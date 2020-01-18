<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('init')
            ->setDescription('initialize clickhouse migrations')
            ->setHelp('initialize clickhouse migrations')
            ->addOption(
                '--format',
                '-f',
                InputOption::VALUE_REQUIRED
            )
            ->addOption(
                '--path',
                '-p',
                InputOption::VALUE_OPTIONAL
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo $input->getOption('format');
        echo $input->getOption('path');

        return 1;
    }
}
