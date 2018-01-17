<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageCountrywideOutbound extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageCountrywideOutbound;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function outboundInsert() {
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $type = intval($_POST['type'] == 1);
        $result = $this->invoke('CountrywideOutbound')->getRow($_POST['year'], $_POST['month'], $type);
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_outboundFile();
        $rows = $this->getData($data['path'], 1);
        $head = array_shift($rows);
        $outbound['head'] = $head;
        foreach ($rows as $row) {
            $outbound['data'][] = $row;
        }
        $outbound = json_encode($outbound, JSON_UNESCAPED_UNICODE);
        $outbound = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'data' => $outbound,
            'type' => $type
        ];
        $result = $this->model->insertRow($outbound);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function outboundUpdate() {
        $data = $this->_outBoundFile();
        $rows = $this->getData($data['path'], 1);
        $head = array_shift($rows);
        $outbound['head'] = $head;
        foreach ($rows as $row) {
            $outbound['data'][] = $row;
        }
        $outbound = json_encode($outbound, JSON_UNESCAPED_UNICODE);
        $result = $this->model->outboundUpdate(intval($_POST['id']), $outbound);
        if(!$result) $this->error();
        $this->success();
    }

    function getList() {
        $year = $this->param['year'];
        $data = $this->model->getList($year);
        $this->returnJson(0, $data);
    }

    function _outboundFile() {
        //print_r($_FILES);exit;
        $config = [
            'key' => 'outbound',
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