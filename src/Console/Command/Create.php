<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Create
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

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');

        if (empty($name)) {
            throw new \Exception('Name is not specified');
        }

        if (!preg_match('/^([A-Z][a-z0-9]+)+$/', $name)) {
            throw new \Exception('Name must be in CamelCase notation');
        }

        $directory = $this->getManager()->getConfig()->getMigrationsPath();
        $migrationFile = sprintf("%s/%s_%s.php", $directory, date('YmdHis'), $name);

        if (file_exists($migrationFile)) {
            throw new \Exception("Migration file with same name already exists");
        }

        if (!is_dir($directory)) {
            throw new \Exception(sprintf("Directory doesn't exist"));
        }

        $migrationFileContent = str_replace(
            'MigrationTemplate',
            $name,
            file_get_contents(sprintf('%s/../../../templates/MigrationTemplate.php', __DIR__))
        );

        file_put_contents($migrationFile, $migrationFileContent);

        $output->writeln(sprintf("New migration create at %s", $migrationFile));

        return 0;
    }
}
