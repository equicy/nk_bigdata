<?php

namespace App\Controllers;

use Sim\IController;

class TjInbound extends IController {
    /**
     * @var \App\Model\TjInbound;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']));

        $data['inbound'] = json_decode($data['inbound'], true);
        $data['entryport_team_number'] = json_decode($data['entryport_team_number'], true);
        foreach ($data['inbound'] as $row) {
            $data['map'][] = ['name' => array_shift(array_keys($row)), 'value' => array_shift(array_values($row))];
        }
        $data['pie'] = $data['map'];
        $data['histogram'] = $data['inbound'];
        $this->returnJson(0, $data);
    }
}