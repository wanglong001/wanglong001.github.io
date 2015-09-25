<?php
$err_message = "";

require_once "db/user.func.php";

if(isset($_POST['username'])){
    $usr=$_POST['username'];
    $pwd=$_POST['password'];
    $pwd2=$_POST['password2'];
    $email=$_POST['email'];
    $mobile=$_POST['mobile'];

    if($pwd==$pwd2){

        if(!checkUserExist($usr)){

            if(addUser($usr,$pwd,$email,$mobile)){
                header("location: index.php");
            }else{
                $err_message = "未能成功注册";
            }

        }else{
            $err_message = "已存在此用户";
        }

    }else{
        $err_message = "两次输入的密码不一致";
    }

}

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>注册</title>
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery-2.1.4.js"></script>
</head>
<body>
<?php include "header.php"; ?>

<div class="content center">
    <div class="form-box" style="width: 560px;margin: 60px auto">
        <div style="width: 280px;margin: 60px auto;position: relative">
            <form action="" method="post">
                <input type="text" name="username" placeholder="用户名" autocomplete="off" autofocus="autofocus" required="required"/>
                <span id="userexist" class="error text-center" style="display:none;position: absolute;left: 300px;top:1px;width: 35%">用户已存在</span>
                <input type="password" name="password" placeholder="密码" required="required"/>
                <input type="password" name="password2" placeholder="密码确认" required="required"/>
                <input type="email" name="email" placeholder="邮件"/>
                <input type="tel" name="mobile" placeholder="手机"/>
                <button type="submit">注册</button>
                <div style="position: absolute;top: -45px;left: 0" class="error text-center">
                    <?= $err_message ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

<script>
    $(document).ready(function(){
        $('input[name="username"]').blur(function(){
            $.getJSON("service/checkuser.php",
                {username:  this.value},

                function(json){
                    if(json.result){
                        $('#userexist').show();
                    }
                    else{
                        $('#userexist').hide();
                    }

                }

            );
        });
    });

</script>
</body>
</html>