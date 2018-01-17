<?php

namespace App\Controllers;

use Sim\IController;

class TjOtherProvinces extends IController {
    /**
     * @var \App\Model\TjOtherProvinces;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']));
        $data['out_provinces'] = json_decode($data['out_provinces'], true);
        $data['ranking'] = json_decode($data['ranking'], true);
        foreach ($data['out_provinces'] as $row) {
            $data['map'][] = [['name' => '天津'], ['name' => array_shift(array_keys($row)), 'value' => array_shift(array_values($row))]];
            $data['pie'][] = ['name' => array_shift(array_keys($row)), 'value' => array_shift(array_values($row))];
        }
        $data['histogram'] = $data['out_provinces'];
        unset($data['out_provinces']);
        $this->returnJson(0, $data);
    }

}