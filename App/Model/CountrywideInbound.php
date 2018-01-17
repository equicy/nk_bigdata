<?php

namespace App\Model;

use Sim\IModel;

class CountrywideInbound extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getRow($year, $month, $type) {
        return $this->select()->from('countrywide_inbound')
            ->where('year = :y', [':y' => $year])
            ->andWhere('month = :m', [':m' => $month])
            ->andWhere('type = :t', [':t' => $type])
            ->row();
    }

}