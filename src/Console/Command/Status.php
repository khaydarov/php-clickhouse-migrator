<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Status extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('status')
            ->setDescription('Show status of migrations')
            ->setHelp('Show the list of migrations')
            ->addOption(
                '--lenght',
                '-l',
                InputOption::VALUE_OPTIONAL
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 1;
    }
}
