<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Rollback
 *
 * @package Khaydarovm\Clickhouse\Migrator\Console\Command
 */
class Rollback extends AbstractCommand
{
    /**
     * @inheritDoc
     */
    protected function configure(): void
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

    /**
     * @inheritDoc
     *
     * @todo not implemented yet
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return 1;
    }
}
