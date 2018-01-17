<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageTjOtherProvinces extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageTjOtherProvinces;
     */
    protected $model;
    protected $id;

    function __construct($params)
    {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function insert() {
        global $_CONFIG;
        if($_POST['month'] < 0 || $_POST['month'] > 12) {
            $this->error(2002, '月份不符合规范');
        }
        $result = $this->invoke('TjOtherProvinces')->getRow($_POST['year'], $_POST['month']);
        if($result)
            $this->error(2001, '插入失败,该记录已经存在');
        $data = $this->_otherProFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');

        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $otherPro[] = [
                $row[0] => $row[1]
            ];
        }
        $otherPro = json_encode($otherPro, JSON_UNESCAPED_UNICODE);
        $data = $this->_ranking();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $ranking[] = [
                $row[0] => $row[1]
            ];
        }
        asort($ranking, SORT_NUMERIC);
        $ranking = array_slice($ranking, 0, $_CONFIG['tourist_attraction_ranking_number']);
        $ranking = json_encode($ranking, JSON_UNESCAPED_UNICODE);
        $data = [
            'year' => $_POST['year'],
            'month' => $_POST['month'],
            'team_number' => $_POST['team_number'],
            'people_number' => $_POST['total_people'],
            'ranking' => $ranking,
            'out_provinces' => $otherPro
        ];

        $result = $this->model->insertRow($data);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function otherProUpdate() {
        $this->_validate();
        $data = $this->_otherProFile();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $otherPro[] = [
                $row[0] => $row[1]
            ];
        }
        $otherPro = json_encode($otherPro, JSON_UNESCAPED_UNICODE);
        try {
            $this->model->otherProUpdate($this->id, $otherPro);
        } catch(\PDOException $e) {
            $this->error(103, $e);
        }
        $this->success();
    }

    function rankingUpdate() {
        global $_CONFIG;
        $this->_validate();
        $data = $this->_ranking();
        $result = $this->model->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path']);
        foreach ($rows as $row) {
            $ranking[] = [
                $row[0] => $row[1]
            ];
        }
        asort($ranking, SORT_NUMERIC);
        $ranking = array_slice($ranking, 0, $_CONFIG['tourist_attraction_ranking_number']);
        $ranking = json_encode($ranking, JSON_UNESCAPED_UNICODE);
        try {
            $this->model->rankingUpdate($this->id, $ranking);
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
        //echo json_encode($data);exit;
	$this->returnJson(0, $data);
    }

    protected function _validate() {
        $this->id = intval($_POST['id']);
        if(!$this->id)
            $this->error(101, 'id错误');
    }

    function _otherProFile() {
        $config = [
            'key' => 'other_provinces',
            'path' => __DIR__ . '/../../Public/upload/xls',
            'size' => '10M',
            'mimetype' => ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/wps-office.xlsx', 'application/wps-office.xls']
        ];

        return $this->_uploadFile($config);
    }

    function _ranking() {
        $config = [
            'key' => 'ranking',
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
