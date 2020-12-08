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

namespace uctoo\ThinkEasyWeChat\PaymentMerchant\Base;


use uctoo\ThinkEasyWeChat\PaymentMerchant\Kernel\BaseClient;
use GuzzleHttp\Exception\RequestException;

class Client extends BaseClient
{
    /**
     * apply to settle in to become a small micro merchant.
     *
     * @param array $params
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     */
    public function submitApplication(array $params)
    {
        $params = $this->processParams($params);
        return $this->httpPostJson('/v3/applyment4sub/applyment/', $params);

    }

    /**
     * query application status.
     *
     * @param string $applymentId
     * @param string $businessCode
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getStatus(string $applymentId, string $businessCode = '')
    {
        if (!empty($applymentId)) {
            $query = [
                'applyment_id' => $applymentId,
            ];
        } else {
            $query = [
                'business_code' => $businessCode,
            ];
        }

        return $this->httpGet('/v3/applyment4sub/applyment', $query);
    }


}
