<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageTjOutbound extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageTjOutbound;
     */
    protected $model;

    function __construct($params)
    {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function outboundInsert() {
        global $_CONFIG;
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('TjOutbound')->getRow($_POST['year'], $_POST['month']);
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_outBoundFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');

        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $outbound[] = [
                $row[0] => $row[1]
            ];
        }
        $outbound = json_encode($outbound, JSON_UNESCAPED_UNICODE);
        $data = $this->_exitPortTeams();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $exitport[] = [
                $row[0] => $row[1]
            ];
        }
        //asort($exitport, SORT_NUMERIC);
        //$exitport = array_slice($exitport, 0, $_CONFIG['exitport_ranking_number']);
        $exitport = json_encode($exitport, JSON_UNESCAPED_UNICODE);
        $data = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'team_number' => $_POST['team_number'],
            'people_number' => $_POST['total_people'],
            'exitport_team_number' => $exitport,
            'outbound' => $outbound
        ];

        $result = $this->model->insertRow($data);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function outboundUpdate() {
        $data = $this->_outBoundFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');

        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $outbound[] = [
                $row[0] => $row[1]
            ];
        }
        $outbound = json_encode($outbound, JSON_UNESCAPED_UNICODE);
        $result = $this->model->outboundUpdate(intval($_POST['id']), $outbound);
        if(!$result) $this->error();
        $this->success();
    }

    function exitportUpdate() {
        global $_CONFIG;
        $data = $this->_exitPortTeams();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $exitport[] = [
                $row[0] => $row[1]
            ];
        }
        asort($exitport, SORT_NUMERIC);
        $exitport = array_slice($exitport, 0, $_CONFIG['exitport_ranking_number']);
        $exitport = json_encode($exitport, JSON_UNESCAPED_UNICODE);
        $result = $this->model->exitportUpdate(intval($_POST['id']), $exitport);
        if(!$result) $this->error();
        $this->success();
    }

    function totalTeamsUpdate() {
        $id = intval($_POST['id']);
        $data = intval($_POST['team_number']);
        if(empty($id) || empty($data)) {
            $this->error(2100, '请输入数据');
        }
        $result = $this->model->totalTeamsUpdate($id, $data);
        if(!$result) $this->error();
        $this->success();
    }

    function totalPeopleUpdate() {
        $id = intval($_POST['id']);
        $data = intval($_POST['total_people']);
        if(empty($id) || empty($data)) {
            $this->error(2100, '请输入数据');
        }
        $result = $this->model->totalPeopleUpdate($id, $data);
        if(!$result) $this->error();
        $this->success();
    }

    function getList() {
        $year = $this->param['year'];
        $data = $this->model->getList($year);
        $this->returnJson(0, $data);
    }

    protected function _validate($title) {

    }

    function _outBoundFile() {
        //print_r($_FILES);exit;
        $config = [
            'key' => 'outbound',
            'path' => __DIR__ . '/../../Public/upload/xls',
            'size' => '10M',
            'mimetype' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx', 'application/wps-office.xls']
        ];

        return $this->_uploadFile($config);
    }

    function _exitPortTeams() {
        $config = [
            'key' => 'exitport',
            'path' => __DIR__ . '/../../Public/upload/xls',
            'size' => '10M',
            'mimetype' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx', 'application/wps-office.xls']
        ];

        return $this->_uploadFile($config);
    }

    function _uploadFile(array $config) {

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