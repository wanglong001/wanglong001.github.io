
# SPEAKIN声纹云python SDK
我们所有的接口都是通过http通讯的，对于传输的数据内容我们都会用app secret或session secret进行加密和签名。对于每个使用我们声纹云接口的用户，我们都会提供一个app id和app secret。在安全层面我们做了两级权限，使用app secret可以进行用户信息操作和创建session.对于每个创建的session,都有不大于2个小时的生命周期，session使用session secret进行加密和签名，而且session会绑定到一个用户，所有操作只会影响到这个用户。对于app secret的使用，我们还可以进行服务器ip限制来加强安全。

[TOC]


---

## SDK接口使用列表
### 全局接口
全局接口是整个sdk的入口，通过全局接口可以用户用户接口和会话接口。对于每个sdk用户我们都会分配一
个app id和app secret.
创建全局接口实例需要如下参数：

| 参数名 | 说明 |
|-------|---- |
| appId | 我们分配的app id|
| appSecret | 我们分配的app secret,需要妥善保管|
| baseUrl | 声纹云的http地址，公有云的是http://api2.speakin.mobi|

``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
```

#### 列出可用的声纹模型
我们对于不同场景(语音时常，环境噪音，数据或自由文本，采样率等)提供了不同的算法模型。根据我们的
用户的需求，我们会给每个appId分配一个或多个算法模型。通过这个接口就可以查看有哪些可用模型。

 - 请求参数
``` python
class ListModuleRequestSchema(Schema):
    pass
```
  - 响应参数
``` python
class ModuleSchema(Schema):
    voice_bit_count = fields.Int(required=True)
    voice_rate = fields.Int(required=True)
    voice_lang = fields.Str(required=True)

class ListModuleResponeSchema(Schema):
    module_list = fields.List(fields.Nested(ModuleSchema),required=True)
``` 

  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
res = api.listModule({})
print(res)
``` 

#### 创建会话
为了安全对于某些操作，会生成会话绑定到某个用户上。从而保证app secret不被泄漏。创建的会话是有生
命周期的，可以通过参数ttl来调整生命周期长短。
在创建会话的时候，需要指定会话的权限，否在调用对应接口的时候会被拒绝。

|参数| 说明|
| --- | --- |
| userId | 绑定的用户Id |
| canRegister |是否可以调用注册接口 |
| canVerify | 是否可以调用验证接口 |
| canIdentity |是否可以调用认证接口 |
| ttl | 会话生存时间，单位秒 |


  - 请求参数
``` python
class StartSessionRequestSchema(Schema):
    user_id = fields.Str(required=True,default="")
    can_register = fields.Bool(default=False)
    can_verify = fields.Bool(default=False)
    can_identity = fields.Bool(default=False)
    ttl = fields.Int(default=500)
```

  - 响应参数
``` python
class StartSessionResponseSchema(Schema):
    session_id = fields.Str(required=True)
    expire_time_stamp = fields.Int(required=True)
    session_secret = fields.Str(required=True)
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
from speakin_voice_sdk.session_api import SessionApi
api = Api("your app id","your app secret","your server url")
api.getUserApi().setAppUser({"user_id": "xx", "user_type": "PEOPLE", "valid": True})
sessionInfo = api.startSession({
    "user_id": "xx",
    "can_register": True,
})
print(sessionInfo)
sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "http
://api2.speakin.mobi")
recordInfo = sessionApi.startRecord({
    "voice_bit_count": 16,
    "voice_rate": 16000,
    "voice_lang": "common-short",
    "data_format": "WAV",
    "target_action": "REGISTER",
})
print(recordInfo)
```
#### 获取用户管理接口
用户管理也是使用app seceret作为加密和签名，为了模块更清晰就独立出来一个模块。

   - 请求参数
``` python
class StartSessionRequestSchema(Schema):
    user_id = fields.Str(required=True,default="")
    can_register = fields.Bool(default=False)
    can_verify = fields.Bool(default=False)
    can_identity = fields.Bool(default=False)
    ttl = fields.Int(default=500)
```
   - 响应参数
``` python
class StartSessionResponseSchema(Schema):
    session_id = fields.Str(required=True)
    expire_time_stamp = fields.Int(required=True)
    session_secret = fields.Str(required=True)
```
   - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
