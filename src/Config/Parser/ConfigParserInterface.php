<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parser;

/**
 * Interface
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parser
 */
interface ConfigParserInterface
{
    public function parse(string $configPath): array;
}
