
# SPEAKIN声纹云python SDK

---


## 环境要求 
python版本：python3.X 以上
 
## 怎样开始
1.  需先进入sdk_py目录
2.  测试文件在demo/sdk_test_voice下，可以进行替换。demo/sdk_test_voice 目录 里面的 一级文件夹的名称就是你要测试的用户名称(如里面的"ll"文件夹， ll就是测试的用户名)， 具体如下方展示：
``` 

    我的目录如下：
    demo/sdk_test_voice
                    |
                    name1(文件夹以测试人的名称命名)
                        |
                        register(注册音频最好大于5个)
                                |
                                *.wav
                        |
                        verify
                                |
                                *.wav
                    |
                    ll(文件夹以测试人的名称命名)
                        |
                        register(注册音频最好大于5个)
                                |
                                *.wav
                        |
                        verify
                                |
                                *.wav


```

 3. 然后执行命令如下

``` 
source bin/activate
cd demo
python DEMO3.py 
``` 


 4. 当然你也可以换目录，格式如步骤2所示， 然后执行命令如下

``` 
source bin/activate
cd demo
python DEMO3.py 你的目录
``` 


## 注意事项
 1. 注册声纹文件的数量要大于5个
 2. 测试结果默认写在 demo/report.csv 中, 1 表示成功 , 0 表示失败
 3. 更换路径：
```
#参考demo 目录下的DEMO3.py 文件， 12行
OUT_FILE_NAME = 'report.csv'  # 生成结果的文件 这个测试脚本只能生成csv文件
BASE_FILE_NAME = 'sdk_test_voice/' # 测试的目录
REGISTER_FILE_NAME = "/register/" # 注册的文件夹名称
VERIFY_FILE_NAME = "/verify/" # 验证的文件夹名称
```
 4. 如果有其他应用对接方面的问题，请联系 wangzelong@speakin.mobi，谢谢！


