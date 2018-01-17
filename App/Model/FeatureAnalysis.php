<?php

namespace App\Model;

use Sim\IModel;

class FeatureAnalysis extends IModel {
    protected $table;

    function __construct() {
        parent::__construct();
    }

    function getTourist($year, $month) {
        $this->table = 'feature_tourist';
        return $this->_query($year, $month);
    }

    function getSpend($year, $month) {
        $this->table = 'feature_spend';
        return $this->_query($year, $month);
    }

    function getFocus($year, $month) {
        $this->table = 'feature_focus';
        return $this->_query($year, $month);
    }

    function getSupplySide($year, $month) {
        $this->table = 'feature_supply_side';
        return $this->_query($year, $month);
    }

    protected function _query($year, $month) {
        return $this->select()->from($this->table)
            ->where('year = :y', [':y' => $year])
            ->andWhere('month = :m', [':m' => $month])
            ->row();
    }
}