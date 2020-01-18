<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator;

use Khaydarovm\Clickhouse\Migrator\Console\Command\Create;
use Khaydarovm\Clickhouse\Migrator\Console\Command\Init;
use Khaydarovm\Clickhouse\Migrator\Console\Command\Migrate;
use Khaydarovm\Clickhouse\Migrator\Console\Command\Rollback;
use Khaydarovm\Clickhouse\Migrator\Console\Command\Status;

class Application extends \Symfony\Component\Console\Application
{
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);

        $this->add(new Init);
        $this->add(new Create);
        $this->add(new Migrate);
        $this->add(new Rollback);
        $this->add(new Status);
    }
}
