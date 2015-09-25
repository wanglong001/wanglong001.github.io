<?php

require_once "dbconn.php";

function validUser($username,$password){

    $db=getDb();
    $stmt=$db->prepare("select count(*) from user where username=:username and password=:password");
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':password',$password);
    $stmt->execute();

    return ($stmt->fetchColumn()==1);
}

function validUser2($username,$password){

    $db=getDb();
    $stmt=$db->prepare("select password from user where username=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    $pwdhash=$stmt->fetchColumn();

    return (password_verify($password,$pwdhash));
}

function getUserGroup($username){

    $db=getDb();
    $stmt=$db->prepare("select usergroupid from user where username=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    return $stmt->fetchColumn();
}

function checkUserExist($username){

    $db=getDb();
    $stmt=$db->prepare("select count(*) from user where username=:username");
    $stmt->bindParam(':username',$username);
    $stmt->execute();

    return ($stmt->fetchColumn()==1);
}

function addUser($username, $password, $email, $mobile){

    $pwdhash=password_hash($password,PASSWORD_DEFAULT);

    $db=getDb();
    $stmt=$db->prepare("insert into user(username, password, email, mobile,usergroupid) values (?,?,?,?,?)");
    $stmt->bindParam(1,$username);
    $stmt->bindParam(2,$pwdhash);
    $stmt->bindParam(3,$email);
    $stmt->bindParam(4,$mobile);
    $stmt->bindValue(5,2);

    $stmt->execute();

    return ($stmt->rowCount()==1);
}

function getUsers($offset=0,$rowcount=10){

    $db=getDb();
    $sql="select user.id,username,email,mobile,
                 usergroup.usergroupdesc
          from user,usergroup
          where user.usergroupid=usergroup.id
          limit ?,?";
    $stmt=$db->prepare($sql);
    $stmt->bindParam(1,$offset,PDO::PARAM_INT);
    $stmt->bindParam(2,$rowcount,PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserById($id){
    $db=getDb();
    $sql="select user.id,username,email,mobile,
                 usergroup.usergroupdesc
          from user,usergroup
          where user.usergroupid=usergroup.id
                and user.id=?";
    $stmt=$db->prepare($sql);
    $stmt->bindParam(1,$id,PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateUser($id,$email,$mobile){
    $db=getDb();
    $stmt=$db->prepare("update user set email=?,mobile=? where id=?");
    $stmt->bindParam(1,$email);
    $stmt->bindParam(2,$mobile);
    $stmt->bindParam(3,$id,PDO::PARAM_INT);
    $stmt->execute();

    return ($stmt->rowCount()==1);
}