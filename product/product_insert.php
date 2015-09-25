<?php
session_start();
require_once "../db/product.func.php";
$id=$_GET['id'];
$pd_id=$_GET['pd_id'];
$user=$_GET['user'];
$num=$_GET['num'];

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



<div class="content center clearfix" style="height: 400px">
    <?php
    if(addorder($id,$pd_id,$num,$user)) echo "提交成功";
    else echo "提交失败";
    ?>

</div>
<?php
header("location:../admin/product_buy.php");
?>
<?php include "../footer.php"; ?>
</body>
</html>