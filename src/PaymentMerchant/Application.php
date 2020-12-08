<?php

/**
 * This file is part of think-easywechat.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    UCToo<contact@uctoo.com>
 * @copyright UCToo<contact@uctoo.com> UCToo [ Universal Convergence Technology ]
 * @link      http://www.uctoo.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace uctoo\ThinkEasyWeChat\PaymentMerchant;

use uctoo\ThinkEasyWeChat\PaymentMerchant\Certficates\Client;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\ServiceContainer;

/**
 * Class Application.
 *
 *
 * @property Client    $certficates
 * @property \app\util\src\PaymentMerchant\Media\Client $media
 *
 * @method mixed submitApplication(array $params)
 * @method mixed getStatus(string $applymentId, string $businessCode = '')
 * @method mixed upgrade(array $params)
 * @method mixed getUpgradeStatus(string $subMchId = '')
 */
class Application extends ServiceContainer
{
    /**
     * @var array
     */
    protected $providers = [
        // Base services
        Base\ServiceProvider::class,
        Certficates\ServiceProvider::class,
        Media\ServiceProvider::class,
    ];

    /**
     * @var array
     */
    protected $defaultConfig = [
        'http' => [
            'base_uri' => 'https://api.mch.weixin.qq.com/',
        ],
        'log' => [
            'default' => 'dev', // 默认使用的 channel，生产环境可以改为下面的 prod
            'channels' => [
                // 测试环境
                'dev' => [
                    'driver' => 'single',
                    'path' => '/tmp/easywechat.log',
                    'level' => 'debug',
                ],
                // 生产环境
                'prod' => [
                    'driver' => 'daily',
                    'path' => '/tmp/easywechat.log',
                    'level' => 'info',
                ],
            ],
        ],
    ];

    /**
     * @return string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function getKey()
    {
        $key = $this['config']->key;

        if (empty($key)) {
            throw new InvalidArgumentException('config key connot be empty.');
        }

        if (32 !== strlen($key)) {
            throw new InvalidArgumentException(sprintf("'%s' should be 32 chars length.", $key));
        }

        return $key;
    }



    /**
     * setCertificate.
     *
     * @param string $certificate
     * @param string $serialNo
     *
     * @return $this
     */
    public function setCertificate(string $certificate, string $serialNo)
    {
        $this['config']->set('certificate', $certificate);
        $this['config']->set('serial_no', $serialNo);

        return $this;
    }



    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this['base'], $name], $arguments);
    }
}
