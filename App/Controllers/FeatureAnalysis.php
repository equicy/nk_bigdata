<?php

namespace App\Controllers;

use Sim\IController;

class FeatureAnalysis extends IController {
    /**
     * @var \App\Model\FeatureAnalysis;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function tourist() {
        $data = $this->model->getTourist($this->param['year'], $this->param['month']);
        $data['age'] = json_decode($data['age'], true);
        $this->_percentShift($data['age']);
        $data['education'] = json_decode($data['education'], true);
        $this->_percentShift($data['education']);
        $this->returnJson(0, $data);
    }

    function spend() {
        $data = $this->model->getSpend($this->param['year'], $this->param['month']);
        $data['family'] = json_decode($data['family'], true);
        $this->_percentShift($data['family']);
        $data['asset'] = json_decode($data['asset'], true);
        $this->_percentShift($data['asset']);
        $data['job'] = json_decode($data['job'], true);
        $this->_percentShift($data['job']);
        $this->returnJson(0, $data);
    }

    function focus() {
        $data = $this->model->getFocus($this->param['year'], $this->param['month']);
        $data['destination'] = json_decode($data['destination'], true);
        $this->_percentShift($data['destination']);
        $data['focus_type'] = json_decode($data['focus_type'], true);
        $this->_percentShift($data['focus_type']);
        $this->returnJson(0, $data);
    }

    function supplySide() {
        $data = $this->model->getSupplySide($this->param['year'], $this->param['month']);
        $data['public_government'] = json_decode($data['public_government'], true);
        //$this->_percentShift($data['public_government']);
        $data['public_business'] = json_decode($data['public_business'], true);
        //$this->_percentShift($data['public_business']);
        $this->returnJson(0, $data);
    }

    function _percentShift(&$data) {
        if(is_array($data)) {
            foreach ($data as &$row) {
                $row['value'] = number_format(floatval($row['value'] * 100), $this->config['PERCENT_DECIMALS']);
            }
        } elseif(is_string($data)) {
            $data = number_format(floatval($data * 100), $this->config['PERCENT_DECIMALS']);
        }
    }
}