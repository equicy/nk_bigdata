<?php

namespace App\Model;

use Sim\IModel;

class TjInbound extends IModel{
    function __construct() {
        parent::__construct();
    }

    function getRow($year, $month) {
        return $this->select()->from('tj_inbound')
            ->where('year = :y', [':y' => $year])
            ->andWhere('month = :m', [':m' => $month])
            ->row();
    }
}