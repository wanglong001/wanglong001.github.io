
#  python SDK of SPEAKIN Voiceprint cloud 
All of our interfaces are through the http communication, for the transmission of data content we will use app secret or session secret for encryption and signature. For each user who uses our voiceprint cloud interface, we will provide an app id and app secret. In the security level we have done two levels of authority, the use of the app secret can be user information operation and create a session. For each created session, there are no more than 2 hours of life cycle, session using session secret for encryption and signature, and session will be bound to a user, all operations will only affect the user. For the use of the app secret, we can also server ip restrictions to enhance security.

[TOC]


---

## The SDK interface
### Global interface
The global interface is the entry of the entire sdk. through the global interface, you can  use user interface and session interface. For each sdk user we will assign an app id and app secret. To create a global interface instance, you should require the following parameters:

| Parameter | Description |
|-------|---- |
| appId | We assign the app id|
| appSecret | We assign the app secret that must be properly kept|
| baseUrl | The voiceprint cloud of the http address, the public cloud is http://api2.speakin.mobi |

``` python
#!/usr/bin/env python3
from speakin_voice_sdk.api import Api
api = Api("your app id","your app secret","your server url")
```

#### List the available voiceprint models
We provide different algorithm models for different scenarios (voice often, ambient noise, data or free text, sampling rate, etc.). According to the needs of our users, we will assign one or more algorithmic models to each appId. Through this interface you can see which available models.

 - Request parameter
``` python
class ListModuleRequestSchema(Schema):
    pass
```
  - Response parameter
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

#### Create a session
For security, session generation is bound to a user. Thus we should ensuring that the app secret is not leaked. To Creating a session is a life cycle, you can adjust the life cycle by parameter ttl. In the creation of the session, you need to specify the permissions of the session, whether the call to the corresponding interface will be rejected.

|Parameter| Description|
| --- | --- |
| userId | Bound user id |
| canRegister |	Whether you can call the registration interface |
| canVerify | Whether you can call the authentication interface |
| canIdentity | Whether the authentication interface can be called |
| ttl | Session Survival Time in seconds |


  - Request Paramter
``` python
class StartSessionRequestSchema(Schema):
    user_id = fields.Str(required=True,default="")
    can_register = fields.Bool(default=False)
    can_verify = fields.Bool(default=False)
    can_identity = fields.Bool(default=False)
    ttl = fields.Int(default=500)
```

  - Response Paramter
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
#### Get the user management interface
User management is also using the app seceret as an encryption and signature, for the module more clearly independent of a module.

   - Request Paramert
``` python
class StartSessionRequestSchema(Schema):
    user_id = fields.Str(required=True,default="")
    can_register = fields.Bool(default=False)
    can_verify = fields.Bool(default=False)
    can_identity = fields.Bool(default=False)
    ttl = fields.Int(default=500)
```
   - Response Parameter
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
### User management interface

  - User attributes
 
| Attribute name | Description |
| ------ | ----|
|userId |The user ID needs to ensure that each unique user is unique within the app range
|userType |User type, there are three: DEV, PEOPLE, VIRTUAL
|valid |Whether the invalid, invalid user call interface will be rejected
|accessAllAppUser |Whether you can access all the user's voiceprint, in the voiceprint authentication meaningful

#### Set up user
  - Request Parameter
``` python
class SetAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    user_type = fields.Str(required=True)
    valid = fields.Bool(default=False)
    access_all_app_user = fields.Bool(default=False)
```

  - Response Paramter
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
#### Get the user
  - Request Parameter
``` python
class GetAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
```
  - Response Paramter
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
#### Increase sub-user
  - Request Parameter
``` python
class AddChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
  - Response Paramter
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
#### Delete the child user
  - Request Parameter
``` python
class RemoveChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
  - Response Parameter
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
#### Get the number of sub-users
  - Request Parameter
