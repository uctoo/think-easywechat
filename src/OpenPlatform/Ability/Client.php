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
namespace uctoo\ThinkEasyWeChat\OpenPlatform\Ability;


use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Client extends BaseClient
{


    /**
     * @param array $params
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function fastRegisterWeapp(array $params)
    {

        return $this->httpPostJson('cgi-bin/component/fastregisterweapp', $params, ['action' => 'create']);

    }

    /**
     * @param string $name
     * @param string $legal_persona_wechat
     * @param string $legal_persona_name
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function fastRegisterWeappQuery(string $name, string $legal_persona_wechat, string $legal_persona_name)
    {

        return $this->httpPostJson('cgi-bin/component/fastregisterweapp', compact([
            'name',
            'legal_persona_wechat',
            'legal_persona_name',
        ]), ['action' => 'search']);

    }

}