<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;

class Migrator
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $schema = 'migrations_schema';

    /**
     * @var array
     */
    private $appliedRevisions = [];

    /**
     * @var int
     */
    private $startTime;

    /**
     * @var int
     */
    private $endTime;

    /**
     * Migrator constructor.
     *
     * @param Client $client
     * @param Config $config
     */
    public function __construct(Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function prepare()
    {
        $sql = sprintf('
            CREATE TABLE IF NOT EXISTS %s (
              event_date date,
              version UInt64,
              migration_name String,
              start_time datetime,
              end_time datetime
            ) ENGINE = MergeTree()
            PARTITION BY toYYYYMM(event_date)
            ORDER BY (version)
        ', $this->schema);

        $this->client->write($sql);

        $this->appliedRevisions = $this->getAppliedRevisions();
    }

    /**
     * @param Revision $revision
     */
    public function beforeExecution(Revision $revision)
    {
        $this->startTime = time();
        echo sprintf("[Migrator] %s:%s", $revision->getId(), $revision->getRevisionClass());
        echo PHP_EOL;
    }

    public function migrate()
    {
        $migrationPath = $this->config->getMigrationsPath();
        $revisions = $this->getAvailableRevisions($migrationPath);

        foreach ($revisions as $revision) {
            /** @var Revision $revision */
            $path = $this->getRevisionFile($migrationPath, $revision);

            include_once $path;

            $revisionClass = $revision->getRevisionClass();

            /** @var AbstractMigration $revisionInstance */
            $revisionInstance = new $revisionClass($this->client, $this->config);

            try {
                $this->beforeExecution($revision);
                $revisionInstance->up();
                $this->afterExecution($revision);
            } catch (\Exception $e) {
                //
            }
        }
    }

    /**
     * @param Revision $revision
     *
     * @throws \Exception
     */
    public function afterExecution(Revision $revision)
    {
        $this->endTime = time();

        try {
            $statement = $this->client->insert(
                $this->schema,
                [
                    [
                        date('Y-m-d'),
                        (int) $revision->getId(),
                        $revision->getRevisionClass(),
                        $this->startTime,
                        $this->endTime
                    ]
                ],
                [
                    'event_date',
                    'version',
                    'migration_name',
                    'start_time',
                    'end_time'
                ]
            );
        } catch (\Exception $e) {
            throw new \Exception("after error");
        }

        if ($statement->isError()) {
            throw new \Exception("erorr");
        }

        echo sprintf("[Migrator]: done!\n\n");
    }

    /**
     * returns all applied revisions
     *
     * @return array
     */
    private function getAppliedRevisions(): array
    {
        $sql = sprintf("SELECT version FROM %s", $this->schema);
        $statement = $this->client->select($sql);

        return array_column($statement->rows(), 'version');
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private function getAvailableRevisions(string $path): array
    {
        $list = [];
        $directory = new \DirectoryIterator($path);

        foreach ($directory as $file) {
            if (!$file->isFile()) {
                continue;
            }

            list($id, $className) = explode('_', $file->getBasename('.php'));

            if (\in_array($id, $this->appliedRevisions, true)) {
                continue;
            }

            $revision = new Revision();
            $revision
                ->setId($id)
                ->setName($className)
                ->setFilename($file->getFilename());

            $list[$id] = $revision;
        }

        asort($list);

        return $list;
    }

    /**
     * @param string   $path
     * @param Revision $revision
     *
     * @return string
     */
    private function getRevisionFile(string $path, Revision $revision): string
    {
        return sprintf('%s/%s', $path, $revision->getRevisionFile());
    }
}
