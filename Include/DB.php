<?php

/**
 * JoiFramework - DB Component
 * By Zhyupe (zyp108421@gmail.com)
 *
 * All rights reserved.
 */

class DB
{
  static $db = null;

  public static function __callStatic($name, $arguments)
  {
    global $_DB;
    if (self::$db === null) {
      self::$db = new PDO('mysql:host=' . $_DB['host'] . ';dbname=' . $_DB['database'] . ';charset=utf8',
          $_DB['username'], $_DB['password']);
      if (!self::$db) {
        exit('No db connection.');
      }

      self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      self::$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    return call_user_func_array(array(self::$db, $name), $arguments);
  }

  public static function t($name)
  {
    global $_CONFIG;
    return $_CONFIG['db']['prefix'] . $name;
  }

  public static function q($sql, $param = null)
  {
    if (!$param) {
      return DB::query($sql);
    } else {
      $stmt = DB::prepare($sql);
      $stmt->execute($param);
      return $stmt;
    }
  }

  public static function insert($table, $param)
  {
    $arg = array();
    $col = $val = '';
    foreach ($param as $k => $v) {
      $key = ':' . $k;
      $col .= ',`' . $k . '`';
      $val .= ',' . $key;
      $arg[$key] = $v;
    }

    $col = substr($col, 1);
    $val = substr($val, 1);
    $table = DB::t($table);
    DB::q("INSERT INTO `{$table}` ({$col}) VALUES ({$val})", $arg);
    return DB::lastInsertId();
  }

  public static function update($table, $param, $id, $pkey = 'id')
  {
    $arg = array();
    $val = '';
    foreach ($param as $k => $v) {
      $key = ':' . $k;
      $val .= ',`' . $k . '`=' . $key;
      $arg[$key] = $v;
    }

    $arg[':' . $pkey] = $id;
    $val = substr($val, 1);
    $table = DB::t($table);
    DB::q("UPDATE {$table} SET {$val} WHERE {$pkey} = :$pkey", $arg);
    return $id;
  }
}
