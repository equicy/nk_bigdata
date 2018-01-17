<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageSpendFeatureAnalysis extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageSpendFeatureAnalysis;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function getList() {
        $year = $this->param['year'];
        $data = $this->model->getList($year);
        $this->returnJson(0, $data);
    }

    function spendInsert() {
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('FeatureAnalysis')->getSpend(intval($_POST['year']), intval($_POST['month']));
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_file('family');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $family[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $data = $this->_file('asset');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $asset[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $data = $this->_file('job');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $job[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $job = json_encode($job, JSON_UNESCAPED_UNICODE);
        $family = json_encode($family, JSON_UNESCAPED_UNICODE);
        $asset = json_encode($asset, JSON_UNESCAPED_UNICODE);
        $spend = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'family' => $family,
            'asset' => $asset,
            'job' => $job
        ];
        $result = $this->model->insertRow($spend);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function familyUpdate() {
        $data = $this->_file('family');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $family[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $family = json_encode($family, JSON_UNESCAPED_UNICODE);
        $result = $this->model->familyUpdate(intval($_POST['id']), $family);
        if(!$result) $this->error();
        $this->success();
    }

    function assetUpdate() {
        $data = $this->_file('asset');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $asset[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $asset = json_encode($asset, JSON_UNESCAPED_UNICODE);
        $result = $this->model->assetUpdate(intval($_POST['id']), $asset);
        if(!$result) $this->error();
        $this->success();
    }

    function jobUpdate() {
        $data = $this->_file('job');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $job[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $job = json_encode($job, JSON_UNESCAPED_UNICODE);
        $result = $this->model->assetUpdate(intval($_POST['id']), $job);
        if(!$result) $this->error();
        $this->success();
    }

    function _file($key) {
        $config = [
            'key' => $key,
            'path' => __DIR__ . '/../../Public/upload/xls',
            'size' => '10M',
            'mimetype' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx', 'application/wps-office.xls']
        ];

        return $this->_uploadFile($config);
    }

    function _uploadFile(array $config) {
        //$this->_validate();
        $upload = new Upload($config);
        try {
            $upload->upload();
        } catch (\Exception $e) {
            $this->error(100, $upload->getErrors());
        }
        $data = $upload->getFileInfo();
        $fileinfo = [
            'real_name' => $_FILES[$config['key']]['name'],
            'uniqid_name' => $data['name'],
            'path' => $config['path'] .'/'. $data['name']
        ];
        return $fileinfo;
    }
}