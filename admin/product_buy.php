<?php
session_start();

require_once "../function/function.php";

adminOnly();
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
<?php
require_once "../db/product.func.php";
$rows=orderlist();
?>
<div class="content center clearfix">

    <?php include "../admin/admin-menu.php" ?>
    <div id="data-list">
        <h3>订单</h3>
        <table>
            <tr>
                <th width="30%">订单id</th>
                <th width="30%">用户</th>
                <th width="30%">商品的id</th>
                <th width="30%">商品数量</th>
            </tr>
            <?php
            foreach($rows as $row)
            {
            ?>
            <tr>
                <td><?=$row['id']?></td>
                <td><?=$row['user']?></td>
                <td><?=$row['pd_id']?></td>
                <td><?=$row['num']?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>

</div>
<?php include "../footer.php"; ?>
</body>
</html>