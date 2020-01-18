<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('migrate')
            ->setDescription('Migrate to specific revision of database')
            ->setHelp('Migrate to specific revision of database')
            ->addOption(
                '--revision',
                '-r',
                InputOption::VALUE_OPTIONAL,
                'Migration revision. All revisions will be executed in case of empty revision'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return 1;
    }
}
