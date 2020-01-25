<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Init extends AbstractCommand
{
    /**
     * @var array
     */
    private $allowedFormats = ['php', 'yaml'];

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
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectPath = $this->getProjectRoot();

        if (!is_writable($projectPath)) {
            throw new \Exception(sprintf("Directory %s must be writable", $projectPath));
        }

        $format = $input->getOption('format');

        if (!in_array($format, $this->allowedFormats, true)) {
            throw new \Exception(sprintf("Config format not allowed"));
        }

        $template = sprintf("%s/../../../templates/config.%s", __DIR__, $format);
        $targetPath = sprintf("%s/config.%s", $projectPath, $format);

        if (!copy($template, $targetPath)) {
            throw new \Exception(sprintf("Can't write config to target directory"));
        }

        $output->writeln(sprintf("config.%s created at %s", $format, $projectPath));
    }
}
