
# SPEAKIN声纹云python SDK
我们所有的接口都是通过http通讯的，对于传输的数据内容我们都会用app secret或session secret进行加密和签名。对于每个使用我们声纹云接口的用户，我们都会提供一个app id和app secret。在安全层面我们做了两级权限，使用app secret可以进行用户信息操作和创建session.对于每个创建的session,都有不大于2个小时的生命周期，session使用session secret进行加密和签名，而且session会绑定到一个用户，所有操作只会影响到这个用户。对于app secret的使用，我们还可以进行服务器ip限制来加强安全。

[TOC]


---

## 怎样验证声纹(要在python3.X 以上的环境运行)
### 第一步 准备好要注册的用户名, 并设置用户 
（可以参考API文档 demo/user_api_set_demo.py）

### 第二步 准备用户的注册文件，并注册声纹
注意：
 1. 注册时，声纹文件的数量最好要大于5
 2. 一定要在同一个session下生成record id，不然会出现 **wrong id** 的错误
 3. 最后将所有的record id放到字典中，作为registger函数的参数，并执行该函数进行注册

### 第三步 准备用户的验证文件，并验证声纹
注意：
 1. 可以通过**sessionApi.verify(recordIdListObj)**的返回值，查看声纹音频的result、得分（score）、阈值（threshold_score）和动态阈值（dyanmic_cmp_score）
 2. 如果result 返回为true，则系统认为验证成功

## 如何批量验证文件
在 demo/sdk_test_voice 目录下有我们提供测试的文件，可以直接运行
``` 
source bin/activate
cd demo
python DEMO3.py 
#or + 路径参数
python DEMO3.py sdk_test_voice/
``` 
也可以自定义脚本，参考demo 目录下的DEMO3.py 文件
只需配置好下面的常量：
``` 
"""
    我的目录如下：
    sdk_test_voice
                |
                name1(文件夹以测试人的名称命名)
                    |
                    register
                            |
                            *.wav
                    |
                    verify
                            |
                            *.wav
                |
                name2(文件夹以测试人的名称命名)
                    |
                    register
                           |
                            *.wav
                    |
                    verify
                            |
                            *.wav
"""
# 大概在 12行
OUT_FILE_NAME = 'report.csv'  # 生成结果的文件 这个测试脚本只能生成csv文件
BASE_FILE_NAME = 'sdk_test_voice/' # 测试的目录
REGISTER_FILE_NAME = "/register/" # 注册的文件夹名称
VERIFY_FILE_NAME = "/verify/" # 验证的文件夹名称
```
**结果默认写在 demo/report.csv 中, 1 表示 true , 0 表示 false**

## 如何联系我们

如果有遇到问题，可以通过邮箱联系我们: wangzelong@speakin.mobi

