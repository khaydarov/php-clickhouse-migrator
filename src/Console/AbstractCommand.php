<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console;

use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use Khaydarovm\Clickhouse\Migrator\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AbstractCommand
 *
 * @package Khaydarovm\Clickhouse\Migrator\Console
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var bool
     */
    protected $requireConfig = false;

    /**
     * @var bool
     */
    protected $requireEnvironment = false;

    /**
     * @var Manager
     */
    private $manager;

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();

        if ($this->requireConfig) {
            $this->addOption(
                '--configuration',
                '-c',
                InputOption::VALUE_REQUIRED,
                'Config file'
            );
        }

        if ($this->requireEnvironment) {
            $this->addOption(
                '--environment',
                '-e',
                InputOption::VALUE_REQUIRED,
                'Environment name'
            );
        }
    }

    /**
     * @inheritDoc
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws ConfigException
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        if ($this->requireConfig) {
            $configPath = $input->getOption('configuration');

            if (empty($configPath) || !file_exists($configPath)) {
                throw new ConfigException("Can't find config file");
            }
            $this->init($configPath);
        }
    }

    /**
     * @return false|string
     */
    protected function getProjectRoot(): string
    {
        return getcwd();
    }

    /**
     * @param string $path
     *
     * @throws ConfigException
     */
    protected function init(string $path): void
    {
        $this->manager = new Manager(new ConfigManager($path));
    }

    /**
     * @return Manager
     */
    protected function getManager(): Manager
    {
        return $this->manager;
    }
}
