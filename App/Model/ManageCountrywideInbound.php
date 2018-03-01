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
        return $this->insert('countrywide_inbound', 'year, month, inbound_count, customer_source')
            ->values(':y, :m, :d, :t', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['inbound_count'],
                ':t' => $data['customer_source']
            ])->exec();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function customerSourceUpdate($id, $data) {
        return $this->_dataUpdate($id, $data, 'customer_source');
    }

    function inboundCountUpdate($id, $data) {
        return $this->_dataUpdate($id, $data, 'inbound_count');
    }

    function _dataUpdate($id, $data, $key) {
        return $this->update('countrywide_inbound')
            ->set($key.' = :d', [':d' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}