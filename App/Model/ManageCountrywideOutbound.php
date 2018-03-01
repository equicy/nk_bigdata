<?php

namespace App\Model;

use Sim\IModel;

class ManageCountrywideOutbound extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('countrywide_outbound')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function insertRow($data) {
        return $this->insert('countrywide_outbound', 'year, month, customer_source, exitport_people_number')
            ->values(':y, :m, :d, :t', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['customer_source'],
                ':t' => $data['exitport_people_number']
            ])->exec();
    }

    function customerSourceUpdate($id, $data) {
        return $this->_dataUpdate($id, $data, 'customer_source');
    }

    function exitportUpdate($id, $data) {
        return $this->_dataUpdate($id, $data, 'exitport_people_number');
    }

    function _dataUpdate($id, $data, $key) {
        return $this->update('countrywide_outbound')
            ->set($key.' = :d', [':d' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}