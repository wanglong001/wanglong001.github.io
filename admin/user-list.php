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
    <script src="../js/jquery-2.1.4.js"></script>
</head>
<body>
<?php include "../header.php"; ?>

<?php require_once "../db/user.func.php"; ?>

<div class="content center clearfix">

    <?php include "admin-menu.php" ?>

    <div id="data-list">

        <h3>用户列表</h3>

        <form action="">

            <table style="width:100%">
                <tr>
                    <th style="width:5%"><input type="checkbox" id="all"/></th>
                    <th style="width:20%">用户名</th>
                    <th style="width:20%">邮件</th>
                    <th style="width:20%">手机</th>
                    <th style="width:15%">用户组</th>
                    <th style="width:10%"></th>
                    <th style="width:10%"></th>
                </tr>

                <?php
                $rows=getUsers();
                foreach($rows as $row){
                ?>

                <tr>
                    <td><input type="checkbox" name="id[]" value="<?=$row['id']?>"/></td>
                    <td><?=$row['username']?></td>
                    <td><?=$row['email']?></td>
                    <td><?=$row['mobile']?></td>
                    <td><?=$row['usergroupdesc']?></td>
                    <td class="text-center"><a class="normal-btn" href="user-edit.php?id=<?=$row['id']?>">修改</a></td>
                    <td class="text-center"><a class="alert-btn" href="user-delete.php?id=<?=$row['id']?>">删除</a></td>
                </tr>

                <?php } ?>

            </table>
            <p>
                <button class="btn alert-btn" type="submit">批量删除</button>

                <a class="btn normal-btn" href="user-add.php">添加用户</a>
            </p>
        </form>

    </div>

</div>

<?php include "../footer.php"; ?>

<script>
    $(document).ready(function(){

        $('#all').click(function(){
            if(this.checked){
                $(':checkbox[name]').prop('checked',true);
                $('tr:gt(0)').addClass('highlight');
            }else{
                $(':checkbox[name]').prop('checked',false);
                $('tr:gt(0)').removeClass('highlight');
            }
        });

        $(':checkbox[name]').click(function(){
            var isall =  $(':checkbox[name]').length==$(':checkbox[name]:checked').length;
            $('#all').prop('checked',isall);

            $(this).parents('tr').toggleClass('highlight');
        });

    });
</script>

</body>
</html>
