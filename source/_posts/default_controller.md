---
title: CI框架 default_controller 如何设置为：'目录/Controller'
date: 2017-03-23 00:08:10
type: "archives"
categories:
  - CI
tags:
  - PHP
  - default_controller
---

## 闲谈

前几天，我的室友发现了一个问题：CI框架的Router.php文件的default_controller设置为application\controllers文件下的 **一级PHP文件名** 就可以，设置为 **目录/Controller名** 就 404，如目录结构：

```
----application
   |
   ----controllers
       |
       -----admin
       |    |
       ---------Welcome.php
       |
       ----Welcome.php
```

在application\config下的routes.php,配置如下
```php
$route['default_controller'] = 'welcome';          这样就可以
$route['default_controller'] = 'admin/welcome';    这样不可以
```
一开始以为是哪里配置错了，但没道理啊，主要的只有这个配置。我开始怀疑是CI版本的问题，到网上一查，果然有相同问题的道友，不过没有找到确切的解决方案，也许是应为安全性的问题，3.x 版本的都不能这样设置了。

## 正题

博主尝试着决解这个问题。**博主的CI版本为**：*3.1.4* 

看了源码才知道原来 $route['default_controller'] 的值是 '类名/方法名' 而不是 '路径/类名'

### 打开system\core目录下的Router.php， 大概在298行

```php
//将下面的代码注释掉
/**
if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
{
	$method = 'index';
}
**/
//并上面的代码后面加上 下面的代码
$index = strripos($this->default_controller, '/');  // 记录 符号‘/’的下标
if($index == false){
    $class = $this->default_controller; // 没有‘/’ 的可以直接赋值
}else{
	$this->directory = substr($this->default_controller, 0, $index + 1); //目录的字符串
	$class  = substr($this->default_controller, $index + 1);  //类的字符串
}
$method = $this->method;  //默认方法
```

如果是 '路径/类名' ，以上的配置就够了

### 添加Controller默认的方法名

当然可以设置默认的方法名 ， 一般是index，如果要改也是可以的
在application\config下的routes.php，添加
```php
$route['method'] = 'index';  //默认的方法名
```
在system\core目录下的Router.php，大概在176行，添加

```php
if (isset($route) && is_array($route))
	{
		isset($route['default_controller']) && $this->default_controller = $route['default_controller'];
		isset($route['translate_uri_dashes']) && $this->translate_uri_dashes = $route['translate_uri_dashes'];
		unset($route['default_controller'], $route['translate_uri_dashes']);
		$this->routes = $route;
		isset($route['method']) && $this->method = $route['method'];  // 添加这一句代码就可以了
	}
```

这样就可以了，希望对你有帮助。







