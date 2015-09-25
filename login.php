<?php
session_start();

$err_message = "";
$usr="";

require_once "db/user.func.php";

// 如果是提交表单
if (isset($_POST['username'])) {

    $usr = $_POST['username'];
    $pwd = $_POST['password'];


//    if ($usr == "admin") {
//        $grp = "administrator";
//    } elseif ($usr == "billy") {
//        $grp = "normaluser";
//    }

    // 验证用户名和密码，此处未来会更改为数据库操作。验证成功则设置登录状态
//    if (($usr == "admin" && $pwd == "123") ||
//        ($usr == "billy" && $pwd == "456")
//    )

    if(validUser2($usr,$pwd))
    {
        $grp=getUserGroup($usr);

        $_SESSION['username'] = $usr;
        $_SESSION['usergroup'] = $grp;
        $_SESSION['logintype'] = "bypassword";

        // 检测自动登录是否打勾，如打勾则设置Cookie
        if (isset($_POST['autologin'])) {
            setcookie('username', $usr, strtotime("+1 week"));
            setcookie('usergroup', $grp, strtotime("+1 week"));
        }
        // 登录后重定向到指定页面，未指定则到首页
        if (isset($_GET['redir']))
            $redir = $_GET['redir'];
        else
            $redir = "index.php";

        header("location: $redir");
    } else {
        $err_message = "用户名或密码不正确";
    }

}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>登录</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<?php include "header.php"; ?>

<div class="content center">
    <div class="form-box" style="width: 400px;margin: 100px auto">
        <div style="width: 280px;margin: 60px auto;position: relative">
            <form action="" method="post">
                <input type="text" name="username" value="<?=$usr?>" placeholder="用户名" autocomplete="off" autofocus="autofocus" required="required"/>
                <input type="password" name="password" placeholder="密码" required="required"/>
                <input type="checkbox" name="autologin" id="autologin"/>
                <label for="autologin">自动登录</label>
                <button type="submit">登录</button>
                <div style="position: absolute;top: -45px;left: 0" class="error text-center">
                    <?= $err_message ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include "footer.php"; ?>
</body>
</html>