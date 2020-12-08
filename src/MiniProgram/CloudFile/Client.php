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

namespace uctoo\ThinkEasyWeChat\MiniProgram\CloudFile;


use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Client extends BaseClient
{
    /**
     * Get upload authentication
     *
     * @param string $path
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFileAuth(string $path){
        return $this->httpPostJson('tcb/uploadfile',['env' => $this->app->config->get('env'),'path'=>$path]);

    }

    public function uploadFile(string $path,string $file_path){
        $res = $this->uploadFileAuth($path);
        if(!$res['errcode']){
            $this->httpUpload($res['url'], [],  $form = [
                'key' => $path,
                'Signature' => $res['authorization'],
                'x-cos-security-token' => $res['token'],
                'x-cos-meta-fileid' => $res['cos_file_id'],
                'file' => fopen($file_path, 'r')
            ]);
        }
        return array_intersect_key($res,array_flip(['file_id']));
    }

    /**
     * Get download links in bulk
     *
     * @param array $file_list
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchDownloadFile(array $file_list){
        return $this->httpPostJson('/tcb/batchdownloadfile',['env' => $this->app->config->get('env'),'file_list' => $file_list]);
    }

    /**
     * Batch delete file
     *
     * @param array $file_list
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function batchDeleteFile(array $file_list){

        return $this->httpPostJson('/tcb/batchdeletefile',['env' => $this->app->config->get('env'),'fileid_list' => $file_list]);

    }


}