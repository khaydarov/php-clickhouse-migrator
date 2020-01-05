<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

//use ClickHouseDB\Client;
//use Osnova\ClickHouse\Migrator\Config;

abstract class AbstractMigration
{
//    /**
//     * @var Client
//     */
//    private $client;
//
//    /**
//     * @var Config
//     */
//    private $config;
//
//    /**
//     * AbstractMigration constructor.
//     *
//     * @param Client $client
//     */
//    public function __construct(Client $client, Config $config)
//    {
//        $this->client = $client;
//        $this->config = $config;
//    }
//
//    /**
//     * @return string
//     */
//    protected function getDatabase(): string
//    {
//        return $this->config->getDatabaseName();
//    }
//
//    /**
//     * @return string
//     */
//    protected function getCluster(): string
//    {
//        return $this->config->getClusterName();
//    }
//
//    /**
//     * @param EngineBuilder $builder
//     */
//    protected function executeFromBuilder(EngineBuilder $builder): void
//    {
//        try {
//            $this->client->write($builder->getQuery());
//        } catch (\Exception $e) {
//            var_dump($e->getMessage());
//            exit();
//        }
//    }
//
//    /**
//     * @param string $query
//     */
//    protected function execute(string $query): void
//    {
//        try {
//            $this->client->write($query);
//        } catch (\Exception $e) {
//            var_dump($e->getMessage());
//            exit();
//        }
//    }
//
//    /**
//     * @return mixed|void
//     */
//    abstract public function up();
//
//    /**
//     * @return mixed|void
//     */
//    abstract public function down();
}