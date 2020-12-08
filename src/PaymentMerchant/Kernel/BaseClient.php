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

namespace uctoo\ThinkEasyWeChat\PaymentMerchant\Kernel;

use uctoo\ThinkEasyWeChat\PaymentMerchant\Application;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException;
use EasyWeChat\Kernel\BaseClient as Base;

class BaseClient extends Base
{
    /**
     * @var string
     */
    protected $certificates;

    /**
     * BaseClient constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->setHttpClient($this->app['http_client']);
    }

    /**
     * GET request.
     *
     * @param string $url
     * @param array $query
     *
     * @param bool $returnResponse
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws EncryptException
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpGet(string $url, array $query = [])
    {
        foreach ($query as $k => $v){
            $url .= '/'.$k.'/'.$v;
        }
        return $this->request($url, 'GET');
    }

    /**
     * JSON request.
     *
     * @param string $url
     * @param array $data
     * @param array $query
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyWeChat\Kernel\Support\Collection|array|object|string
     *
     * @throws EncryptException
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function httpPostJson(string $url, array $data = [], array $query = [])
    {
        return $this->request($url, 'POST', ['query' => $query, 'json' => $data, 'sign_body' => json_encode($data,JSON_UNESCAPED_UNICODE)]);
    }

    /**
     * request.
     *
     * @param string $endpoint
     * @param string $method
     * @param array $options
     * @param bool $returnResponse
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws EncryptException
     */
    public function request(string $endpoint,string $method = 'POST', array $options = [], $returnResponse = false)
    {

        $sign_body  = $options['sign_body'] ?? '';
        $options['headers'] = [
            'Content-Type'=> 'application/json',
            'Accept' => 'application/json',
            'Authorization' =>  $this->getAuthorization($endpoint,$method,$sign_body),
            'Wechatpay-Serial' => $this->app['config']['serial_no']
        ];
        $this->pushMiddleware($this->logMiddleware(), 'log');
        $response = $this->performRequest($endpoint, $method, $options);
        return  $returnResponse ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    /**
     * httpUpload.
     *
     * @param string $url
     * @param array $files
     * @param array $form
     * @param array $query
     * @param bool $returnResponse
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws EncryptException
     */
    public function httpUpload(string $url, array $files = [], array $form = [], array $query = [], $returnResponse = false)
    {
        $multipart = [];

        foreach ($files as $name => $path) {
            $multipart[] = [
                'name' => $name,
                'contents' => fopen($path, 'r'),
            ];
        }


        foreach ($form as $name => $contents) {
            $multipart[] = compact('name', 'contents');
        }

        $options = [
            'query' => $query,
            'multipart' => $multipart,
            'connect_timeout' => 30,
            'timeout' => 30,
            'read_timeout' => 30,
        ];

        $options['headers'] = [
            'Accept' => 'application/json',
            'Authorization' =>  $this->getAuthorization($url,'POST',$form['meta']),
        ];

        $this->pushMiddleware($this->logMiddleware(), 'log');

        $response = $this->performRequest($url, 'POST', $options);
        return $returnResponse ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }



    /**
     * processing parameters contain fields that require sensitive information encryption.
     *
     * @param array $params
     *
     * @return array
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     */
    protected function processParams(array $params)
    {

        $sensitive_fields = $this->getSensitiveFieldsName();
        foreach ($params as $k => $v) {
            if(is_array($v)){
                $params[$k] = $this->processParams($v);
            }else{
                if (in_array($k, $sensitive_fields, true)) {
                    $params[$k] = $this->encryptSensitiveInformation($v);
                }
            }
        }

        return $params;
    }

    /**
     * To id card, mobile phone number and other fields sensitive information encryption.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     */
    protected function encryptSensitiveInformation(string $string)
    {
        $certificates = $this->app['config']->get('certificate');
        if (null === $certificates) {
            throw new InvalidArgumentException('config certificate connot be empty.');
        }

        $encrypted = '';
        $publicKeyResource = openssl_get_publickey($certificates);
        $f = openssl_public_encrypt($string, $encrypted, $publicKeyResource,OPENSSL_PKCS1_OAEP_PADDING);
        openssl_free_key($publicKeyResource);
        if ($f) {
            return base64_encode($encrypted);
        }

        throw new EncryptException('Encryption of sensitive information failed');
    }

    /**
     * get sensitive fields name.
     *
     * @return array
     */
    protected function getSensitiveFieldsName()
    {
        return [
            'contact_name',
            'contact_id_number',
            'openid',
            'mobile_phone',
            'contact_email',
            'id_card_name',
            'id_card_number',
            'account_name',
            'account_number'
        ];
    }

    /**
     * @param string $url
     * @param string $method
     * @param string $body
     * @return string
     */
    protected function getAuthorization(string $url,string $method,string $body)
    {
        $nonce_str = uniqid();
        $timestamp = time();
        $message = $method . "\n" .
            $url . "\n" .
            $timestamp . "\n" .
            $nonce_str . "\n" .
            $body . "\n";
        openssl_sign($message, $raw_sign, $this->getPrivateKey(), 'sha256WithRSAEncryption');
        $sign = base64_encode($raw_sign);
        $schema = 'WECHATPAY2-SHA256-RSA2048 ';
        $token = sprintf('mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $this->app['config']['mch_id'], $nonce_str, $timestamp, $this->app['config']['mch_serial_no'], $sign);

        return $schema.$token;
    }

    /**
     * 获取商户私钥
     * @return bool|resource
     */
    protected function getPrivateKey(){
        $key_path = $this->app['config']['key_path'];
        if (!file_exists($key_path)) {
            throw new \InvalidArgumentException(
                "SSL certificate not found: {$key_path}"
            );
        }
        return openssl_get_privatekey(file_get_contents($key_path));
    }

}
