<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;

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
        return '';
    }

    /**
     * @param string $query
     */
    protected function execute(string $query): void
    {
        try {
            $this->client->write($query);
        } catch (\Exception $e) {
            exit();
        }
    }

    /**
     * @return mixed|void
     */
    abstract public function up();

    /**
     * @return mixed|void
     */
    abstract public function down();
}
