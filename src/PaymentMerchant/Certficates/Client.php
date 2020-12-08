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

namespace uctoo\ThinkEasyWeChat\PaymentMerchant\Certficates;

use uctoo\ThinkEasyWeChat\PaymentMerchant\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidExtensionException;

/**
 * Class Client.
 *
 * @author   liuml  <liumenglei0211@163.com>
 * @DateTime 2019-05-30  14:19
 */
class Client extends BaseClient
{
    /**
     * get certficates.
     *
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidArgumentException
     * @throws InvalidExtensionException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        $response = $this->httpGet('/v3/certificates');
        $certificates = $response['data'][0];
        $certificates['certificates'] = $this->decrypt($certificates['encrypt_certificate']);;
        unset($certificates['encrypt_certificate']);
       return $certificates;

    }

    /**
     * decrypt ciphertext.
     *
     * @param array $encryptCertificate
     *
     * @return string
     *
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidExtensionException
     */
    public function decrypt(array $encryptCertificate)
    {
        if (false === extension_loaded('sodium')) {
            throw new InvalidExtensionException('sodium extension is not installed，Reference link https://php.net/manual/zh/book.sodium.php');
        }

        if (false === sodium_crypto_aead_aes256gcm_is_available()) {
            throw new InvalidExtensionException('aes256gcm is not currently supported');
        }

        // sodium_crypto_aead_aes256gcm_decrypt function needs to open libsodium extension.
        // https://www.php.net/manual/zh/function.sodium-crypto-aead-aes256gcm-decrypt.php
        return sodium_crypto_aead_aes256gcm_decrypt(
            base64_decode($encryptCertificate['ciphertext'], true),
            $encryptCertificate['associated_data'],
            $encryptCertificate['nonce'],
            $this->app['config']->apiv3_key
        );
    }
}