# 获取用户管理接
userApi = api.getUserApi()
print(userApi.setAppUser({"user_id":"xx","user_type":"PEOPLE","valid":True}))
```
### 用户管理接口

  - 用户属性
  
| 属性名 | 说明 |
| ------ | ----|
|userId |用户ID，需要保证每个唯一用户在app范围内唯一
|userType |用户类型，目前有三种:DEV,PEOPLE,VIRTUAL
|valid |是否有效，无效用户调用接口会被拒绝
|accessAllAppUser |是否可以访问所有用户的声纹，在声纹认证中有意义

  - 父用户和子用户的概念
父用户和子用户都是app内的用户，任何用户都可以建立父子关系。比如A，B两个用户，A可以是B的父用
户，也可以是B的子用户。对于B也是一样。
父子用户只是一种关联关系，并不是从属关系。父用户有权限访问子用户的声纹，只在声纹认证中有意
义。
对于一个用户进行认证时，如果设置了accessAllAppUser就可以比对整个app中所有用户声纹，否则的话就
只比对自己和子用户的声纹。
#### 设置用户
  - 请求参数
``` python
class SetAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    user_type = fields.Str(required=True)
    valid = fields.Bool(default=False)
    access_all_app_user = fields.Bool(default=False)
```

  - 响应参数
``` python
class SetAppUserResponseSchema(Schema):
    pass
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
# 获取用户管理接
userApi = api.getUserApi()
# 设置用户
userApi.setAppUser({"user_id":"xx","user_type":"PEOPLE","valid":True})
```
#### 获取用户
  - 请求参数
``` python
class GetAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
```
  - 响应参数
``` python
class GetAppUserResponseSchema(Schema):
    user_id = fields.Str(required=True)
    user_type = fields.Str(required=True)
    valid = fields.Bool(default=False)
    access_all_app_user = fields.Bool(default=False)
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
user = userApi.getAppUser({"user_id":"xx"})
print(user)
```
#### 增加子用户
  - 请求参数
``` python
class AddChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
  - 响应参数
``` python
class AddChildAppUserResponseSchema(Schema):
    pass
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
userApi.addChildAppUser({"user_id":"xx","child_user_id":"xxx"})
```
#### 删除子用户
  - 请求参数
``` python
class RemoveChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
  - 响应参数
``` python
class RemoveChildAppUserResponseSchema(Schema):
    pass
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
userApi.removeChildAppUser({"user_id":"xx","child_user_id":"xxx"})
```
#### 获取子用户数量
  - 请求参数
``` python
class GetChildAppUserCountRequestSchema(Schema):
    user_id = fields.Str(required=True)
```
  - 响应参数
``` python
class GetChildAppUserCountResponseSchema(Schema):
    count = fields.Int(required=True)
```
  - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
num = userApi.getChildAppUserCount({"user_id":"xx"})
print(num)
```
#### 列出子用户
 - 请求参数
``` python
class ListChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    offset = fields.Int(required=True)
    limit = fields.Int(required=True)
```
 - 响应参数
``` python
class ListChildAppUserResponseSchema(Schema):
    child_user_id_list = fields.List(fields.Str(), required=True)
```
 - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
users = userApi.listChildAppUser({"user_id":"xx"})
print(users)
```
#### 检查是否包含子用户
 - 请求参数
``` python
class ContainChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
 - 响应参数
``` python
class ContainChildAppUserResponseSchema(Schema):
    contain = fields.Bool(required=True)
```
 - demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
userApi = api.getUserApi()
isContain = userApi.containChildAppUser({"user_id":"xx","child_user_id":"xxx"})
print(isContain)
```
### 会话接口
会话接口主要包含上传文件，注册，验证和认证接口。
在文件上传的使用需要指定文件的用途，所有对文件的检查都是在上传过程中做的。在注册，验证和认证
接口中，只要指定使用的语音ID就可以了。
#### 文件上传接口
文件上传支持流式上传，sdk的使用者可以边获取数据边上传。每个会话同时只支持一个上传流，要开启一
个新的上传流的时候，必须结束上一个上传流或者取消上一个上传流。分片上传数据的时候，数据片段编
号是从1开始，不是从0开始。

 - 开始上传
##### 服务端检查逻辑
  1. 检查targetAction的权限
  2. 检查数据格式是否支持
  3. 检查上一个上传流是否存在
  4. 检查算法模型参数是否正确

请求参数
``` python
class StartRecordRequestSchema(Schema):
    gen_text = fields.Bool(default=False)
    voice_bit_count = fields.Int(required=True)
    voice_rate = fields.Int(required=True)
    voice_lang = fields.Str(required=True)
    data_format = fields.Str(required=True)
    target_action = fields.Str(required=True)
