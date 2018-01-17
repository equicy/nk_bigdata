<?php
namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageSupplySideFeatureAnalysis extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageSupplySideFeatureAnalysis;
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

    function supplySideInsert() {
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('FeatureAnalysis')->getSupplySide(intval($_POST['year']), intval($_POST['month']));
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_file('public_government');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $public_government[] = [
                'name' => $row[0],
                'value' => $row[1],
                //$row[0] => $row[1]
            ];
        }
        $data = $this->_file('public_business');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $public_business[] = [
                'name' => $row[0],
                'value' => $row[1],
                //$row[0] => $row[1]
            ];
        }
        $public_government = json_encode($public_government, JSON_UNESCAPED_UNICODE);
        $public_business = json_encode($public_business, JSON_UNESCAPED_UNICODE);
        $supply_side = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'public_government' => $public_government,
            'public_business' => $public_business,
        ];
        $result = $this->model->insertRow($supply_side);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function publicGovernmentUpdate() {
        $data = $this->_file('public_government');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $public_government[] = [
                'name' => $row[0],
                'value' => $row[1],
                //$row[0] => $row[1]
            ];
        }
        $public_government = json_encode($public_government, JSON_UNESCAPED_UNICODE);
        $result = $this->model->publicGovernmentUpdate(intval($_POST['id']), $public_government);
        if(!$result) $this->error();
        $this->success();
    }

    function publicBusinessUpdate() {
        $data = $this->_file('public_business');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $public_business[] = [
                'name' => $row[0],
                'value' => $row[1],
                //$row[0] => $row[1]
            ];
        }
        $public_business = json_encode($public_business, JSON_UNESCAPED_UNICODE);
        $result = $this->model->publicBusinessUpdate(intval($_POST['id']), $public_business);
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