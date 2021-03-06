<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;

/**
 * Class Manager
 *
 * @package Khaydarovm\Clickhouse\Migrator
 */
class Manager
{
    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * @var callable
     */
    private $beforeExecutionCallback;

    /**
     * @var callable
     */
    private $afterExecutionCallback;

    /**
     * @var callable
     */
    private $whenDoneCallback;

    /**
     * Manager constructor.
     *
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }

    /**
     * @param string|null $environment
     *
     * @throws Exceptions\ConfigException
     *
     * @return Config
     */
    public function getConfig(string $environment = null): Config
    {
        return $this->configManager->getConfig($environment);
    }

    /**
     * @param string|null $environment
     *
     * @throws Exceptions\ConfigException
     *
     * @return Manager
     */
    public function prepare(string $environment = null): self
    {
        $this->migrator = new Migrator(
            $this->getClient($environment),
            $this->getConfig($environment),
        );

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return Manager
     */
    public function beforeExecution(callable $callable): self
    {
        $this->beforeExecutionCallback = $callable;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return Manager
     */
    public function afterExecution(callable $callable): self
    {
        $this->afterExecutionCallback = $callable;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return Manager
     */
    public function whenDone(callable $callable): self
    {
        $this->whenDoneCallback = $callable;

        return $this;
    }

    /**
     * @param string|null $revisionId
     *
     * @throws MigrationException
     */
    public function migrate(string $revisionId = null): void
    {
        // Prepare Migrator schema
        $this->migrator->prepare();

        $migrationPath = $this->getMigrationsPath();

        $appliedRevisions = $this->migrator->getAppliedRevisions();

        if (\in_array($revisionId, $appliedRevisions, true)) {
            throw new MigrationException('This revision is already applied');
        }

        if (!$revisionId) {
            $revisions = $this->getAvailableRevisions($migrationPath, $appliedRevisions);
        } else {
            $revisions = [$this->getRevisionById($migrationPath, $revisionId)];
        }

        foreach ($revisions as $revision) {
            /** @var Revision $revision */
            $path = $this->getRevisionPath($migrationPath, $revision);

            if ($this->beforeExecutionCallback) {
                call_user_func($this->beforeExecutionCallback, $revision);
            }

            $this->migrator->migrate($path, $revision);

            if ($this->afterExecutionCallback) {
                call_user_func($this->afterExecutionCallback, $revision);
            }
        }

        // ready
        if ($this->whenDoneCallback) {
            call_user_func($this->whenDoneCallback);
        }
    }

    /**
     * @todo implement
     *
     * @param string|null $revisionId
     */
    public function rollback(string $revisionId = null)
    {
//        $this->migrator->rollback();
    }

    /**
     * @param string|null $environment
     *
     * @throws Exceptions\ConfigException
     *
     * @return Client
     */
    public function getClient(string $environment = null): Client
    {
        $config = $this->getConfig($environment);

        if (!$this->client) {
            $this->client = new Client([
                'host' => $config->getHost(),
                'port' => $config->getPort(),
                'username' => $config->getUsername(),
                'password' => $config->getPassword()
            ]);

            $this->client->database($config->getDatabase());
        }

        return $this->client;
    }

    /**
     * @return string
     */
    public function getMigrationsPath(): string
    {
        return $this->configManager->getMigrationsPath();
    }

    /**
     * @param string   $path
     * @param Revision $revision
     *
     * @throws MigrationException
     *
     * @return string
     */
    private function getRevisionPath(string $path, Revision $revision): string
    {
        return sprintf('%s/%s', $path, $revision->getRevisionFile());
    }

    /**
     * @param string $path
     * @param array  $alreadyApplied
     *
     * @throws MigrationException
     *
     * @return array
     */
    public function getAvailableRevisions(string $path, array $alreadyApplied): array
    {
        if (!is_dir($path)) {
            throw new MigrationException('Path is not directory');
        }

        $list = [];
        $directory = new \DirectoryIterator($path);

        foreach ($directory as $file) {
            if (!$file->isFile()) {
                continue;
            }

            list($id, $className) = explode('_', $file->getBasename('.php'));

            if (\in_array($id, $alreadyApplied, true)) {
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
     * @param string $path
     * @param string $revisionId
     *
     * @throws MigrationException
     *
     * @return Revision
     */
    public function getRevisionById(string $path, string $revisionId): Revision
    {
        if (!is_dir($path)) {
            throw new MigrationException('Path is not directory');
        }

        $directory = new \DirectoryIterator($path);

        foreach ($directory as $file) {
            if (!$file->isFile()) {
                continue;
            }

            list($id, $className) = explode('_', $file->getBasename('.php'));

            if ($revisionId === $id) {
                $revision = new Revision();
                $revision
                    ->setId($id)
                    ->setName($className)
                    ->setFilename($file->getFilename());

                return $revision;
            }
        }

        throw new MigrationException('Revision not found');
    }
}
