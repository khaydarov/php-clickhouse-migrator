<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

abstract class AbstractCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->addOption(
            '--configuration',
            '-c',
            InputOption::VALUE_REQUIRED,
            'The configuration file'
        );

        $this->addOption(
            '--environment',
            '-e',
            InputOption::VALUE_REQUIRED,
            'Environment name'
        );
    }
}