``` python
class GetChildAppUserCountRequestSchema(Schema):
    user_id = fields.Str(required=True)
```
  - Response Parameter
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
#### List sub-users
 -  Request Parameter
``` python
class ListChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    offset = fields.Int(required=True)
    limit = fields.Int(required=True)
```
 - Response Parameter
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
#### Check if the sub-user is included
 - Request Parameter
``` python
class ContainChildAppUserRequestSchema(Schema):
    user_id = fields.Str(required=True)
    child_user_id = fields.Str(required=True)
```
 - Response Parameter
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
### Session interface
The session interface mainly includes upload file, registration, authentication and authentication interface. 

The use of the file upload needs to specify the use of the file, all of the documents are checked in the upload process to do. In the registration, authentication and authentication interface, as long as the use of the specified voice ID on it.

#### File upload interface
File upload support streaming upload, sdk users can get data while uploading. Each session also supports only one upload stream. To open a new upload stream, you must end the last upload stream or cancel the previous upload stream. When the data is uploaded, the data fragment number starts at 1, not from 0.

 - Start uploading
##### Server-side check logic
  1. Check the permissions for targetAction
  2. Check whether the data format is supported
  3. Check if the last upload stream exists
  4. Check that the algorithm model parameters are correct

Request Parameter
``` python
class StartRecordRequestSchema(Schema):
    gen_text = fields.Bool(default=False)
    voice_bit_count = fields.Int(required=True)
    voice_rate = fields.Int(required=True)
    voice_lang = fields.Str(required=True)
    data_format = fields.Str(required=True)
    target_action = fields.Str(required=True)
```
Response Parameter
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
             print ( " file is uploading ... " )
            rs.done()
            print ( " upload file is successful ... " )
        else :
            print ( " recordInfo get failed ... " )
else :
   print ( " sessonInfo get failed ... " )
```
 - Upload file fragmentation

 From the beginning of the upload file demo to get the RecordStream object and call on the write method on it.

 - Cancel the upload
 
 From the start upload demo to get the RecordStream object and call cancel method on it.

 - End upload
 
The done method is called on the RecordStream object from the start upload demo.

##### Server logic
1. Check if it is in an upload stream
2. Check if the voice data is legal
3. Merge the file fragment and save it to the file server
4. Save the voice meta information
5. Clear the upload status

#### Voiceprint registration
Request parameter
``` python
class RegisterRequestSchema(Schema):
    record_id_list = fields.List(fields.Str(),required=True)
```
Response parameter
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
        # Upload the file test using the demo directory 501-1_5.wav audio file
        with open('501-1_5.wav', 'rb') as f :
            rs.write(f.read())
            print ( " file is uploading ... " )
        rs.done()
        print ( " upload file is successful ... " )
        recordIdList = []
        recordIdList.append(recordInfo['record_id'])
        recordIdListReq = {}
        recordIdListReq['record_id_list'] = recordIdList
        # voiceprint registration
        sessionApi.register(recordIdListReq)
        print( " voiceprint registration success ... " )
    else :
       print( " recordInfo get failed ... " )
else :
    print( " sessonInfo get failed ... " )
```
#### Voiceprint verification
Request parameter
``` python
class VerifyRequestSchema(Schema):
    record_id = fields.Str(required=True)
```
Response parameter
``` python
class VerifyResponseSchema(Schema):
    result = fields.Bool(required=True)
    score  = fields.Float(required=True)
    threshold_score = fields.Float(required=True)
    dyanmic_cmp_score = fields.Float(required=True)
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
         # Upload the file test using the demo directory 501-1_5.wav audio file
        with open('501-1_5.wav', 'rb') as f :
            rs.write(f.read())
            print ( " file is uploading ... " )
        rs.done()
        print ( " upload file is successful ... " )
        recordIdListObj = {}
        recordIdListObj['record_id'] = recordInfo['record_id']
        # voiceprint verification
        isVerify = sessionApi.verify(recordIdListObj)
        if isVerify :
            print( " voiceprint verification is successful ... " )
        else :
            print( " voiceprint verification failure. .. " )
    else :
     print("recordInfo get failed ...")
else :
    print("sessonInfo get successful...")
```
#### Voiceprint identify
The certification will be compared to the voice of multiple users. If the user can access all the user's voiceprint, that is the global contrast. For ordinary users, only against their own and sub-users voiceprint.

