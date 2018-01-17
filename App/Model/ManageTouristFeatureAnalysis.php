<?php

namespace App\Model;

use Sim\IModel;

class ManageTouristFeatureAnalysis extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('feature_tourist')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertRow($data) {
        return $this->insert('feature_tourist', 'year, month, age, education')
            ->values(':y, :m, :a, :e', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':a' => $data['age'],
                ':e' => $data['education']
            ])->exec();
    }

    function ageUpdate($id, $data) {
        return $this->update('feature_tourist')
            ->set('age = :a', [':a' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }

    function educationUpdate($id, $data) {
        return $this->update('feature_tourist')
            ->set('education = :e', [':e' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}