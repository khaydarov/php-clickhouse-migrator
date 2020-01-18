<?php
declare(strict_types=1);

namespace Khaydarovm\Clickhouse\Migrator\Config\Parser;

/**
 * Class PhpConfig
 *
 * @package Khaydarovm\Clickhouse\Migrator\Config\Parser
 */
class PhpConfig implements ConfigParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse(string $configPath): array
    {
        return include $configPath;
    }
}
