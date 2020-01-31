<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException;
use Khaydarovm\Clickhouse\Migrator\Revision;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Migrate
 *
 * @package Khaydarovm\Clickhouse\Migrator\Console\Command
 */
class Migrate extends AbstractCommand
{
    /**
     * @var bool
     */
    protected $requireConfig = true;

    /**
     * @var bool
     */
    protected $requireEnvironment = true;

    /**
     * @inheritDoc
     */
    protected function configure(): void
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

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws ConfigException
     * @throws MigrationException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $environment = $input->getOption('environment');
        $revision = $input->getOption('revision');

        try {
            $this->getManager()
                ->prepare($environment)
                ->beforeExecution(function (Revision $revision) use ($output) {
                    $output->writeln(sprintf('Starting revision: %s', $revision->getRevisionClass()));
                })
                ->afterExecution(function (Revision $revision) use ($output) {
                    $output->writeln(sprintf('Revision %s is done.', $revision->getRevisionClass()));
                })
                ->whenDone(function () use ($output) {
                    $output->writeln('<info>Successfully migrated!</info>');
                })
                ->migrate($revision);
        } catch (MigrationException $e) {
            $output->writeln(sprintf('<comment>%s</comment>', $e->getMessage()));
        }

        return 0;
    }
}
