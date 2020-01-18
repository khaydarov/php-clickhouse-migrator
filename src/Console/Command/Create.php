<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Create extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('create')
            ->setDescription('Create new migration file')
            ->setHelp('Create new migration file')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Migration file name in camelCase notation'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        return 1;
    }
}
