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

use Psr\SimpleCache\CacheInterface;
use think\Cache;

class CacheBridge implements CacheInterface
{

    public function get($key, $default = null)
    {
        return Cache::get($key,$default);
    }

    public function set($key, $value, $ttl = null)
    {
        return Cache::set($key,$value,$ttl);
    }

    public function delete($key)
    {
        return Cache::rm($key);
    }

    public function clear()
    {
        return Cache::clear();
    }

    public function getMultiple($keys, $default = null)
    {
    }

    public function setMultiple($values, $ttl = null)
    {
    }

    public function deleteMultiple($keys)
    {
    }

    public function has($key)
    {
        return Cache::has($key);
    }
}