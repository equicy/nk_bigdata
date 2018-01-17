<?php

namespace App\Model;

use Sim\IModel;

class ManageCountrywideInbound extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('countrywide_inbound')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertRow($data) {
        return $this->insert('countrywide_inbound', 'year, month, data, type')
            ->values(':y, :m, :d, :t', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['data'],
                ':t' => $data['type']
            ])->exec();
    }

    function inboundUpdate($id, $data) {
        return $this->update('countrywide_inbound')
            ->set('data = :d', [':d' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}