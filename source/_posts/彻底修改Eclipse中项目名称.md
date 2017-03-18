---
title: 彻底修改Eclipse中项目名称
date: 2017-03-19 01:43:13
type: "archives"
categories:
  - Eclipse
tags:
  - eclipse
  - maven
  - 修改
  - 项目名
---



### 一、
1. #### 比较简单的方法： 
      右键工程： Refactor->Rename, 或者按F2，修改名称
      
2. #### 手动更改目录配置：
-      找到项目文件夹，修改项目根目录的名称
-      打开.setting文件夹，找到org.eclipse.wst.common.component 文件，如下图，修改成你想要的项目名称
``` bash
 <?xml version="1.0" encoding="UTF-8"?><project-modules id="moduleCoreId" project-version="1.5.0">
    <wb-module deploy-name="项目名">
        <wb-resource deploy-path="/WEB-INF/classes" source-path="/src/main/java"/>
        <wb-resource deploy-path="/" source-path="/target/m2e-wtp/web-resources"/>
        <wb-resource deploy-path="/" source-path="/src/main/webapp" tag="defaultRootSource"/>
        <wb-resource deploy-path="/WEB-INF/classes" source-path="/src/main/resources"/>
        <property name="java-output-path" value="/项目名/target/classes"/>
        <property name="项目名" value="collector"/>
    </wb-module>
</project-modules>
```
*deploy-name：配置到web server时显示的组件名称*
*java-output-path：类编译文件的输出位置*
*context-root：部署目录*

### 二、【如果你是手动更改目录，可以忽略次步骤】
- 项目右键--> Properties --> Web Project Settings --> Context root

### 三、修改项目目录下.project 文件
``` bash
<?xml version="1.0" encoding="UTF-8"?>
<projectDescription>
	<name>项目名</name>
	<comment></comment>
	.....
<projectDescription>
```
**一般的项目到这里就完成了**
### 四、maven项目：更改pom.xml配置【不是maven项目请忽略】
打开 pom.xml 文件，修改以下三项配置，xxx 就是原项目名

``` bash
<artifactId>项目名</artifactId>
<name>项目名 Maven Webapp</name>
<finalName>项目名</finalName>
```