```
响应参数
``` python
class StartRecordResponseSchema(Schema):
    record_id = fields.Str(required=True)
    text = fields.Str(required=True)
```
demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
from speakin_voice_sdk.session_api import SessionApi
api = Api("your app id","your app secret","your server url")
api.getUserApi().setAppUser({"user_id": "xx", "user_type": "PEOPLE", "valid": True})
sessionInfo = api.startSession({
    "user_id": "xx",
    "can_register": True,
})
sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "your
server url")
    if sessionInfo != None :
        sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "
        your server url")
        recordInfo = sessionApi.startRecord({
            "voice_bit_count": 16,
            "voice_rate": 16000,
            "voice_lang": "common-short",
            "data_format": "WAV",
            "target_action": "REGISTER",
        })
        if recordInfo != None :
            rs = sessionApi.openUploadRecordStream(recordInfo['record_id'])
            with open('your file', 'rb') as f :
             rs.write(f.read())
             print("文件正在上传中...")
            rs.done()
            print("上传文件成功...")
        else :
             print("recordInfo 获取失败...")
else :
    print("sessonInfo 获取失败...")
```
 - 上传文件分片
从开始上传文件demo中获取的RecordStream对象上调用write方法就可以了。
 - 取消上传
从开始上传demo中获取的RecordStream对象上调用cancel方法就可以了。
 - 结束上传
从开始上传demo中的RecordStream对象上调用done方法。
##### 服务端逻辑
1. 检查是否处在一个上传流中
2. 检查语音数据是否合法
3. 合并文件片段并保存到文件服务器中
4. 保存语音meta信息
5. 清除上传流状态
#### 声纹注册
请求参数
``` python
class RegisterRequestSchema(Schema):
    record_id_list = fields.List(fields.Str(),required=True)
```
响应参数
``` python
class RegisterResponseSchema(Schema):
    pass
```
demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
from speakin_voice_sdk.session_api import SessionApi
api = Api("your app id","your app secret","your server url")
api.getUserApi().setAppUser({"user_id": "xx", "user_type": "PEOPLE", "valid": True})
sessionInfo = api.startSession({
    "user_id": "xx",
    "can_register": True,
})
print(sessionInfo)
sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "your
server url")
if sessionInfo != None :
    sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "
    your server url")
    recordInfo = sessionApi.startRecord({
        "voice_bit_count": 16,
        "voice_rate": 16000,
        "voice_lang": "common-short",
        "data_format": "WAV",
        "target_action": "REGISTER",
    })
    if recordInfo != None :
        rs = sessionApi.openUploadRecordStream(recordInfo['record_id'])
        # 上传⽂件 测试⽤demo⽬录下的 501-1_5.wav ⾳频⽂件
        with open('501-1_5.wav', 'rb') as f :
        rs.write(f.read())
        print("文件正在上传中...")
        rs.done()
        print("上传文件件成功...")
        recordIdList = []
        recordIdList.append(recordInfo['record_id'])
        recordIdListReq = {}
        recordIdListReq['record_id_list'] = recordIdList
        # 声纹注册
        sessionApi.register(recordIdListReq)
        print("声纹注册成功...")
    else :
        print("recordInfo 获取失败...")
else :
    print("sessonInfo 获取失败...")
```
#### 声纹验证
请求参数
``` python
class VerifyRequestSchema(Schema):
    record_id = fields.Str(required=True)
```
响应参数
``` python
class VerifyResponseSchema(Schema):
    result = fields.Bool(required=True)
```
demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
from speakin_voice_sdk.session_api import SessionApi
api = Api("your app id","your app secret","your server url")
api.getUserApi().setAppUser({"user_id": "xx", "user_type": "PEOPLE", "valid": True})
sessionInfo = api.startSession({
    "user_id": "xx",
    "can_register": True,
})
print(sessionInfo)
sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "your
server url")
if sessionInfo != None :
    sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "
    your server url")
    recordInfo = sessionApi.startRecord({
        "voice_bit_count": 16,
        "voice_rate": 16000,
        "voice_lang": "common-short",
        "data_format": "WAV",
        "target_action": "REGISTER",
    })
    if recordInfo != None :
        rs = sessionApi.openUploadRecordStream(recordInfo['record_id'])
        # 上传⽂件 测试用demo目录下的 501-1_5.wav 音频文件
        with open('501-1_5.wav', 'rb') as f :
        rs.write(f.read())
        print("文件正在上传中...")
        rs.done()
        print("上传文件成功...")
        recordIdListObj = {}
        recordIdListObj['record_id'] = recordInfo['record_id']
        # 声纹验证
        isVerify = sessionApi.verify(recordIdListObj)
        if isVerify :
            print("声纹验证成功...")
        else :
            print("声纹验证失败...")
    else :
     print("recordInfo 获取失败...")
else :
    print("sessonInfo 获取失败...")
```
#### 声纹认证
认证会进行多个用户的声纹对比。如果用户可以访问所有用户的声纹，那就是全局对比。对于普通用户，
只会对比自己以及子用户的声纹。

