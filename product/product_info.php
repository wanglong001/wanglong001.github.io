<?php session_start();

$pd_image=$_GET['pd_image'];
$pd_price=$_GET['pd_price'];
$pd_name=$_GET['pd_name'];
$pd_id=$_GET['pd_id']
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <link href="../css/style.css" rel="stylesheet">
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>
<?php include "../header.php"; ?>
<div class="content center clearfix">
    <div class="pd_pic">
        <div class="pd_phone_img">
        <img  src="../images/<?=$pd_image?> ">
        </div>
    </div>
    <div class="pd_detail">
        <h3><?=$pd_name?></h3>
        <ul >
            <li>
                <span class="pd_price_p">price</span>
            </li>
            <li>
                <span class="pd_price_pn">￥ <?=$pd_price?></span>
            </li>
        </ul>
        <div class="pd_amount">
            <span >数量</span>
                  <div class="pd_text" >
                      <input id="num" type="text" value="1" title="请输入购买量"/>件
                  </div>
        </div>
        <div class="pd_buy">
            <a class="normal-btn" id="buy" >立即购买</a>
            <a class="normal-btn" href="">加入购物车</a>
        </div>
    </div>
</div>
<?php include "../footer.php"; ?>
<script>
    $(document).ready(function(){
        $('#buy').click(function(){
            var num=$('#num').val();
            $(this).attr("href","product_sumbit.php?id=<?=$pd_id?>&num="+num);
        })
    })
</script>
</body>
</html>