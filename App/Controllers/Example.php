<?php

namespace App\Controllers;

class Example extends IController {
    /**
     * @var \App\Model\Example;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function helloWorld() {
        $this->returnJson(0, 'Hello World!');
    }
}