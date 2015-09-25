<?php
function adminOnly()
{
    // 如果未登录，或不是管理员，则跳转到登录页面
    if (!isset($_SESSION['usergroup']) ||
        $_SESSION['usergroup'] != 1
    ) {
        header("location: ../login.php?redir=".$_SERVER['SCRIPT_NAME']);
    }
}