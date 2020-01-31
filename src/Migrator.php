<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;

/**
 * Class Migrator
 *
 * @package Khaydarovm\Clickhouse\Migrator
 */
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

    /**
     * Creates table with migrations
     * Prepares applied revisions
     */
    public function prepare(): void
    {
        $sql = sprintf('
            CREATE TABLE IF NOT EXISTS %s (
              event_date date,
              version UInt64,
              migration_name String,
              start_time datetime,
              end_time datetime,
              status String
            ) ENGINE = MergeTree()
            PARTITION BY toYYYYMM(event_date)
            ORDER BY (version)
        ', $this->schema);

        $this->client->write($sql);
    }

    /**
     * @param Revision $revision
     */
    public function beforeExecution(Revision $revision): void
    {
        $this->startTime = time();
    }

    /**
     * @param string   $path
     * @param Revision $revision
     *
     * @throws MigrationException
     */
    public function migrate(string $path, Revision $revision): void
    {
        include_once $path;

        $class = $revision->getRevisionClass();

        /** @var AbstractMigration $instance */
        $instance = new $class($this->client, $this->config);

        // set timer
        $this->beforeExecution($revision);

        // try to up
        try {
            $instance->up();
        } catch (\Exception $e) {
            throw new MigrationException(sprintf('Migration error: %s', $e->getMessage()));
        }

        // stop time and save revision
        $this->afterExecution($revision);
    }

    /**
     * @todo implement
     *
     * @param string   $path
     * @param Revision $revision
     */
    public function rollback(string $path, Revision $revision): void
    {
    }

    /**
     * @param Revision $revision
     * @param string   $status
     *
     * @throws MigrationException
     */
    public function afterExecution(Revision $revision, string $status = 'up'): void
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
                        $this->endTime,
                        $status
                    ]
                ],
                [
                    'event_date',
                    'version',
                    'migration_name',
                    'start_time',
                    'end_time',
                    'status'
                ]
            );
        } catch (\Exception $e) {
            throw new MigrationException(sprintf('Revision error: %s', $e->getMessage()));
        }

        if ($statement->isError()) {
            throw new MigrationException('Clickhouse statement error');
        }
    }

    /**
     * returns all applied revisions
     *
     * @return array
     */
    public function getAppliedRevisions(): array
    {
        $sql = sprintf('SELECT version FROM %s', $this->schema);
        $statement = $this->client->select($sql);

        return array_column($statement->rows(), 'version');
    }
}
