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

namespace Corepim\UserLoginHistoryBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Corepim\UserLoginHistoryBundle\Tool\Installer;

class CorepimUserLoginHistoryBundle extends AbstractPimcoreBundle {

    const BUNDLE_VERSION = '1.0.1';

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container) {

        parent::build($container);
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion() {
        return self::BUNDLE_VERSION;
    }

    /**
     * {@inheritdoc}
     */
    public function getInstaller() {
        return $this->container->get(Installer::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getJsPaths() {
        return [
            '/bundles/corepimuserloginhistory/js/pimcore/startup.js'
        ];
    }

}
