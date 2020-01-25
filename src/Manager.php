<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use ClickHouseDB\Client;
use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;

/**
 * Class Manager
 *
 * @package Khaydarovm\Clickhouse\Migrator
 */
class Manager
{
    /**
     * @var string
     */
    private $configPath;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Migrator
     */
    private $migrator;

    public function __construct(string $path)
    {
        $this->configPath = $path;
    }

    /**
     * @throws Exceptions\ConfigException
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        if (!$this->config) {
            $configManager = new ConfigManager($this->configPath);
            $this->config = $configManager->getConfig();
        }

        return $this->config;
    }

    /**
     * @param string $environment
     *
     * @throws Exceptions\ConfigException
     *
     * @return Manager
     */
    public function prepare(string $environment): self
    {
        $this->migrator = new Migrator($this->getClient($environment), $this->getConfig());
        $this->migrator->prepare();

        return $this;
    }

    public function migrate()
    {
        $this->migrator->migrate();
    }

    public function rollback()
    {
        // todo
    }

    /**
     * @param string $environment
     *
     * @throws Exceptions\ConfigException
     *
     * @return Client
     */
    public function getClient(string $environment = ''): Client
    {
        $config = $this->getConfig();

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
}
