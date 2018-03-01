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
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']));
        $data['customer_source'] = json_decode($data['customer_source'], true);
        $data['exitport_people_number'] = json_decode($data['exitport_people_number'], true);
        $this->returnJson(0, $data);
    }
}