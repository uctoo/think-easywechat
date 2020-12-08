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

namespace uctoo\ThinkEasyWeChat\MiniProgram\CloudEnv;


use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Client extends BaseClient
{
    /**
     * Open cloud development
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createCloudUser()
    {
        return $this->httpPost('tcb/createclouduser');
    }

    /**
     * Get env info
     *
     * @param string $env
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEnvInfo(string $env = null)
    {
        return $this->httpPostJson('tcb/getenvinfo', ['env' => $env]);
    }

    /**
     * Create a cloud environment
     *
     * @param string $env
     * @param string $alias
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createEnvAndResource(string $env, string $alias)
    {
        return $this->httpPostJson('tcb/createenvandresource', ['env' => $env, 'alias' => $alias]);
    }

    /**
     * Upload applet configuration
     *
     * @param array $config
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAppConfig(array $config)
    {
        return $this->httpPostJson('tcb/uploadappconfig', $config);
    }

    /**
     * Get applet configuration
     *
     * @param int $type
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAppConfig(int $type = 1)
    {
        return $this->httpPostJson('tcb/getappconfig', ['type' => $type]);
    }

    /**
     * Create a cloud function
     *
     * @param string $function_name
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createFunction(string $function_name)
    {
        return $this->httpPostJson('tcb/createfunction', [
            'env' => $this->app->config->get('env'),
            'function_name' => $function_name
        ]);
    }

    /**
     * Get the code protection key
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCodeSecret()
    {
        return $this->httpPostJson('tcb/getcodesecret');
    }

    /**
     * Get upload credentials
     *
     * @param string $hashed_payload
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUploadSignature(string $hashed_payload)
    {
        return $this->httpPostJson('tcb/getuploadsignature', [
            'hashed_payload' => $hashed_payload
        ]);
    }

    /**
     * Get a list of cloud functions
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function functionList(int $limit = null, int $offset = null)
    {
        $params =  [
            'env' => $this->app->config->get('env'),
        ];

        $limit && $params['limit'] = $limit;
        $limit && $params['offset'] = $offset;

        return $this->httpPostJson('tcb/listfunctions',$params);
    }

    /**
     * Get the cloud function download Url
     *
     * @param string $function_name
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDownloadUrl(string $function_name)
    {
        return $this->httpPostJson('tcb/downloadfunction', [
            'env' => $this->app->config->get('env'),
            'function_name' => $function_name
        ]);
    }

    /**
     * Upload the cloud function configurations
     *
     * @param string $function_name
     * @param string $config
     * @param int $type
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFuncConfig(string $function_name, string $config,int $type = 1)
    {
        return $this->httpPostJson('tcb/uploadfuncconfig', [
            'env' => $this->app->config->get('env'),
            'config' => $config,
            'type' => $type,
            'function_name' => $function_name,
        ]);
    }

    /**
     * Get the cloud function configuration
     *
     * @param string $function_name
     * @param int $type
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFuncConfig(string $function_name, int $type = 1)
    {
        return $this->httpPostJson('tcb/getfuncconfig', [
            'env' => $this->app->config->get('env'),
            'type' => $type,
            'function_name' => $function_name,
        ]);
    }

    /**
     * @param array $params
     * @param array $headers
     * @return array|Collection|object|ResponseInterface|string
     */
    public function uploadCodeZip(array $params,array $headers){
        $client = new \GuzzleHttp\Client();
        $res = $client->request('POST','https://scf.tencentcloudapi.com',['body'=> json_encode($params,JSON_UNESCAPED_SLASHES),'headers'=>$headers])->getBody()->getContents();
        return json_decode($res);
    }

}