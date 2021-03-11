
## 介绍
  微信SDK For ThinkPHP 6.0+ 基于[overtrue/wechat](https://github.com/overtrue/wechat)
  easywechat SDK for ThinkPHP6.0
  增加小程序云开发、微信支付服务商等接口
  
## 软件架构
软件架构说明


## 安装
### Composer安装
~~~
composer require uctoo/think-easywechat:dev-master
~~~
### Git安装
https://github.com/uctoo/think-easywechat
或
https://gitee.com/UCT/think-easywechat

## 配置
1. 修改配置文件
修改项目根目录下extra/wechat.php中对应的参数

2. 每个模块基本都支持多账号，默认为 default。

3. 根据各自应用系统的情况初始化SDK

## 使用
### 接受普通消息
新建一个Controller，我这边用的是Official
```php


```
### 获得SDK实例
#### 使用facade
```php
use uctoo\ThinkEasyWeChat\Facade;

$officialAccount = Facade::officialAccount();  // 公众号
$work = Facade::work(); // 企业微信
$payment = Facade::payment(); // 微信支付
$openPlatform = Facade::openPlatform(); // 开放平台
$miniProgram = Facade::miniProgram(); // 小程序  
```
以上均支持传入自定义账号:例如
```php
$officialAccount = Facade::officialAccount('test'); // 公众号
```

以上均支持传入自定义账号+配置(注:这里的config和配置文件中账号的格式相同):例如
```php
$officialAccount = Facade::officialAccount('',$config); // 公众号
```
### 微信第三方平台
新建一个Controller，微信第三方平台授权事件接收URL我这边用的是wechatopen\authevent



## 开发说明

1.  建议采用微信第三方平台方式进行微信相关功能开发，好处很多。

 更多 SDK 的具体使用请参考：https://easywechat.com

## 参考项目
- [overtrue/laravel-wechat](https://raw.githubusercontent.com/overtrue/laravel-wechat)

## 交流群
QQ群：102324323(已满)，138048128，使用疑问，开发，贡献代码请加群。
## 建议
近年微信生态已增加了很多新的能力，而且自2015年微信推出微信开放平台第三方开发方式以来，第三方开发方式逐渐流行，特别2017年小程序发布以来，第三方平台成为小程序的主要服务提供方式，建议采用第三方平台方式进行开发。

## 捐赠
如果觉得think-easywechat对你有帮助，欢迎请作者一杯咖啡

![捐赠wechat](https://gitee.com/uctoo/uctoo/raw/master/Public/images/donate.png)

## License

MIT
