<?php

namespace App\Controllers;

use Sim\IController;
use Upload;
use BigData\Readxls;

class ManageTjTouristAttractions extends IController {
    use Readxls;
    /**
     * @var \App\Model\ManageTjTouristAttractions;
     */
    protected $model;

    function __construct($params) {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function insert() {
        $data = $this->model->getRow($_POST['year']);
        if(!empty($data))
            $this->error(2101, '插入失败,该记录已经存在');
        $data = $this->_attractionFile();
        $result = $this->invoke('ManageTjOutbound')->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');
        $rows = $this->getData($data['path'], 1);
        $title = array_shift($rows);
        foreach ($rows as $row) {
            $attraction_data[] = [
                $row[0] => [$title[1] => $row[1], $title[2] => $row[2]],
            ];
        }
        $attraction_data = json_encode($attraction_data, JSON_UNESCAPED_UNICODE);
        $data = [
            'year' => $_POST['year'],
            'data' => $attraction_data
        ];

        $result = $this->model->insertRow($data);
        if(!$result)
            $this->error(100, '插入记录失败');
        $this->success();
    }

    function attractionUpdate() {
        $data = $this->_attractionFile();
        $result = $this->invoke('ManageTjOutbound')->insertFile($data);
        if(!$result)
            $this->error(100, '保存文件失败');

        $rows = $this->getData($data['path'], 1);
        $title = array_shift($rows);
        foreach ($rows as $row) {
            $attraction_data[] = [
                $row[0] => [$title[1] => $row[1], $title[2] => $row[2]],
            ];
        }
        $attraction_data = json_encode($attraction_data, JSON_UNESCAPED_UNICODE);
        $result = $this->model->attractionUpdate(intval($_POST['year']), $attraction_data);
        if(!$result) $this->error();
        $this->success();
    }

    function _attractionFile() {
        $config = [
            'key' => 'attraction_data',
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