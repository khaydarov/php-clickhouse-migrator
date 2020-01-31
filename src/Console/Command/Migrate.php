<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Khaydarovm\Clickhouse\Migrator\Revision;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException
     * @throws \Khaydarovm\Clickhouse\Migrator\Exceptions\MigrationException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $environment = $input->getOption('environment');

        $this->getManager()
            ->prepare($environment)
            ->beforeRevisionMigration(function (Revision $revision) use ($output) {
                $output->writeln(sprintf('Revision: %s', $revision->getRevisionClass()));
            })
            ->afterRevisionMigration(function (Revision $revision) use ($output) {
                $output->writeln('done');
            })
            ->whenDone(function () use ($output) {
                $output->writeln('<info>Successfully migrated!</info>');
            })
            ->migrate();

        return 0;
    }
}
