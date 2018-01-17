<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageTouristFeatureAnalysis extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageTouristFeatureAnalysis;
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

    function touristInsert() {
        if($_POST['month'] < 1 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('FeatureAnalysis')->getTourist(intval($_POST['year']), intval($_POST['month']));
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_file('age');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $age[] = [
              'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
              'value' => $row[1],
            ];
        }
        $data = $this->_file('education');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $education[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $age = json_encode($age, JSON_UNESCAPED_UNICODE);
        $education = json_encode($education, JSON_UNESCAPED_UNICODE);
        $tourist = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'age' => $age,
            'education' => $education,
        ];
        $result = $this->model->insertRow($tourist);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function ageUpdate() {
        $data = $this->_file('age');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $age[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $age = json_encode($age, JSON_UNESCAPED_UNICODE);
        $result = $this->model->ageUpdate(intval($_POST['id']), $age);
        if(!$result) $this->error();
        $this->success();
    }

    function educationUpdate() {
        $data = $this->_file('education');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $education[] = [
                'name' => $row[0] . ' ' . number_format($row[1] * 100, 2) . '%',
                'value' => $row[1],
            ];
        }
        $education = json_encode($education, JSON_UNESCAPED_UNICODE);
        $result = $this->model->educationUpdate(intval($_POST['id']), $education);
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