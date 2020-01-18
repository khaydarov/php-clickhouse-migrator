<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parser;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlConfig
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parser
 */
class YamlConfig implements ConfigParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $configPath): array
    {
        return Yaml::parseFile($configPath);
    }
}
