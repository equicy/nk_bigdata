<?php

namespace Sim\Core;

class Route {

    public static function exec() {
        global $_ROUTES;

        $path = $_SERVER['PATH_INFO'] ?? '/';
        $path = preg_replace('/\/+/', '/', $path);

        if(strlen($path) != 1 && strpos($path, '/') === 0)
            $path = substr ($path, 1);

        if(!isset($_ROUTES[$path]))
            self::err(404, '404');

        if(($_ROUTES[$path][0] != '*') && ($_SERVER['REQUEST_METHOD'] != strtoupper($_ROUTES[$path][0])))
            self::err(404, '404');

        if(!empty($_ROUTES[$path][3])) {
            self::middleware($_ROUTES[$path][3]);
        }

        $class = CONTROLLER_NAMESPACE . $_ROUTES[$path][1];
        if(class_exists($class)) {
            $instance = new $class($_GET);
        } else {
            self::err(500, 'class not found');
        }

        if(method_exists($instance, $_ROUTES[$path][2])) {
            $method = $_ROUTES[$path][2];
            $instance->$method();
        } else {
            self::err(500, 'method not found');
        }
    }

    protected static function middleware($middleware) {
        $middleware = explode('@', $middleware, 2);
        $middleware[0] = CONTROLLER_NAMESPACE . $middleware[0];
        if(class_exists($middleware[0])) {
            $instance = new $middleware[0]($_GET);
        } else {
            self::err(500, 'middleware class not found');
        }

        if(method_exists($instance, $middleware[1])) {
            $method = $middleware[1];
            $instance->$method();
        } else {
            self::err(500, 'middleware method not found');
        }

    }

    protected static function err($status, $data) {
        $return = [
            'err' => $status,
            'data' => $data
        ];

        exit(json_encode($return));
    }
}