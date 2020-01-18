<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Rollback extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('rollback')
            ->setDescription('Rollback to specific revision of database')
            ->setHelp('Rollback to specific revision of database')
            ->addOption(
                '--revision',
                '-r',
                InputOption::VALUE_OPTIONAL,
                'Migration revision. In case of empty revision it rollbacks at all'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 1;
    }
}
