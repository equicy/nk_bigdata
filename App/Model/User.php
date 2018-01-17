<?php

namespace App\Model;

use Sim\IModel;

class User extends IModel {

    function __construct()
    {
        parent::__construct();
    }

    function userExist($username) {
        return $this->select('id')->from('user')
            ->where('username = :n', [':n' => $username])
            ->row();
    }

    function getLoginInfo($username, $password) {
        return $this->select('id, username, power')->from('user')
            ->where('username = :n', [':n' => $username])
            ->andWhere('password = :p', [':p' => $password])
            ->andWhere('static = :s', [':s' => 0])
            ->row();
    }

    function insertUser($username, $password, $power) {
        return $this->insert('user', 'username, password, power')
            ->values("'$username', '$password', '$power'")
            ->exec();
    }
}