<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config;

/**
 * Class Config
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config
 */
class Config
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->config['database'];
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->config['host'];
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->config['port'];
    }

    /**
     * @return string
     */
    public function getMigrationsPath(): string
    {
        return $this->config['migrationsPath'];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->config['username'];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->config['password'];
    }
}
