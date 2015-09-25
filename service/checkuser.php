<?php

header("Content-type: application/json");

require_once "../db/user.func.php";

$username=$_GET['username'];

if(checkUserExist($username)){
    echo '{ "result": true }';
}else{
    echo '{ "result": false }';
}