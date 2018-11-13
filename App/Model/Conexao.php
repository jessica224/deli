<?php

namespace App\Model;

define('HOST', 'mysql://mysql:3306/');
define('DBNAME', 'sampledb');
define('CHARSET', 'utf8');
define('USER', 'userUBG');
define('PASSWORD', 'BfyKqkap304yiT1g');


class Conexao {
    private static $instance;
    public static function getConnection() {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new \PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . "; charset=" . CHARSET . ";", USER, PASSWORD, array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',\PDO::ATTR_PERSISTENT => TRUE));
                self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(\PDO::ATTR_ORACLE_NULLS, \PDO::NULL_EMPTY_STRING);
                self::$instance->query("SET SESSION sql_mode = ''"); // permite retirar linhas duplicadas left outer join
            } catch (\PDOException $e) {
                print "erro getInstance() : " . $e->getMessage();
            }
        }
        return self::$instance;
    }
}
