<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\MigratorTests\Config;

use Khaydarovm\Clickhouse\Migrator\Config\Config;
use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ClickhouseMigratorConfigException;
use PHPUnit\Framework\TestCase;

class ConfigManagerTest extends TestCase
{
    /**
     * @throws ClickhouseMigratorConfigException
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
     * @throws ClickhouseMigratorConfigException
     */
    public function testGetConfigUnsupportedExtension()
    {
        $this->expectException(ClickhouseMigratorConfigException::class);
        $this->expectExceptionMessage('Extension is not supported');

        // XML is not supported
        $manager = new ConfigManager(__DIR__ . '/configs/config.xml');
        $config = $manager->getConfig();
    }

    /**
     * @throws ClickhouseMigratorConfigException
     */
    public function testGetConfigFileNotFound()
    {
        $this->expectException(ClickhouseMigratorConfigException::class);
        $this->expectExceptionMessage('Config file not found');

        // XML is not supported
        $manager = new ConfigManager(__DIR__ . '/config.php');
        $config = $manager->getConfig();
    }
}
