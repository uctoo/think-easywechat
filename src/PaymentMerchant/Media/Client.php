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

namespace uctoo\ThinkEasyWeChat\PaymentMerchant\Media;

use uctoo\ThinkEasyWeChat\PaymentMerchant\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;


class Client extends BaseClient
{
    /**
     * @param string $path
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\InvalidSignException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \EasyWeChat\MicroMerchant\Kernel\Exceptions\EncryptException
     */
    public function upload(string $path,string $filename,string $sha256)
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException(sprintf("File does not exist, or the file is unreadable: '%s'", $path));
        }

        $form = [
            'meta' => json_encode([
                'filename' => $filename,
                'sha256' => $sha256
            ]),
        ];

        return $this->httpUpload('/v3/merchant/media/upload', ['file' => $path],$form);

    }
}
