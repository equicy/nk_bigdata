<?php

namespace App\Model;

use Sim\IModel;

class ManageTjTouristAttractions extends IModel{
    function __construct() {
        parent::__construct();
    }

    function getRow($year) {
        return $this->select()
            ->from('tj_tourist_attraction_statistics')
            ->where('year = :y', [':y' => $year])
            ->row();
    }

    function insertRow($data) {
        return $data = $this->insert('tj_tourist_attraction_statistics', 'year, data')
            ->values("'$data[year]','$data[data]'")
            ->exec();
    }

    function attractionUpdate($year, $data) {
        return $this->update('tj_tourist_attraction_statistics')
            ->set('data = :d', [':d' => $data])
            ->where('year = :y', [':y' => $year])
            ->save();
    }
}