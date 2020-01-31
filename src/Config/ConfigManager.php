<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config;

use Khaydarovm\Clickhouse\Migrator\Config\Parser\PhpConfig;
use Khaydarovm\Clickhouse\Migrator\Config\Parser\YamlConfig;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;

/**
 * Class ConfigManager
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config
 */
class ConfigManager
{
    public const PHP_CONFIG = 'php';
    public const YAML_CONFIG = 'yaml';

    /**
     * @var string
     */
    private $configPath;

    /**
     * @var array
     */
    private $payload;

    /**
     * ConfigManager constructor.
     *
     * @param string $configPath
     *
     * @throws ConfigException
     */
    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;

        if (!file_exists($this->configPath)) {
            throw new ConfigException('Config file not found');
        }

        $extension = pathinfo($this->configPath, PATHINFO_EXTENSION);

        switch ($extension) {
            case self::PHP_CONFIG:
                $parser = new PhpConfig();
                break;
            case self::YAML_CONFIG:
                $parser = new YamlConfig();
                break;
            default:
                throw new ConfigException('Extension is not supported');
        }

        $this->payload = $parser->parse($this->getConfigPath());
    }

    /**
     * @param string|null $environment
     *
     * @throws ConfigException
     *
     * @return Config
     */
    public function getConfig(string $environment = null): Config
    {
        if (!$environment) {
            $environment = $this->payload['default'];
        }

        if (!isset($this->payload['environments'][$environment])) {
            throw new ConfigException('Environment does not exist');
        }

        return new Config($this->payload['environments'][$environment]);
    }

    /**
     * @return string
     */
    public function getMigrationsPath(): string
    {
        return $this->payload['paths']['migrations'];
    }

    /**
     * @return string
     */
    private function getConfigPath(): string
    {
        return $this->configPath;
    }
}
