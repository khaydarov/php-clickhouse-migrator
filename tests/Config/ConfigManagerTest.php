<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config;

use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use PHPUnit\Framework\TestCase;

class ConfigManagerTest extends TestCase
{
    /**
     * @throws ConfigException
     */
    public function testGetConfig()
    {
        // load PHP config
        $manager = new ConfigManager(__DIR__ . '/configs/config.php');
        $config = $manager->getConfig();

        $this->assertInstanceOf(Config::class, $config);

        // load YAML config
        $manager = new ConfigManager(__DIR__ . '/configs/config.yaml');
        $config = $manager->getConfig();

        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @throws ConfigException
     */
    public function testGetConfigUnsupportedExtension()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Extension is not supported');

        // XML is not supported
        $manager = new ConfigManager(__DIR__ . '/configs/config.xml');
        $config = $manager->getConfig();
    }

    /**
     * @throws ConfigException
     */
    public function testGetConfigFileNotFound()
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Config file not found');

        // XML is not supported
        $manager = new ConfigManager(__DIR__ . '/config.php');
        $config = $manager->getConfig();
    }
}
