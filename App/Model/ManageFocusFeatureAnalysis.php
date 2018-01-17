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
        return $this->insert('feature_focus', 'year, month, destination')
            ->values(':y, :m, :d', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':d' => $data['destination']
            ])->exec();
    }

    function destinationUpdate($id, $data) {
        return $this->update('feature_focus')
            ->set('destination = :a', [':a' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}