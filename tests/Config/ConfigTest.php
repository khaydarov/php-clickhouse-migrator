<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\MigratorTests\Config;

use Khaydarovm\Clickhouse\Migrator\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigClass()
    {
        $config = [
            'host' => 'localhost',
            'port' => '2333',
            'username' => 'test',
            'password' => 'pass',
            'migrationsPath' => 'migrations',
            'database' => 'db',
        ];

        $fixture = new Config($config);

        $this->assertSame($fixture->getHost(), $config['host']);
        $this->assertSame($fixture->getPort(), $config['port']);
        $this->assertSame($fixture->getUsername(), $config['username']);
        $this->assertSame($fixture->getPassword(), $config['password']);
        $this->assertSame($fixture->getDatabase(), $config['database']);
        $this->assertSame($fixture->getMigrationsPath(), $config['migrationsPath']);
    }
}