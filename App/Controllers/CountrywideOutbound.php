<?php

namespace App\Controllers;

use Sim\IController;

class CountrywideOutbound extends IController {
    /**
     * @var \App\Model\CountrywideOutbound;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        //type:   团队数: 0 人数: 1
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']), intval($this->param['type'] == 1));
        $data['outbound'] = json_decode($data['data'], true);
        $this->returnJson(0, $data);
    }
}