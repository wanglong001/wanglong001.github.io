<?php
session_start();

require_once "../function/function.php";
require_once "../db/user.func.php";

adminOnly();

if(isset($_POST['email'])){
    $id=$_POST['id'];
    $email=$_POST['email'];
    $mobile=$_POST['mobile'];

    if(updateUser($id,$email,$mobile)){
        header("location: user-list.php");
    }


}else{
    $id=(int)$_GET['id'];

    $row=getUserById($id);
}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理员页面</title>
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>
<?php include "../header.php"; ?>



<div class="content center clearfix">

    <?php include "admin-menu.php" ?>

    <div id="data-list">

        <h3>修改用户信息</h3>

        <form action="" method="post">

            <input type="hidden" name="id" value="<?=$row['id']?>"/>

            <table style="width:60%">

                <tr>
                    <td>用户名</td>
                    <td><?=$row['username']?></td>
                </tr>
                <tr>
                    <td>邮件</td>
                    <td><input type="text" name="email" value="<?=$row['email']?>"/></td>
                </tr>
                <tr>
                    <td>手机</td>
                    <td><input type="text" name="mobile" value="<?=$row['mobile']?>"/></td>
                </tr>
                <tr>
                    <td>用户组</td>
                    <td><?=$row['usergroupdesc']?></td>
                </tr>

            </table>

            <p>
                <button class="btn normal-btn" type="submit">提交修改</button>
                <a class="btn normal-btn" href="user-list.php">取消</a>
            </p>

        </form>

    </div>

</div>

<?php include "../footer.php"; ?>



</body>
</html>
