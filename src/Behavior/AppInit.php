<?php
/**
 * This file is part of think-easywechat.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    Patrick<contact@uctoo.com>
 * @copyright Patrick<contact@uctoo.com> UCToo [ Universal Convergence Technology ]
 * @link      http://www.uctoo.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace app\common\Behavior;

use EasyWeChat\MiniProgram\Application as MiniProgram;
use EasyWeChat\OfficialAccount\Application as OfficialAccount;
use EasyWeChat\OpenPlatform\Application as OpenPlatform;
use EasyWeChat\Payment\Application as Payment;
use EasyWeChat\Work\Application as Work;
use uctoo\ThinkEasyWechat\CacheBridge;
use Illuminate\Container\Container;

class AppInit
{
    protected $apps
        = [
            'official_account' => OfficialAccount::class,
            'work' => Work::class,
            'mini_program' => MiniProgram::class,
            'payment' => Payment::class,
            'open_platform' => OpenPlatform::class,
        ];

    /*
     *  通过application\extra\wechat.php获取微信应用帐号信息的服务初始化方法
     *      $app = Container::getInstance()->make('wechat.official_account');
     *  or
     *      $app = Container::getInstance()->make( $module_name, [ app_id => 'test' ])
     */

    public function run()
    {
        $wechat_default = config('wechat.default') ? config('wechat.default') : [];
        foreach ($this->apps as $name => $app) {
            if (!config('wechat.' . $name)) {
                continue;
            }
            $configs = config('wechat.' . $name);
            foreach ($configs as $config_name => $module_default) {

                Container::getInstance()->bind('wechat.' . $name . '.' . $config_name, function ($config = [] ) use ($app, $module_default, $wechat_default) {
                    //合并配置文件
                    $account_config = array_merge($module_default, $wechat_default, Container::getInstance()->getBindings());
                    $account_app = Container::getInstance()->make($app, ['config' => $account_config]);
                    if ($wechat_default['use_tp_cache']) {
                        $account_app['cache'] = Container::getInstance()->make(CacheBridge::class);
                    }
                    return $account_app;
                });
            }
            if (isset($configs['default'])) {
                Container::getInstance()->bind('wechat.' . $name, 'wechat.' . $name . '.default');
            }
        }
    }
}