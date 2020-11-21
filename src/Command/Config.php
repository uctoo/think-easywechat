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

namespace uctoo\ThinkEasyWeChat\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Config extends Command
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('wechat:config')
            ->setDescription('send wechat config to config dir');
    }

    protected function execute(Input $input, Output $output)
    {
        if (file_exists(CONF_PATH . 'wechat.php')) {
            $output->error('file is exist');
            return;
        }
        $fileContent = file_get_contents(VENDOR_PATH . 'uctoo/think-easywechat/src/config.php');
        file_put_contents(CONF_PATH . 'wechat.php', $fileContent);

        if (file_exists(LIB_PATH  . 'think/Facade.php')) {
            $output->error('file is exist');
            return;
        }
        $fileContent = file_get_contents(VENDOR_PATH . 'uctoo/think-easywechat/src/Library/think/Facade.php');
        file_put_contents(LIB_PATH . 'think/Facade.php', $fileContent);

        $output->info('send success');
        return;
    }
}
