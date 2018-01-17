<?php

namespace App\Model;

use Sim\IModel;

class ManageSupplySideFeatureAnalysis extends IModel {
    function __construct() {
        parent::__construct();
    }

    function getList($year) {
        return $this->select('id, year, month, modify_time')
            ->from('feature_supply_side')
            ->where('year = :y', [':y' => $year])
            ->query();
    }

    function insertRow($data) {
        return $this->insert('feature_supply_side', 'year, month, public_government, public_business')
            ->values(':y, :m, :a, :e', [
                ':y' => $data['year'],
                ':m' => $data['month'],
                ':a' => $data['public_government'],
                ':e' => $data['public_business']
            ])->exec();
    }

    function publicGovernmentUpdate($id, $data) {
        return $this->update('feature_supply_side')
            ->set('public_government = :a', [':a' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }

    function publicBusinessUpdate($id, $data) {
        return $this->update('feature_supply_side')
            ->set('public_business = :e', [':e' => $data])
            ->where('id = :id', [':id' => $id])
            ->save();
    }
}