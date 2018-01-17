<?php

namespace App\Controllers;

use Sim\IController;

class TjOutbound extends IController {
    /**
     * @var \App\Model\TjOutbound;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function get() {
        $data = $this->model->getRow(intval($this->param['year']), intval($this->param['month']));

        $data['outbound'] = json_decode($data['outbound'], true);
        $data['exitport_team_number'] = json_decode($data['exitport_team_number'], true);
        foreach ($data['outbound'] as $row) {
            $data['map'][] = ['name' => array_shift(array_keys($row)), 'value' => array_shift(array_values($row))];
        }
        $data['pie'] = $data['map'];
        $data['histogram'] = $data['outbound'];
        $this->returnJson(0, $data);
    }
}