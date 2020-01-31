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
    private $data;

    /**
     * Config constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getCluster(): string
    {
        return $this->data['cluster'];
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->data['database'];
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->data['host'];
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->data['port'];
    }

    /**
     * @return string
     */
    public function getMigrationsPath(): string
    {
        return $this->data['migrations'];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->data['username'];
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->data['password'] ?? '';
    }
}
