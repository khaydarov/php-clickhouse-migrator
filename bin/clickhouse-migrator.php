<?php
declare(strict_types=1);

use Khaydarovm\Clickhouse\Migrator\Application;
use Khaydarovm\Clickhouse\Migrator\Config\ConfigManager;

$autoloadPathList = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

foreach ($autoloadPathList as $autoloadPath) {
    if (!file_exists($autoloadPath)) {
        continue;
    }

    require_once $autoloadPath;
}

$application = new Application();
$application->run();
//$manager = new ConfigManager(__DIR__ . '/config.php');
//$config = $manager->getConfig();
//
//$client = new ClickHouseDB\Client([
//    'host' => $config->getHost(),
//    'port' => $config->getPort(),
//    'username' => $config->getUsername(),
//    'password' => $config->getPassword()
//]);
//$client->database($config->getDatabase());
//
//var_dump($client->ping());