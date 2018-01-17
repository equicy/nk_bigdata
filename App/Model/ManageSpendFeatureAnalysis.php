<?php

namespace App\Model;

use Sim\IModel;

class ManageSpendFeatureAnalysis extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('feature_spend')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertRow($data) {
        return $this->insert('feature_spend', 'year, month, family, asset, job')
            ->values(':y, :m, :f, :a, :j', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':f' => $data['family'],
                ':a' => $data['asset'],
                ':j' => $data['job'],
            ])->exec();
    }

    function familyUpdate($id, $data) {
        return $this->update('feature_spend')
            ->set('family = :a', [':a' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }

    function assetUpdate($id, $data) {
        return $this->update('feature_spend')
            ->set('asset = :e', [':e' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }

    function jobUpdate($id, $data) {
        return $this->update('feature_spend')
            ->set('job = :e', [':e' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}