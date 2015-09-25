<?php

require_once "dbconn.php";

function getProducts($offset=0,$rowcount=10){

    $db=getDb();
    $stmt=$db->prepare("select * from product limit ?,?");
    $stmt->bindParam(1,$offset,PDO::PARAM_INT);
    $stmt->bindParam(2,$rowcount,PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSuggestion($keyword){
    $db=getDb();
    $stmt=$db->prepare("select pd_name from product where pd_name like ? limit 10");
    $keyword='%'.$keyword.'%';
    $stmt->bindParam(1,$keyword);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function addorder($id,$pd_id,$num,$user){
    $db=getDb();
    $stmt=$db->prepare("insert into orders(id,pd_id,num,user) VALUES(?,?,?,?) ");
    $stmt->bindParam(1,$id);
    $stmt->bindParam(2,$pd_id);
    $stmt->bindParam(3,$num);
    $stmt->bindParam(4,$user);
    $stmt->execute();
    return ($stmt->rowCount()==1);
}
function orderlist($offset=0,$rowcount=10){
    $db=getDb();
    $stmt=$db->prepare('select * from orders limit ?,?');
    $stmt->bindParam(1,$offset,PDO::PARAM_INT);
    $stmt->bindParam(2,$rowcount,PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
