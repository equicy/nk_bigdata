<?php

namespace App\Controllers;

use Sim\IController;

class Middleware extends IController {
    /**
     * @var \App\Model\Middleware;
     */
    protected $model;

    function __construct($params)
    {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function normal() {
        /*if(!in_array($_SESSION['power'], [10, 100])) {
            $this->error(3001, '权限不足');
        }*/
    }

    function manager() {
        /*if($_SESSION['power'] != 100) {
            $this->error(3002, '权限不足');
        }*/
    }
}