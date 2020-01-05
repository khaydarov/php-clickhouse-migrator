<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\MigratorBenchMarks\Config;

use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;

class ConfigManagerBench
{
    /**
     * @OutputTimeUnit("seconds", precision=5)
     */
    public function benchPhpConfig()
    {
        // load PHP config
        $manager = new ConfigManager(__DIR__ . '/configs/config.php');
        $manager->getConfig();
    }

    /**
     * @OutputTimeUnit("seconds", precision=3)
     */
    public function benchYamlConfig()
    {
        // load PHP config
        $manager = new ConfigManager(__DIR__ . '/configs/config.yaml');
        $manager->getConfig();
    }
}
