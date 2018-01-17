<?php

namespace App\Model;

use Sim\IModel;

class ManageTjInbound extends IModel{

    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('tj_inbound')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function insertRow($data) {
        return $data = $this->insert('tj_inbound', 'year, month, team_number, people_number, entryport_team_number, inbound')
            ->values("'$data[year]', '$data[month]',
                 '$data[team_number]', '$data[people_number]',
                 '$data[entryport_team_number]', '$data[inbound]'")
            ->exec();
    }

    function inboundUpdate($id, $data) {
        return $this->update('tj_inbound')
            ->set('inbound = :i', [':i' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function entryportUpdate($id, $data) {
        return $this->update('tj_inbound')
            ->set('entryport_team_number = :e', [':e' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalTeamsUpdate($id, $data) {
        return $this->update('tj_inbound')
            ->set('team_number = :t', [':t' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalPeopleUpdate($id, $data) {
        return $this->update('tj_inbound')
            ->set('people_number = :p', [':p' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }
}