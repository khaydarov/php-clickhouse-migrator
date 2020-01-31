<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;

/**
 * Class AbstractMigration
 *
 * @package Khaydarovm\Clickhouse\Migrator
 */
abstract class AbstractMigration
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
     * AbstractMigration constructor.
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
     * @return string
     */
    protected function getDatabase(): string
    {
        return $this->config->getDatabase();
    }

    /**
     * @return string
     */
    protected function getCluster(): string
    {
        return $this->config->getCluster();
    }

    /**
     * @param string $query
     *
     * @throws MigrationException
     */
    protected function execute(string $query): void
    {
        try {
            $this->client->write($query);
        } catch (\Exception $e) {
            throw new MigrationException($e->getMessage());
        }
    }

    abstract public function up(): void;

    abstract public function down(): void;
}
