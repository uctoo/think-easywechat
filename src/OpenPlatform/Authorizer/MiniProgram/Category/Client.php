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

namespace uctoo\ThinkEasyWeChat\OpenPlatform\Authorizer\MiniProgram\Category;


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
    public function getAll()
    {
        return $this->httpGet('/cgi-bin/wxopen/getallcategories');
    }

    /**
     * Get the set
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function get()
    {
        return $this->httpGet('cgi-bin/wxopen/getcategory');
    }

    /**
     * @param array $categories
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function add(array $categories)
    {
        return $this->httpPostJson('cgi-bin/wxopen/addcategory', [
            'categories' => $categories
        ]);
    }

    /**
     * @param int $first
     * @param int $second
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function delete(int $first, int $second)
    {
        return $this->httpPostJson('cgi-bin/wxopen/deletecategory', [
            'first' => $first,
            'second' => $second
        ]);
    }

    /**
     * @param int $first
     * @param int $second
     * @param array $certicates
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function modify(int $first, int $second,array $certicates){
        return $this->httpPostJson('cgi-bin/wxopen/modifycategory', [
            'first' => $first,
            'second' => $second,
            'certicates' => $certicates
        ]);
    }


    /**
     * Gets the category information that can be filled in at audit time
     *
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     */
    public function getSettable()
    {
        return $this->httpGet('wxa/get_category');
    }


}