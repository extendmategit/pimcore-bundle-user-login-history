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

namespace Corepim\UserLoginHistoryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link https://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class CorepimUserLoginHistoryExtension extends Extension {

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container) {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }

}
