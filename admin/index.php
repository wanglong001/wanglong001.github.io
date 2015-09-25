<?php
session_start();

require_once "../function/function.php";

adminOnly();

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>管理员页面</title>
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<?php include "../header.php"; ?>

<div class="content center clearfix">

    <?php include "admin-menu.php" ?>



</div>

<?php include "../footer.php"; ?>
</body>
</html>
