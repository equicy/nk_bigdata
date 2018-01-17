<?php

namespace App\Controllers;

use Sim\IController;

class TjTourist extends IController {
    /**
     * @var \App\Model\TjTourist;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']));
        $data['data'] = json_decode($data['data'], true);
        $i = 0;
        $rows = [];
        foreach ($data['data'] as $row) {
            $i++;
            $keys = array_keys($row[$i]);
            $values = array_values($row[$i]);
            $rows[$keys[0]][] = $values[0];
            $rows[$keys[1]][] = $values[1];
        }
        $data['data'] = $rows;
        $this->returnJson(0, $data);
    }
}