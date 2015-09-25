<?php
session_start();

// 清除所有客户端Cookie
foreach ($_COOKIE as $key => $value)
    setcookie($key, "", time() - 600);

// 清除并撤销会话
session_unset();
session_destroy();

// 重定向
header("location: index.php");