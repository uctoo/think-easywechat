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

namespace uctoo\ThinkEasyWeChat\OpenPlatform\Authorizer\MiniProgram\Basic;


use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Client extends BaseClient
{


    /**
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function getAccountBasicInfo()
    {
        return $this->httpGet('/cgi-bin/account/getaccountbasicinfo');
    }

    /**
     * @param array $params
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function modifyDomain(array $params)
    {
        return $this->httpPostJson('wxa/modify_domain', $params);
    }

    /**
     * @param array $params
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function setWebviewDomain(array $params)
    {
        return $this->httpPostJson('wxa/setwebviewdomain', $params);
    }

    /**
     * @param string $nickname
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function checkVerifyNickname(string $nickname)
    {
        return $this->httpPostJson('cgi-bin/wxverify/checkwxverifynickname', ['nick_name' => $nickname]);
    }

    /**
     * Query the status of rename audit
     *
     * @param string $audit_id
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function queryRenameAudit(string $audit_id)
    {
        return $this->httpPostJson('wxa/api_wxa_querynickname', ['audit_id' => $audit_id]);
    }

    /**
     * @param array $params
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function modifyHeadImage(array $params)
    {
        return $this->httpPostJson('cgi-bin/account/modifyheadimage', $params);
    }

    /**
     * @param string $signature
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function modifySignature(string $signature)
    {
        return $this->httpPostJson('cgi-bin/account/modifysignature', ['signature' => $signature]);
    }

    /**
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function getSearchStatus()
    {
        return $this->httpGet('wxa/getwxasearchstatus');
    }

    /**
     * @param int $status
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function changSearchStatus(int $status)
    {
        return $this->httpPostJson('wxa/changewxasearchstatus', ['status' => $status]);
    }


}