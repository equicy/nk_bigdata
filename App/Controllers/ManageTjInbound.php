<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageTjInbound extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageTjInbound;
     */
    protected $model;
    protected $id;

    function __construct($params)
    {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function inboundInsert() {
        global $_CONFIG;
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('TjInbound')->getRow($_POST['year'], $_POST['month']);
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_inboundFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');

        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $inbound[] = [
                $row[0] => $row[1]
            ];
        }
        $inbound = json_encode($inbound, JSON_UNESCAPED_UNICODE);
        $data = $this->_entryportTeams();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $entryport[] = [
                $row[0] => $row[1]
            ];
        }
        //asort($entryport, SORT_NUMERIC);
        //$entryport = array_slice($entryport, 0, $_CONFIG['ioport_ranking_number']);
        $entryport = json_encode($entryport, JSON_UNESCAPED_UNICODE);
        $data = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'team_number' => $_POST['team_number'],
            'people_number' => $_POST['total_people'],
            'entryport_team_number' => $entryport,
            'inbound' => $inbound
        ];

        $result = $this->model->insertRow($data);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function inboundUpdate() {
        $this->_validate();
        $data = $this->_inboundFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $inbound[] = [
                $row[0] => $row[1]
            ];
        }
        $inbound = json_encode($inbound, JSON_UNESCAPED_UNICODE);
        try {
            $this->model->inboundUpdate($this->id, $inbound);
        } catch(\PDOException $e) {
            $this->error(103, $e);
        }
        $this->success();
    }

    function entryportUpdate() {
        global $_CONFIG;
        $this->_validate();
        $data = $this->_entryportTeams();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $entryport[] = [
                $row[0] => $row[1]
            ];
        }
        asort($entryport, SORT_NUMERIC);
        $entryport = array_slice($entryport, 0, $_CONFIG['ioport_ranking_number']);
        $entryport = json_encode($entryport, JSON_UNESCAPED_UNICODE);
        try {
            $this->model->entryportUpdate($this->id, $entryport);
        } catch(\PDOException $e) {
            $this->error(103, $e);
        }
        $this->success();
    }

    function totalTeamsUpdate() {
        $this->_validate();
        $data = intval($_POST['team_number']);
        $result = $this->model->totalTeamsUpdate($this->id, $data);
        if(!$result) $this->error();
        $this->success();
    }

    function totalPeopleUpdate() {
        $this->_validate();
        $data = intval($_POST['total_people']);
        $result = $this->model->totalPeopleUpdate($this->id, $data);
        if(!$result) $this->error();
        $this->success();
    }

    function getList() {
        $year = $this->param['year'];
        $data = $this->model->getList($year);
        $this->returnJson(0, $data);
    }

    protected function _validate() {
        $this->id = intval($_POST['id']);
        if(!$this->id)
            $this->error(101, 'id错误');
    }

    function _inboundFile() {
        $config = [
            'key' => 'inbound',
            'path' => __DIR__ . '/../../Public/upload/xls',
            'size' => '10M',
            'mimetype' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx', 'application/wps-office.xls']
            ];

        return $this->_uploadFile($config);
    }

    function _entryportTeams() {
        $config = [
            'key' => 'entryport',
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