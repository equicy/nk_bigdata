<?php

namespace Sim;

use Sim\Core\Controller;

class IController extends Controller {
    protected $param = [];
    protected $model;
    protected $config = [];

    function __construct($params, $class_name) {
        global $_CONFIG;
        $this->setParam($params);
        $this->config = $_CONFIG;
        $model = MODEL_NAMESPACE . $class_name;
        if(class_exists($model))
            $this->model = new $model();
    }

    protected function setParam($parmas) {
        foreach($parmas as $key => $value)
            $this->param[$key] = htmlspecialchars($value);
    }

    public function invoke($class_name) {
        $model = MODEL_NAMESPACE . $class_name;
        if(class_exists($model))
            return new $model();
        return null;
    }

    public function returnJson($status, $data) {
        $return = [
            'err' => $status,
            'data' => $data
        ];

        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }

    public function error($status = 100, $data = 'Illegal request') {
        $return = [
            'err' => $status,
            'data' => $data
        ];

        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }

    public function success($status = 0, $data = 'The request was successful') {
        $return = [
            'err' => $status,
            'data' => $data
        ];

        exit(json_encode($return, JSON_UNESCAPED_UNICODE));
    }
}