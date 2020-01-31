<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Create
 *
 * @package Khaydarovm\Clickhouse\Migrator\Console\Command
 */
class Create extends AbstractCommand
{
    /**
     * @var bool
     */
    protected $requireConfig = true;

    /**
     * @inheritDoc
     */
    protected function configure(): void
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

    /**
     * @inheritDoc
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws ConfigException
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (empty($name)) {
            throw new ConfigException('Name is not specified');
        }

        if (!preg_match('/^([A-Z][a-z0-9]+)+$/', $name)) {
            throw new ConfigException('Name must be in CamelCase notation');
        }

        $directory = $this->getManager()->getMigrationsPath();
        $migrationFile = sprintf("%s/%s_%s.php", $directory, date('YmdHis'), $name);

        if (file_exists($migrationFile)) {
            throw new ConfigException('Migration file with same name already exists');
        }

        if (!is_dir($directory)) {
            throw new ConfigException(sprintf("Directory doesn't exist"));
        }

        $migrationFileContent = str_replace(
            'MigrationTemplate',
            $name,
            file_get_contents(sprintf('%s/../../../templates/MigrationTemplate.php', __DIR__))
        );

        file_put_contents($migrationFile, $migrationFileContent);

        $output->writeln(sprintf("<info>New migration create at %s</info>", $migrationFile));

        return 0;
    }
}
