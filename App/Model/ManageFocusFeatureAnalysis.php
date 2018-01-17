<?php

namespace App\Model;

use Sim\IModel;

class ManageFocusFeatureAnalysis extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('feature_focus')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertRow($data) {
        return $this->insert('feature_focus', 'year, month, destination, focus_type')
            ->values(':y, :m, :d, :f', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['destination'],
                ':f' => $data['focus_type']
            ])->exec();
    }

    function destinationUpdate($id, $data) {
        return $this->update('feature_focus')
            ->set('destination = :a', [':a' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }

    function focusTypeUpdate($id, $data) {
        return $this->update('feature_focus')
            ->set('focus_type = :f', [':f' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}