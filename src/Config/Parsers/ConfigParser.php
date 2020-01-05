<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parsers;

/**
 * Class ConfigParser
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parsers
 */
abstract class ConfigParser
{
    abstract public static function parse(string $configPath): array;
}
