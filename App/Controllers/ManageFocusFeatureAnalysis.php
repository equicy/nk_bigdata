<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageFocusFeatureAnalysis extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageFocusFeatureAnalysis;
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

    function focusInsert() {
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('FeatureAnalysis')->getFocus(intval($_POST['year']), intval($_POST['month']));
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_file('destination');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $focus[] = [
                'name' => $row[0] . ' ' . $row[1] * 100 . '%',
                'value' => $row[1],
            ];
        }
        $focus = json_encode($focus, JSON_UNESCAPED_UNICODE);
        $focus = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'destination' => $focus,
        ];
        $result = $this->model->insertRow($focus);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function destinationUpdate() {
        $data = $this->_file('destination');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $destination[] = [
                'name' => $row[0] . ' ' . $row[1] * 100 . '%',
                'value' => $row[1],
            ];
        }
        $destination = json_encode($destination, JSON_UNESCAPED_UNICODE);
        $result = $this->model->destinationUpdate(intval($_POST['id']), $destination);
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