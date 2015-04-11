##框架名称：
> XYWeChat

##框架简介：
`XYWeChat` 是一款为便捷开发微信工程平台而产生的 `PHP` 类库，其目的是为方便开发微信公众平台，类库已支持绝大多数微信开发的接口。后期会随着微信官方的更新而做出相应的更新。

##开发语言：
> PHP

##版本要求：
> PHP > 5.3

##相关链接

公众平台首页：https://mp.weixin.qq.com/
公众平台文档：http://mp.weixin.qq.com/wiki/home/index.html
测试申请地址：http://mp.weixin.qq.com/debug/cgi-bin/sandbox?t=sandbox/login

##联系方式
邮箱：dingdayu (85897970@qq.com)
博客：http://blog.dingxiaoyu.com

------------

## 相关知识

###微信公众账号分类：

[![微信官方给出的分类表格](http://file.service.qq.com/user-files/uploads/201502/0c4c88ce860d609528e4e14ff2a6e947.jpg "微信官方给出的分类表格")](http://kf.qq.com/faq/120911VrYVrA130805byM32u.html "微信官方给出的分类表格")

###专业术语：

1、OpenId：微信服务器并不会告诉公众号用户的微信ID，即使是你的关注者也不行，为了解决开发中唯一标识的问题，微信使用了OpenId，所谓的OpenId，就是用户和微信公众号之间的一种唯一关系。一个用户在一个公众号面前，享用唯一的OpenId，不会和别人重复。换言之，同一个用户在另一个公众号面前，是拥有另一个OpenId的。再直白些就是$openId = md5('用户微信ID+公众号ID')

2、Access_Token：此项只有认证号的功能才会使用的到，Access_token是一个授权标识，即一个授权验证码，一个标识10分钟内有效，10分钟的有效期内公众号的多个关注者可以使用同一个Access_Token。在使用主动给指定用户发送消息、自定义菜单、用户管理和用户组管理等功能的时候，每次操作需要给微信服务器以参数的形式附带Access_token。

3、Access_Token网页版：本Access_Token网页版授权时会使用到，和2中的Access_Toekn是不同的东西，不过使用我们的LaneWeChat微信快速开发框架是不需要了解这些的。Access_Token网页版是说在用户打开你的公众号提供的网页的时候，你的网页需要获取用户的OpenId、昵称、头像等信息的时候授权用的。同时，本Access_Token网页版有两种用法，一种是打开网页后弹出一个授权框，让用户点击是否授权，界面像主流的开放平台授权界面（比如QQ登陆某网站，支付宝账号登陆某网站等）；另一种是不需要弹出授权框仍旧可以获取用户信息，用法可以在实例中看到。


###如何使用：

1、本框架以代码包的插件形式放在项目的目录中即可。调用时只需要`include 'XYWeChat.php'`即可，可参照`demo.php`。如：
```php
<?php
	include 'XYWeChat.php';
	//获取自定义菜单列表
	$menuList = Menu::getMenu();
```

同时也可以仿照 `wechat.php`进行微信公众平台接受的开发（被动消息）。

2、配置项：打开根目录下的 `config.php`，修改定义常量`WECHAT_APPID`，`WECHAT_APPSECRET`，`WECHAT_URL`。其中前两项可以在微信公众号官网的开发者页面中找到，而`WECHAT_URL`是你微信项目的URL,即回调地址。

3、本框架的外部访问唯一入口为根目录下的`wechat.php`，本框架的内部调用唯一入口为根目录下的`XYWeChat.php`。
   - 两者的区别是`wechat.php`是留给微信平台调用的入口。
   - 而`XYWeChat.php`是我们项目内部调用时需要调用的。

4、首次使用时，请打开根目录下的wechat.php，注释掉26行，并且打开注释第29行。`验证服务器地址的有效性`

5、微信服务器在第4步验证通过后，反向操作第4步，即注释掉第27行，打开注释第26行。至此，安装配置完成。

6、`AccessToken`类中，是以文件的形式来保存关注者的`TokenID`请在实际应用中更换为自己的存储方式。