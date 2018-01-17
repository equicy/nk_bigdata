<?php

namespace App\Model;

use Sim\IModel;

class TjTourist extends IModel{

    function __construct() {
        parent::__construct();
    }

    function getRow($year) {
        return $this->select()->from('tj_tourist_attraction_statistics')
            ->where('year = :y', [':y' => $year])
            ->row();
    }
}