
## 介绍
  微信SDK For ThinkPHP 5.0+ 基于[overtrue/wechat](https://github.com/overtrue/wechat)
  easywechat SDK for ThinkPHP5.0
  由于easywechat 4参考Laravel的框架风格，缺少集成ThinkPHP 5.0.*版本比较优雅的方式，还有很多基于ThinkPHP 5.0的系统有微信开发需求，因此开发此SDK供legacy system使用
  增加小程序云开发、微信支付服务商等接口
## 软件架构
软件架构说明

1.  ThinkPHP 5.0.*没有container和facade，因此需增加相关依赖
2.  使用了facade模式, TP5.0不支持, composer安装已自动复制, 如未生效, 需要手动将TP5.1的think\Facade.php文件拷贝到 thinkphp\library\think 目录下,facade.php无其他依赖
3.  由于TP5.0不支持容器, TP5.1的容器有一定耦合度不利于升级, 因此采用了illuminate/container
4.  TP5.0不支持middleware,中间件暂无更优雅的替代方案, 可以使用Hook 机制将具体实现的业务逻辑分发到各个模块

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

3. 如需应用启动时自动加载SDK，建议将Behavior\AppInit.php拷贝到application\common\behavior目录下，根据各自应用系统的情况初始化SDK

## 使用
### 接受普通消息
新建一个Controller，我这边用的是Official
```php
<?php
namespace app\wechatopen\controller;

use think\Controller;
use uctoo\ThinkEasyWeChat\Facade;
use think\Hook;
/**
 * 非第三方平台开发模式微信公众号的交互控制器，中控服务器
 * 主要获取和反馈微信平台的数据，分析用户交互和系统消息分发。
 */
class Official extends Controller {
    
    //行为绑定，需要将标签位的处理逻辑分发到各个行为类的都要在这里绑定行为
    public function _initialize()
    {
        Hook::add('user_enter_tempsession','app\\wechatopen\\behavior\\UserEnterTempsession');
        Hook::add('text_auto_reply','app\\wechatopen\\behavior\\CustomSendAutoReply');
        Hook::add('subscribemsg','app\\wechatopen\\behavior\\CustomSendAutoReply');
    }
        
     /**
     * 开启了开发模式的公众号所有发送到微信公众号的消息都会推送到该操作
     * 所以，微信公众平台后台填写的api地址则为该操作的访问地址
     * 在mp.weixin.qq.com  公众号登录 开发->基本配置-> 服务器地址(URL)填写  https://域名/wechatopen/official/index
     * @return mixed
     */
	public function index() {
	    $config = [
            // AppID
            'app_id' => '',
            // AppSecret
            'secret' => '',
            // Token
            'token' => 'uctoo',
            // EncodingAESKey
            'aes_key' => '',
        ];
        $officialAccount = Facade::officialAccount('',$config);  // 支持传入公众号配置信息
        $officialAccount->server->push(function($message){       //公众号收到消息回复用户
            return 'hello,world';
        });
        $officialAccount->server->serve()->send();
    }
}
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
```php
<?php
namespace app\wechatopen\controller;
use app\wechatopen\service\openPlatform\AuthorizedEventHandler;
use app\wechatopen\service\openPlatform\RegisterEventHandler;
use app\wechatopen\service\openPlatform\UnauthorizedEventHandler;
use app\wechatopen\service\openPlatform\ComponentVerifyTicketEventHandler;
use app\wechatopen\service\openPlatform\UpdateAuthorizedEventHandler;
use EasyWeChat\OpenPlatform\Application;
use EasyWeChat\OpenPlatform\Server\Guard;
use think\Controller;
use uctoo\ThinkEasyWeChat\Facade;

/*
 * 微信开放平台授权事件处理
 * */
class Authevent extends Controller {

    /*
     * 微信第三方平台授权事件接收URL
     * */
      public function index(){
          $auth_code = input('auth_code');  //商户扫码授权后第三方平台redirect回本地址时会有auth_code字段
          $openPlatform = Facade::openPlatform();

          if ($this->request->isGet() && !isset($auth_code)) {  //跳转第三方平台授权页,微信第三方平台接收到的地址 $_SERVER["HTTP_REFERER"] 需要与第三方平台 授权事件接收URL 相同
              $callbackurl = url('wechatopen/authevent/index','','',true);
              $PAurl = $openPlatform->getPreAuthorizationUrl($callbackurl);
              $this->success("跳转第三方平台授权页",$PAurl,'',1);
          }
        try{
            // 授权事件
            $openPlatform->server->push(new ComponentVerifyTicketEventHandler(),Guard::EVENT_COMPONENT_VERIFY_TICKET);
            // 授权事件
            $openPlatform->server->push(new AuthorizedEventHandler(),Guard::EVENT_AUTHORIZED);
            // 更新授权事件
            $openPlatform->server->push(new UpdateAuthorizedEventHandler(),Guard::EVENT_UPDATE_AUTHORIZED);
            // 取消授权事件
            $openPlatform->server->push(new UnauthorizedEventHandler(),Guard::EVENT_UNAUTHORIZED);
            // 小程序快速注册事件
            $openPlatform->server->push(new RegisterEventHandler(),Guard::EVENT_THIRD_FAST_REGISTERED);

            $openPlatform->server->serve()->send();
        }catch (\Exception $e){
            return '404';
         }
    }
}
```

### Oauth登录中间件(暂无解决方案)


### HOOK
> 你可以监听相应的事件，并对事件发生后执行相应的操作。
```php
<?php
Hook::add('text_auto_reply','app\\wechatopen\\behavior\\CustomSendAutoReply');
```

```php
<?php
namespace app\wechatopen\behavior;

use uctoo\ThinkEasyWeChat\Facade;

class CustomSendAutoReply
{
    /**
     * 用户进入小程序客服默认发图片
     * @access public
     */
    public function textAutoReply()
    {
    }

    /**
     * 用户关注默认发消息
     * @access public
     */
    public function subscribemsg()
    {
    }
}
```
## 开发说明

1.  建议基于TP5.0和easywechat的项目采用uctoo/think-easywechat进行重构, 重构方式非常简单, 仅需替换获取easywechat SDK实例的代码即可, 理论上其他代码无需改动,以fastadmin官方微信管理插件为例
  菜单管理功能 app\admin\controller\wechat\Menu
```php
<?php
use uctoo\ThinkEasyWeChat\Facade;

$app = Facade::officialAccount('',Config::load());   //注意帐号配置信息传第二个参数
```
2.  所有主动接口, 即通过应用服务器向微信服务器发送请求的, 都可以在业务实现点参考1 进行重构。所有被动响应接口, 即微信服务器向应用服务器推送消息的,
 包括单独开发模式的 服务器地址(URL), 第三方开发模式的 授权事件接收URL和 消息与事件接收URL 都有中心化的入口, 建议采用easywechat的EventHandler机制
 结合ThinkPHP的Hook机制, 分发到个业务实现点进行重构, 解耦各模块, 支持各模块实现微信开发的功能互不冲突, 可以商业化分发。以fastadmin官方微信管理插件为例
 
 微信接口 addons\wechat\controller\index
```php
 <?php
use uctoo\ThinkEasyWeChat\Facade;

/**
 * 微信接口
 */
class Index extends \think\addons\Controller
{
    public $app = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->app = Facade::officialAccount('',Config::load());  // 公众号
        Hook::add('text_auto_reply','app\\admin\\eventhandler\\wechat\\CustomSendAutoReply');        //注册具体业务处理的模块
        Hook::add('subscribemsg','app\\admin\\eventhandler\\wechat\\CustomSendAutoReply');
    }
    
        /**
         * 微信API对接接口
         */
        public function api()
        {
            //.....
            //自动回复处理
            $res =  Hook::listen("text_auto_reply", $message);         //实现点监听消息，将处理逻辑分发到具体业务实现模块
            $res =  Hook::listen("subscribemsg", $message);
            return $res[0];
        }
}
```
 在app\admin\eventhandler\wechat\CustomSendAutoReply类中具体实现功能
```php
 <?php
