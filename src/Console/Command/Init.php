<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Console\Command;

use Khaydarovm\Clickhouse\Migrator\Console\AbstractCommand;
use Khaydarovm\Clickhouse\Migrator\Exceptions\ConfigException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Init
 *
 * @package Khaydarovm\Clickhouse\Migrator\Console\Command
 */
class Init extends AbstractCommand
{
    /**
     * @var array
     */
    private $allowedFormats = ['php', 'yaml'];

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('init')
            ->setDescription('Initialize Clickhouse migrations project')
            ->setHelp('Initialize Clickhouse migrations project')
            ->addOption(
                '--format',
                '-f',
                InputOption::VALUE_REQUIRED
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
        $projectPath = $this->getProjectRoot();

        if (!is_writable($projectPath)) {
            throw new ConfigException(sprintf('Directory %s must be writable', $projectPath));
        }

        $format = $input->getOption('format');

        if (!in_array($format, $this->allowedFormats, true)) {
            throw new ConfigException(sprintf('Config format not allowed'));
        }

        $template = sprintf('%s/../../../templates/config.%s', __DIR__, $format);
        $targetPath = sprintf('%s/config.%s', $projectPath, $format);

        if (!copy($template, $targetPath)) {
            throw new ConfigException(sprintf("Can't write config to target directory"));
        }

        $output->writeln(sprintf('<info>config.%s created at %s</info>', $format, $projectPath));

        return 0;
    }
}
