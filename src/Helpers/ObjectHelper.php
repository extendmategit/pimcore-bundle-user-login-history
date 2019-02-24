<?php

/**
 * Corepim.com
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, 
 * please view the LICENCE.md and gpl-3.0.txt files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2018 Corepim.com (http://corepim.com)
 * @license    GNU General Public License version 3 (GPLv3)
 * @author     Er Faiyaz Alam (https://www.linkedin.com/in/er-faiyaz-alam-0704219a/)
 */

namespace Corepim\UserLoginHistoryBundle\Helpers;

use Pimcore\File;
use Pimcore\Model\Element\Service;

class ObjectHelper {

    public static function getValidKey($key) {
        $bool = method_exists('Pimcore\Model\Element\Service', 'getValidKey');
        $result = $bool ? Service::getValidKey($key, 'object') : File::getValidFilename($key);
        return str_replace('%', '-', $result);
    }

}