class CustomSendAutoReply
{
    /**
     * 用户进入默认发图片
     * @access public
     */
    public function textAutoReply($message)
    {
        $matches = null;
          $wechatService = new WechatService;
          $unknownMessage = WechatConfig::getValue('default.unknown.message');
          $unknownMessage = $unknownMessage ? $unknownMessage : "";
          //自动回复处理
         if ($message['MsgType'] == 'text') {
              $autoreply = null;
              $autoreplyList = WechatAutoreply::where('status', 'normal')->cache(true)->order('weigh DESC,id DESC')->select();
              foreach ($autoreplyList as $index => $item) {
                  //完全匹配和正则匹配
                  if ($item['text'] == $message['Content'] || (in_array(mb_substr($item['text'], 0, 1), ['#', '~', '/']) && preg_match($item['text'], $message['Content'], $matches))) {
                      $autoreply = $item;
                      break;
                  }
              }
              if ($autoreply) {
                  $wechatResponse = WechatResponse::where(["eventkey" => $autoreply['eventkey'], 'status' => 'normal'])->find();
                  if ($wechatResponse) {
                      $responseContent = (array)json_decode($wechatResponse['content'], true);
                      $wechatContext = WechatContext::where(['openid' => $message['FromUserName']])->order('id', 'desc')->find();
                      $result = $wechatService->response($this, $message['FromUserName'], $message['Content'], $responseContent, $wechatContext, $matches);
                      if ($result) {
                          return $result;
                      }
                  }
              }
          }
          return $unknownMessage;
    }

    /**
     * 用户关注默认发消息
     * @access public
     */
    public function subscribemsg()
    {
    }
}

``` 
  这样可以将消息响应的中控服务器逻辑代码开源，需要实现业务的模块在开源代码中注册入口，在各自商业化模块中实现具体功能。

3.  建议采用微信第三方平台方式进行微信相关功能开发，好处很多。

 更多 SDK 的具体使用请参考：https://easywechat.com

## 参考项目
- [overtrue/laravel-wechat](https://raw.githubusercontent.com/overtrue/laravel-wechat)
- [niaoxiaoxin/think-wechat](https://raw.githubusercontent.com/overtrue/laravel-wechat)

## 交流群
QQ群：102324323(已满)，138048128，使用疑问，开发，贡献代码请加群。
## 建议
近年微信生态已增加了很多新的能力，而且自2015年微信推出微信开放平台第三方开发方式以来，第三方开发方式逐渐流行，特别2017年小程序发布以来，第三方平台成为小程序的主要服务提供方式，建议采用第三方平台方式进行开发。

## 捐赠
如果觉得think-easywechat对你有帮助，欢迎请作者一杯咖啡

![捐赠wechat](https://gitee.com/uctoo/uctoo/raw/master/Public/images/donate.png)

## License

MIT