Requset Parameter
``` python
class IdentityRequestSchema(Schema):
    record_id = fields.Str(required=True)
```
Response Parameter
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
        # Upload the file test 501-1_5.wav audio file 
        with open('501-1_5.wav', 'rb') as f :
        rs.write(f.read())
        print( " file is uploading ... " )
        rs.done()
        print("upload file successfully...")
        recordIdListObj = {}
        recordIdListObj['record_id'] = recordInfo['record_id']
        # voiceprint identity
        userIdList = sessionApi.identity(recordIdListObj)
        if userIdList != None :
            print("voiceprint identity success...")
            print(userIdList)
        else :
            print("voiceprint identity failed...")
    else :
        print("recordInfo get failed...")
else :
    print("sessonInfo get successful...")
```

---

## How do I test audio?
You can refer to the DEMO3.py file that is under the demo directory  
only need to configure the following constant:
``` python
"""
     My Contents are as follows: ：
    sdk_test_voice
                |
                name1(the folder name is as same as tester)
                    |
                    register
                            |
                            *.wav
                    |
                    verify
                            |
                            *.wav
                |
                name2(the folder name is as same as tester)
                    |
                    register
                           |
                            *.wav
                    |
                    verify
                            |
                            *.wav
"""

OUT_FILE_NAME = 'report.csv'  # Generate the results of the file ,This test script can only generate csv file
BASE_FILE_NAME = '/home/long/下载/sdk_test_voice/' # test directory
REGISTER_FILE_NAME = "/register/"  # registered folder name 
VERIFY_FILE_NAME = "/verify/"  # Verify the folder name
```



---
## SDK interface error list
| Error ID | Misrepresentation |
| -------| --------|
|common.miss_param|	Missing request parameters
|common.wrong_time_stamp|	The wrong timestamp, the request time and the server time difference is too large
|common.wrong_sign|	Wrong data signature
|common.wrong_data|	Wrong data
|common.unkwon|	unknown mistake
|common.unkwon_app_id|	Unknown app id
|common.unkwon_session_id|	Unknown session id
|common.invalid_id_type|	Illegal id type
|user.wrong_type|	Wrong user type
|user.not_exist|	User does not exist
|user.no_parent|	The parent user does not exist
|user.no_child|	Sub-user does not exist
|user.not_valid	|The user is banned
|record.pre_not_done|	The last upload is not over yet
|record.wrong_id|	Wrong upload stream ID
|record.wrong_target|	Wrong voice use
|record.target_not_allow|	Voice use is not allowed
|record.no_module|	There is no algorithm module that handles the speech
|record.not_start|	The upload stream has not yet begun
|record.unsupport_data_format|	Unsupported voice format
|record.snr_too_low	Voice| signal to noise ratio is too low
|record.speech_too_short|	Voice time is too short
|record.volumn_too_low|	Voice sounds too small
|record.wrong_data|	Wrong voice data
|record.wrong_voice_bit_count|	Wrong voice bit number
|record.wrong_voice_rate|	Wrong sampling rate
|register.wrong_record_id|	Wrong voice ID
|register.need_more_record|	Need more voice to complete the registration
|register.multi_module|	Multiple algorithm model conflicts
|register.no_module_found|	There are no available algorithm modules
|verify.wrong_record_id|	Wrong voice ID
|verify.no_module_found	|There are no available algorithm modules
|verify.need_register|	Lack of voiceprint, need to register first
|identity.wrong_record_id|	Wrong voice ID
|identity.no_module_found|	There are no available algorithm modules





