<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parsers;

/**
 * Class PhpConfigParser
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parsers
 */
class PhpConfigParser extends ConfigParser
{
    /**
     * @inheritDoc
     */
    public static function parse(string $configPath): array
    {
        return include $configPath;
    }
}
