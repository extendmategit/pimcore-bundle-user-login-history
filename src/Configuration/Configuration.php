<?php

/**
 * extendmate.com
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, 
 * please view the LICENCE.md and gpl-3.0.txt files that are distributed with this source code.
 *
 * @copyright  Copyright (c) extendmate.com (https://extendmate.com)
 * @license    GNU General Public License version 3 (GPLv3)
 * @author     Er Faiyaz Alam (https://www.linkedin.com/in/er-faiyaz-alam-0704219a/)
 */

namespace Corepim\UserLoginHistoryBundle\Configuration;

class Configuration {

    const SYSTEM_CONFIG_DIR_PATH = PIMCORE_PRIVATE_VAR . '/bundles/CorepimUserLoginHistoryBundle';
    const SYSTEM_CONFIG_FILE_PATH = PIMCORE_PRIVATE_VAR . '/bundles/CorepimUserLoginHistoryBundle/config.yml';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $systemConfig;

    /**
     * @param array $config
     */
    public function setConfig($config = []) {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfigNode() {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getConfigArray() {
        return $this->config;
    }

    /**
     * @param $slot
     *
     * @return mixed
     */
    public function getConfig($slot) {
        return $this->config[$slot];
    }

}
