<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parsers;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConfigParser
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parsers
 */
class YamlConfigParser extends ConfigParser
{
    /**
     * @inheritDoc
     */
    public static function parse(string $configPath): array
    {
        return Yaml::parseFile($configPath);
    }
}
