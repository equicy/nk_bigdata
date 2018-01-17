<?php

namespace App\Model;

use Sim\IModel;

class ManageTjOutbound extends IModel{

    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('tj_outbound')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertFile($data) {
        return $data = $this->insert('file', 'real_name, uniqid_name, path')
            ->values("'$data[real_name]', '$data[uniqid_name]', '/Public/upload/xls'")->exec();
    }

    function insertRow($data) {
        return $data = $this->insert('tj_outbound', 'year, month, team_number, people_number, exitport_team_number, outbound')
            ->values("'$data[year]', '$data[month]',
                 '$data[team_number]', '$data[people_number]',
                 '$data[exitport_team_number]', '$data[outbound]'")
            ->exec();
    }

    function outboundUpdate($id, $data) {
        return $this->update('tj_outbound')
            ->set('outbound = :o', [':o' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function exitportUpdate($id, $data) {
        return $this->update('tj_outbound')
            ->set('exitport_team_number = :e', [':e' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalTeamsUpdate($id, $data) {
        return $this->update('tj_outbound')
            ->set('team_number = :t', [':t' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }

    function totalPeopleUpdate($id, $data) {
        return $this->update('tj_outbound')
            ->set('people_number = :p', [':p' => $data])
            ->where('id = :id', ['id' => $id])
            ->save();
    }
}