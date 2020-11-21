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

return [
    /*
      * 默认配置，将会合并到各模块中
      */
    'default'         => [
        /*
         * 指定 API 调用返回结果的类型：array(default)/object/raw/自定义类名
         */
        'response_type' => 'array',
        /*
         * 使用 ThinkPHP 的缓存系统
         */
        'use_tp_cache'  => true,
        /*
         * 日志配置
         *
         * level: 日志级别，可选为：
         *                 debug/info/notice/warning/error/critical/alert/emergency
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log' => [
            'level' => 'error',
            'file'  => '/log/wechat.log',
        ],
    ],

    //公众号
    'official_account' => [
        'default' => [
            // AppID
            'app_id' => '',
            // AppSecret
            'secret' => '',
            // Token
            'token' => '',
            // EncodingAESKey
            'aes_key' => '',
            /*
             * OAuth 配置
             *
             * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
             * callback：OAuth授权完成后的回调页地址(如果使用中间件，则随便填写。。。)
             */
            //'oauth' => [
            //    'scopes'   => array_map('trim', explode(',', 'snsapi_userinfo')),
            //    'callback' => '/examples/oauth_callback.php',
            //],
        ],
    ],

    //第三方开发平台
    'open_platform'    => [
        'default' => [
            'app_id'  => '',
            'secret'  => '',
            'token'   => '',
            'aes_key' => '',
        ],
    ],

    //小程序
    //'mini_program'     => [
    //    'default' => [
    //        'app_id'  => '',
    //        'secret'  => '',
    //        'token'   => '',
    //        'aes_key' => '',
    //    ],
    //],

    //支付
    //'payment'          => [
    //    'default' => [
    //        'sandbox'    => false,
    //        'app_id'     => '',
    //        'mch_id'     => 'your-mch-id',
    //        'key'        => 'key-for-signature',
    //        'cert_path'  => 'path/to/cert/apiclient_cert.pem',    // XXX: 绝对路径！！！！
    //        'key_path'   => 'path/to/cert/apiclient_key.pem',      // XXX: 绝对路径！！！！
    //        'notify_url' => 'http://example.com/payments/wechat-notify',                           // 默认支付结果通知地址
    //    ],
    //    // ...
    //],

    //企业微信
    //'work'             => [
    //    'default' => [
    //        'corp_id'  => 'xxxxxxxxxxxxxxxxx',
    //        'agent_id' => 100020,
    //        'secret'   => '',
    //        //...
    //    ],
    //],
];