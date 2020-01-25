<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
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
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $input->getOption('environment');

        if (empty($environment)) {
            throw new \Exception("Environment is empty");
        }

        $this->getManager()
            ->prepare($environment)
            ->migrate();

        return 0;
    }
}
