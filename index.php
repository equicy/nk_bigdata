<?php
/*
 * 这是Sim入口文件
 */
$php_version = phpversion();
if (version_compare($php_version, '7.1.0', '<')) {
    echo "PHP version $php_version < PHP 7.1.0. Please install PHP 7.1.0 or above.\n";
    exit(1);
}

session_start();
date_default_timezone_set('Asia/Shanghai');
define('CONTROLLER_NAMESPACE', 'App\\Controllers\\');
define('MODEL_NAMESPACE', 'App\\Model\\');

foreach (glob(__DIR__ . '/Sim/loader.php') as $file)
    require_once $file;
foreach (glob(__DIR__ . '/Config/*.php') as $file)
    require_once $file;
foreach (glob(__DIR__ . '/Include/*.php') as $file)
    require_once $file;
require_once __DIR__ . '/App/loader.php';

Sim\Core\Core::start();