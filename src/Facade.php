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

namespace uctoo\ThinkEasyWeChat;

use think\Facade as ThinkFacade;
use Illuminate\Container\Container;

/**
 * Class Facade.
 *
 * @author patrick <contact@uctoo.com>
 */
class Facade extends ThinkFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'wechat.official_account';
    }

    /**
     * @return \EasyWeChat\OfficialAccount\Application
     */
    public static function officialAccount($name = '',$config = [])
    {
        return $name ? Container::getInstance()->make('wechat.official_account.' . $name, ["config"=>$config]) : Container::getInstance()->make('wechat.official_account',  ["config"=>$config]);
    }

    /**
     * @return \EasyWeChat\Work\Application
     */
    public static function work($name = '',$config = [])
    {
        return $name ? Container::getInstance()->make('wechat.work.' . $name,  ["config"=>$config]) : Container::getInstance()->make('wechat.work',  ["config"=>$config]);
    }

    /**
     * @return \EasyWeChat\Payment\Application
     */
    public static function payment($name = '',$config = [])
    {
        return $name ? Container::getInstance()->make('wechat.payment.' . $name,  ["config"=>$config]) : Container::getInstance()->make('wechat.payment',  ["config"=>$config]);
    }

    /**
     * @return \EasyWeChat\MiniProgram\Application
     */
    public static function miniProgram($name = '',$config = [])
    {
        return $name ? Container::getInstance()->make('wechat.mini_program.' . $name,  ["config"=>$config]) : Container::getInstance()->make('wechat.mini_program',  ["config"=>$config]);
    }

    /**
     * @return \EasyWeChat\OpenPlatform\Application
     */
    public static function openPlatform($name = '',$config = [])
    {
        return $name ? Container::getInstance()->make('wechat.open_platform.' . $name,  ["config"=>$config]) : Container::getInstance()->make('wechat.open_platform',  ["config"=>$config]);
    }
}