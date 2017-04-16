---
title: Markdown 实现粘贴图片（ahk）
date: 2017-04-16 16:44:45
type: "archives"
categories:
  - AutoHotKey
tags:
  - AutoHotKey
  - Markdown
---

## 闲谈
博主使用的Markdown编辑器是 **Cmd Markdown** 每次粘贴截图都是非常的麻烦：
> 将截图保存到本地 --> 上传到服务器 --> 引用链接

当然，也可以使用  **Cmd Markdown** 的服务器，不过要会员： 99/年。
作为学生的我现在是能省就省（毕竟又不是我自己的钱）

最近看到一篇文章，[简化markdown写作中的贴图流程][1]，不过是 **mac系统** 的。其中有提到一个关键词 [AutoHotKey][2] ，这是windows下使用快捷键的。

接下来的几天开始研究 **AutoHotKey**，发现这种脚本还是比较好玩的，不但是快捷键、桌面绘图和控件都有相关的库。

现在博主主要的问题还是在如果把剪切板的截图转换成图片，找了很久，还是没有找到相关的资料，本来是想实在找不到，就自己写一个截图工具（从根本上截图问题），那是还是太天真了。虽然到最后可以实现 截图，但是不想QQ截图那样，在截图的时候，桌面上多了一层图层，在截图的时候不会点击到图层下面的文件、内容的。博主的这个还是会有点击、和拖动事件的比较麻烦。

在偶然的情况下，发现了一个非常有用的函数 <code>Gdip_CreateBitmapFromClipboard()</code>,一下了解决了问题。这下可以直接从获取剪切板的截图了。

## 代码


仓库：[github地址][3]

> 32位系统用 Gdip.ahk
> 64为系统用 Gdip_All.ahk



```
![image](http://wanglong001.github.io/images/20170416_182250.png)
createPic(PicPath)
{
	pToken := Gdip_Startup() ; Start gdi+
        ; pBitmapAlpha := Gdip_CreateBitmapFromFile(PicPath)
        ;pBitmapAlpha := Gdip_BitmapFromScreen(0, "")
        ;pBitmapAlpha := Gdip_BitmapFromScreen(x "|" y "|" width "|" height)
        ;从剪切板直接获取位图
        pBitmapAlpha := Gdip_CreateBitmapFromClipboard()
        ;图片的宽度
        ImgWidth := Gdip_GetImageWidth(pBitmapAlpha)  ; 获取宽度，高度，可省略
        ;图片的高度
        ImgHeight := Gdip_GetImageHeight(pBitmapAlpha)
        ;保存图片到指定的位置
        Gdip_SaveBitmapToFile(pBitmapAlpha, PicPath,"255") ;第三个参数控制图片质量
        Gdip_DisposeImage(pBitmapAlpha)
        
	Gdip_Shutdown(pToken) ; close gdi+
	Traytip, 截图完毕:, 宽: %ImgWidth% 高: %ImgHeight%`n保存为: %PicPath%
}
```
```
;按ctrl + F2 发布图片
^F2::
  run,cmd /c cd /D %hexoPath% & hexo g & hexo d 
return

;按ctrl + F1 保存截图,并 输入 markdown 的图片格式
^F1::
    ; 保存图片的位置和格式
    FormatTime, currentFilenameFormat, A_Now, %filenameFormat%
    savePath := PicPath . currentFilenameFormat . "." . imageFormat
    saveHttpPath := httpPath . currentFilenameFormat . "." . imageFormat
   createPic(savePath)
   clipboard := "![image](" . saveHttpPath . ")" 
   send, ^v
   if  isPushWhenSave
       send, ^n
return

```

## 演示

 1. 操作
 
*前提：运行ClipMd.ahk*

 - Ctrl + F1 : 生成图片链接
 - Ctrl + F2 : 发布图片
 - Ctrl + F8 : 退出

![image](http://wanglong001.github.io/images/clipmd_demo.gif)




  [1]: http://www.jianshu.com/p/7bd4e6ed99be
  [2]: autohotkey.com
  [3]: https://github.com/wanglong001/ClipMd