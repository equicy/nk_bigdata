<?php

namespace App\Controllers;

use Sim\IController;

class Provinces extends IController {
    /**
     * @var \App\Model\Provinces;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']));
        $data['reception_number'] = json_decode($data['reception_number'], true);
        $this->returnJson(0, $data);
    }
}