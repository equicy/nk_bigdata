<?php

namespace App\Model;

use Sim\IModel;

class ManageTjOtherProvinces extends IModel {

    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('tj_other_provinces')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function insertRow($data) {
        return $data = $this->insert('tj_other_provinces', 'year, month, team_number, people_number, ranking, out_provinces')
            ->values("'$data[year]', '$data[month]',
                 '$data[team_number]', '$data[people_number]',
                 '$data[ranking]', '$data[out_provinces]'")
            ->exec();
    }

    function otherProUpdate($id, $data) {
        return $this->update('tj_other_provinces')
            ->set('out_provinces = :o', [':o' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function rankingUpdate($id, $data) {
        return $this->update('tj_other_provinces')
            ->set('ranking = :r', [':r' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalTeamsUpdate($id, $data) {
        return $this->update('tj_other_provinces')
            ->set('team_number = :t', [':t' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalPeopleUpdate($id, $data) {
        return $this->update('tj_other_provinces')
            ->set('people_number = :p', [':p' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

}