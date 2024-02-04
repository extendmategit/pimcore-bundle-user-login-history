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

namespace Corepim\UserLoginHistoryBundle\Session\Configurator;

use Pimcore\Session\SessionConfiguratorInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\NamespacedAttributeBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionUserConfigurator implements SessionConfiguratorInterface {

    const BAG_NAME = 'user';
    const NS_ATTRIBUTE_BAG = 'corepim_user_login_history';

    /**
     * @inheritDoc
     */
    public function configure(SessionInterface $session) {
        $bag = new NamespacedAttributeBag(self::NS_ATTRIBUTE_BAG);
        $bag->setName(self::BAG_NAME);
        $session->registerBag($bag);
    }

}
