<?php

namespace Sim\Core;
use DB;

class Model {
    protected $sql;
    protected $param = [];

    function __construct() {

    }

    function join($table, $on, $type = 'INNER') {
        $this->sql .= $type . ' JOIN ' . $table . ' ON ' . $on . ' ';
        return $this;
    }

    function limit($limit, $page = null) {
        $this->sql .= 'LIMIT ' . $limit . ' ';
        if($page)
            $this->offset($page * $limit);
        return $this;
    }

    protected function offset($offset) {
        $this->sql .= 'OFFSET ' . $offset . ' ';
        return $this;
    }

    function order(string $col) {
        $this->sql .= 'ORDER BY ' . $col . ' ';
        return $this;
    }

    function where(string $where, array $param = null) {
        $this->sql .= 'WHERE ' . $where . ' ';
        if($param)
            $this->param = array_merge($param, $this->param);
        return $this;
    }

    function andWhere(string $where, array $param = null) {
        $this->sql .= 'AND (' . $where . ') ';
        if($param)
            $this->param = array_merge($param, $this->param);
        return $this;
    }

    function orWhere(string $where, array $param = null) {
        $this->sql .= 'OR (' . $where . ') ';
        if($param)
            $this->param = array_merge($param, $this->param);
        return $this;
    }

    function sqlAndWhere(string $where) {
        $this->sql .= 'AND (' . $where . ') ';
        return $this;
    }

    function sqlOrWhere(string $where) {
        $this->sql .= 'Or (' . $where . ') ';
        return $this;
    }

    function from($table) {
        $this->sql .= 'FROM ' . $table . ' ';
        return $this;
    }

    function select(string $col = '*') {
        $this->param = [];
        $this->sql = 'SELECT ' . $col . ' ';
        return $this;
    }

    function update($table) {
        $this->param = [];
        $this->sql = 'UPDATE ' . $table . ' ';
        return $this;
    }

    function set($col, array $param = null) {
        $this->sql .= 'SET ' . $col . ' ';
        if($param)
            $this->param = array_merge($param, $this->param);
        return $this;
    }

    function insert($table, $col) {
        $this->param = [];
        $this->sql = "INSERT INTO `$table` ($col) ";
        return $this;
    }

    function values($values, array $param = null) {
        $this->sql .= "VALUES($values) ";
        if($param)
            $this->param = array_merge($param, $this->param);
        return $this;
    }

    function exec() {
        DB::q($this->sql, $this->param);
        return DB::lastInsertId();
    }

    function save() {
        if($this->param)
            return DB::q($this->sql, $this->param);
        return DB::q($this->sql);
    }

    function group($group) {
        $this->sql .= 'GROUP BY ' . $group . ' ';
        return $this;
    }

    function query() {
        if($this->param)
            return DB::q($this->sql, $this->param)->fetchAll();
        return DB::q($this->sql)->fetchAll();
    }

    function row() {
        if($this->param)
            return DB::q($this->sql, $this->param)->fetch();
        return DB::q($this->sql)->fetch();
    }

    function column() {
        //return DB::q($this->sql);
        if($this->param)
            return DB::q($this->sql, $this->param)->fetchColumn();
        return DB::q($this->sql)->fetchColumn();
    }

    function sqlRow($sql, array $param = null) {
        if($param)
            return DB::q($sql, $param)->fetch();
        return DB::q($sql)->fetch();
    }

    function sqlAll($sql, array $param = null) {
        if($param)
            return DB::q($sql, $param)->fetchAll();
        return DB::q($sql)->fetchAll();
    }
}