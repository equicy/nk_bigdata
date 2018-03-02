<?php

namespace App\Model;

use Sim\IModel;

class ManageProvinces extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('provinces')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function insertRow($data) {
        return $this->insert('provinces', 'year, month, reception_number')
            ->values(':y, :m, :d', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['reception_number'],
            ])->exec();
    }

    function receptionNumberUpdate($id, $data) {
        return $this->_dataUpdate($id, $data, 'reception_number');
    }

    function _dataUpdate($id, $data, $key) {
        return $this->update('provinces')
            ->set($key.' = :d', [':d' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}