请求参数
``` python
class IdentityRequestSchema(Schema):
    record_id = fields.Str(required=True)
```
响应参数
``` python
class IdentityResponseSchema(Schema):
    user_id_list = fields.List(fields.Str(),required=True)
```
demo
``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
from speakin_voice_sdk.session_api import SessionApi
api = Api("your app id","your app secret","your server url")
api.getUserApi().setAppUser({"user_id": "xx", "user_type": "PEOPLE", "valid": True})
sessionInfo = api.startSession({
"user_id": "xx",
"can_register": True,
})
print(sessionInfo)
sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "your
server url")
if sessionInfo != None :
    sessionApi = SessionApi(sessionInfo["session_id"], sessionInfo["session_secret"], "
    your server url")
    recordInfo = sessionApi.startRecord({
        "voice_bit_count": 16,
        "voice_rate": 16000,
        "voice_lang": "common-short",
        "data_format": "WAV",
        "target_action": "REGISTER",
    })
    if recordInfo != None :
        rs = sessionApi.openUploadRecordStream(recordInfo['record_id'])
        # 上传⽂件 测试用demo目录下的 501-1_5.wav 音频文件
        with open('501-1_5.wav', 'rb') as f :
        rs.write(f.read())
        print("文件正在上传中...")
        rs.done()
        print("上传文件成功...")
        recordIdListObj = {}
        recordIdListObj['record_id'] = recordInfo['record_id']
        # 声纹认证
        userIdList = sessionApi.identity(recordIdListObj)
        if userIdList != None :
            print("声纹认证成功...")
            print(userIdList)
        else :
            print("声纹认证失败...")
    else :
        print("recordInfo 获取失败...")
else :
    print("sessonInfo 获取失败...")
```

---

## 怎样测试音频？
可以参考demo 目录下的DEMO3.py 文件
只需配置好下面的常量：
``` python
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

OUT_FILE_NAME = 'report.csv'  # 生成结果的文件 这个测试脚本只能生成csv文件
BASE_FILE_NAME = '/home/long/下载/sdk_test_voice/' # 测试的目录
REGISTER_FILE_NAME = "/register/" # 注册的文件夹名称
VERIFY_FILE_NAME = "/verify/" # 验证的文件夹名称
```



---
## SDK接口错误列表
| 错误ID | 错误说明 |
| -------| --------|
| common.miss_param | 缺少请求参数|
|common.wrong_time_stamp| 错误的时间戳，请求时间和服务器时间相差太大
|common.wrong_sign| 错误的数据签名
|common.wrong_data |错误的数据
|common.unkwon |未知错误
|common.unkwon_app_id| 未知的app id
|common.unkwon_session_id |未知的session id
|common.invalid_id_type |非法的id类型
|user.wrong_type |错误的用户类型
|user.not_exist| 用户不存在
|user.no_parent |父用户不存在
|user.no_child |子用户不存在
|user.not_valid| 用户被禁了
|record.pre_not_done |上一个上传流还未结束
|record.wrong_id| 错误的上传流ID
|record.wrong_target| 错误的语音用途
|record.target_not_allow| 语音用途不被允许
|record.no_module| 没有处理该语音的算法模块
|record.not_start |上传流还未开始
|record.unsupport_data_format| 不支持的语音格式
|record.snr_too_low| 语音信噪比过低
|record.speech_too_short |语音时间太短
|record.volumn_too_low |语音声音太小
|record.wrong_data |错误的语音数据
|record.wrong_voice_bit_count| 错误的语音bit数
|record.wrong_voice_rate |错误的采样率
|register.wrong_record_id |错误的语音ID
|register.need_more_record |需要更多语音完成注册
|register.multi_module |多个算法模型冲突
|register.no_module_found |没有可用的算法模块
|verify.wrong_record_id |错误的语音ID
|verify.no_module_found |没有可用的算法模块
|verify.need_register| 缺少声纹，需要先注册
|identity.wrong_record_id| 错误的语音ID
|identity.no_module_found | 没有可用的算法模块





