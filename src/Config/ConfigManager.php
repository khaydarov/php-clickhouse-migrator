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
    const PHPConfig = 'php';
    const YAMLConfig = 'yaml';

    /**
     * @var string
     */
    private $configPath;

    /**
     * ConfigManager constructor.
     *
     * @param string $configPath
     */
    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * @throws ConfigException
     *
     * @return Config
     */
    public function getConfig(): Config
    {
        if (!file_exists($this->configPath)) {
            throw new ConfigException('Config file not found');
        }

        $extension = pathinfo($this->configPath, PATHINFO_EXTENSION);

        switch ($extension) {
            case self::PHPConfig:
                $parser = new PhpConfig();
                break;
            case self::YAMLConfig:
                $parser = new YamlConfig();
                break;
            default:
                throw new ConfigException('Extension is not supported');
        }

        $parsedConfig = $parser->parse($this->getConfigPath());
        $config = $this->prepare($parsedConfig);

        return new Config($config);
    }

    /**
     * @return string
     */
    private function getConfigPath(): string
    {
        return $this->configPath;
    }

    /**
     * @param array $parsedConfig
     *
     * @return array
     */
    private function prepare(array $parsedConfig): array
    {
        $defaultEnvironment = $parsedConfig['default_environment'];
        $environmentConfig = $parsedConfig['environments'][$defaultEnvironment];
        $migrationsPath = $parsedConfig['paths']['migrations'];

        return [
            'defaultEnvironment' => $defaultEnvironment,
            'migrationsPath' => $migrationsPath,
            'cluster' => $environmentConfig['cluster'],
            'host' => $environmentConfig['host'],
            'port' => $environmentConfig['port'],
            'database' => $environmentConfig['database'],
            'username' => $environmentConfig['username'],
            'password' => $environmentConfig['password']
        ];
    }
}
