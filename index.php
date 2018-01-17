<?php
/*
 * 这是Sim入口文件
 */
$php_version = phpversion();
/*if (version_compare($php_version, '7.1.0', '<')) {
    echo "PHP version $php_version < PHP 7.1.0. Please install PHP 7.1.0 or above.\n";
    exit(1);
}*/
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

session_start();
date_default_timezone_set('Asia/Shanghai');
define('CONTROLLER_NAMESPACE', 'App\\Controllers\\');
define('MODEL_NAMESPACE', 'App\\Model\\');

require_once __DIR__ . '/Sim/loader.php';
foreach (glob(__DIR__ . '/Config/*.php') as $file)
    require_once $file;
foreach (glob(__DIR__ . '/Include/*.php') as $file)
    require_once $file;
require_once __DIR__ . '/App/loader.php';
require_once __DIR__ . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors',0);
ini_set('log_errors',1);

Sim\Core\Core::start();
