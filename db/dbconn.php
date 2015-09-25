<?php

function getDb()
{
    $db_server = 'localhost';
    $db_port = '3306';
    $db_name = 'myshop';

    $db_user = 'root';
    $db_password = '';

    $dsn = "mysql:host=$db_server;port=$db_port;dbname=$db_name;charset=utf8";

    try {
        $db = new PDO($dsn, $db_user, $db_password, array(PDO::ATTR_PERSISTENT => true));

    } catch (PDOException $ex) {
        exit("不能连接数据库");
    }

    return $db;
}