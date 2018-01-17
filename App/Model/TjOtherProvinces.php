<?php

namespace App\Model;

use Sim\IModel;

class TjOtherProvinces extends IModel{

    function __construct() {
        parent::__construct();
    }

    function getRow($year, $month) {
        return $this->select()->from('tj_other_provinces')
            ->where('year = :y', [':y' => $year])
            ->andWhere('month = :m', [':m' => $month])
            ->row();
    }
}