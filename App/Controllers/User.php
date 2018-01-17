<?php

namespace App\Controllers;

use Sim\IController;
use BigData\Text;

class User extends IController {
    use Text;
    /**
     * @var \App\Model\User;
     */
    protected $model;

    function __construct($params)
    {
        $function = new \ReflectionClass(__CLASS__);
        parent::__construct($params, $function->getShortName());
    }

    function signIn() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = self::passHash($username, $password);
        $userinfo = $this->model->getLoginInfo($username, $password);
        if(empty($userinfo)) {
            $this->error(2001, '登录失败');
        }
        $_SESSION['id'] = $userinfo['id'];
        $_SESSION['username'] = $userinfo['username'];
        $_SESSION['power'] = $userinfo['power'];
        $this->success(0, '登录成功');
    }

    function signUp() {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $power = $_POST['power'];
        if(!preg_match('/^\w{6,16}$/', $username)) {
            $this->error(1002, '用户名只能包含字母或数字组成,且长度为6-16个字符');
        }

        if(strlen($password) < 6 || strlen($password) > 16) {
            $this->error(1003, '密码长度不符合规范(6-16位)');
        }

        if(!in_array($power, [10, 100])) {
            $this->error(1004, '权限设置错误');
        }

        $result = $this->model->userExist($username);
        if(!empty($result)) {
            $this->error(1005, '该用户已存在');
        }

        $password = self::passHash($username, $password);
        $result = $this->model->insertUser($username, $password, $power);
        if(!$result) {
            $this->error(1006, '添加用户失败');
        }
        $this->success(0, '添加成功');
    }
}