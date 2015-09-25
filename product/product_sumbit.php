<?php
session_start();
if(!$_SESSION['username']) header("location:../login.php");
else
{
    $_SESSION['num']=$_GET['num'];
    $_SESSION['pd_id']=$_GET['id'];
    $_SESSION['time']=date("y-m-r h:m:s",time());
    $orderid=rand(100,999);
    $orderid="$_SESSION[time]$_SESSION[username]$orderid";
}


?>
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
    <div class="a_center">
        <form action="" method="post">
        <h3>订单</h3>
        <table>
            <tr>
            <th width="30%">订单id</th>
            <th width="30%">用户</th>
            <th width="30%">下单时间</th>
            </tr>
            <tr>
                <td><?=$orderid?></td>
                <td><?=$_SESSION['username']?></td>
                <td><?=$_SESSION['time']?></td>
            </tr>
        </table>
        <h3>订单详情</h3>
        <table>
            <tr>
                <th width="30%">订单id</th>
                <th width="30%">商品id</th>
                <th width="30%">数量</th>
            </tr>
            <tr>
                <td><?=$orderid?></td>
                <td><?=$_SESSION['pd_id']?></td>
                <td><?=$_SESSION['num']?></td>
            </tr>
        </table>
            <div class="pd_summit">
                <a class="normal-btn" style="float: right;display: inline-block" href="product_insert.php?id=<?=$orderid?>&pd_id=<?=$_SESSION['pd_id']?>&user=<?=$_SESSION['username']?>&num=<?=$_SESSION['num']?>"  >提交订单</a>
            </div>
</form>
</div>
</div>

<?php include "../footer.php"; ?>
</body>
</html>