<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\MigratorBenchMarks\Config;

use Khaydarovm\Clickhouse\Migrator\Config\Config;

class ConfigBench
{
    private $config = [
        'defaultEnvironment' => 'development',
        'migrationsPath' => 'migrations',
        'cluster' => 'development',
        'host' => 'localhost',
        'port' => 111,
        'username' => 'default',
        'password' => 'dummy'
    ];

    /**
     * @Revs(100)
     * @Iterations(5)
     */
    public function benchConfig()
    {
        $config = new Config($this->config);

        $config->getUsername();
    }
}
