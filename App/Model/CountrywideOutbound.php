<?php

namespace App\Model;

use Sim\IModel;

class CountrywideOutbound extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getRow($year, $month, $type) {
        return $this->select()->from('countrywide_outbound')
            ->where('year = :y', [':y' => $year])
            ->andWhere('month = :m', [':m' => $month])
            ->andWhere('type = :t', [':t' => $type])
            ->row();
    }

}