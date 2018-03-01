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
        $result = $this->invoke('CountrywideOutbound')->getRow($_POST['year'], $_POST['month']);
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_file('customer_source');
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $customer[] = [
                $row[0] => $row[1]
            ];
        }
        $customer = json_encode($customer, JSON_UNESCAPED_UNICODE);
        $data = $this->_file('exitport');
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $exitport[] = [
                $row[0] => $row[1]
            ];
        }
        $exitport = json_encode($exitport, JSON_UNESCAPED_UNICODE);
        $data = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'exitport_people_number' => $exitport,
            'customer_source' => $customer
        ];

        $result = $this->model->insertRow($data);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function customerSourceUpdate() {
        $data = $this->_file('customer_source');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $customer[] = [
                $row[0] => $row[1]
            ];
        }
        $customer = json_encode($customer, JSON_UNESCAPED_UNICODE);
        $result = $this->model->customerSourceUpdate(intval($_POST['id']), $customer);
        if(!$result) $this->error();
        $this->success();
    }

    function exitportUpdate() {
        $data = $this->_file('exitport');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $exitport[] = [
                $row[0] => $row[1]
            ];
        }
        $exitport = json_encode($exitport, JSON_UNESCAPED_UNICODE);
        $result = $this->model->exitportUpdate(intval($_POST['id']), $exitport);
        if(!$result) $this->error();
        $this->success();
    }

    function getList() {
        $year = $this->param['year'];
        $data = $this->model->getList($year);
        $this->returnJson(0, $data);
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