<?php

namespace Sim\Core;
use DB;

class Model {
    protected $sql;
    protected $param;

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
        $this->param = $param;
        return $this;
    }

    function from($table) {
        $this->sql .= 'FROM ' . $table . ' ';
        return $this;
    }

    function select(string $col = '*') {
        unset($this->sql);
        $this->sql .= 'SELECT ' . $col . ' ';
        return $this;
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