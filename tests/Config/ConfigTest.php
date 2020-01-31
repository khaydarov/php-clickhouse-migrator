<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testConfigClass()
    {
        $config = [
            'host' => 'localhost',
            'port' => 2333,
            'cluster' => 'staging',
            'username' => 'test',
            'password' => 'pass',
            'migrations' => 'migrations',
            'database' => 'db',
        ];

        $fixture = new Config($config);

        $this->assertSame($fixture->getCluster(), $config['cluster']);
        $this->assertSame($fixture->getHost(), $config['host']);
        $this->assertSame($fixture->getPort(), $config['port']);
        $this->assertSame($fixture->getUsername(), $config['username']);
        $this->assertSame($fixture->getPassword(), $config['password']);
        $this->assertSame($fixture->getDatabase(), $config['database']);
        $this->assertSame($fixture->getMigrationsPath(), $config['migrations']);
    }
}
