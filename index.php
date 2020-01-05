<?php
declare(strict_types=1);

use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;

require_once 'vendor/autoload.php';

$manager = new ConfigManager(__DIR__ . '/config.php');
$config = $manager->getConfig();

$client = new ClickHouseDB\Client([
    'host' => $config->getHost(),
    'port' => $config->getPort(),
    'username' => $config->getUsername(),
    'password' => $config->getPassword()
]);
$client->database($config->getDatabase());

var_dump($client->ping());