<?php

namespace App\Controllers;

use Sim\IController;

class CountrywideInbound extends IController {
    /**
     * @var \App\Model\CountrywideInbound;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']), intval($this->param['type'] == 1));
        $data['inbound'] = json_decode($data['data'], true);
        //unset($data['data']['data']);
        $this->returnJson(0, $data);
    }
}