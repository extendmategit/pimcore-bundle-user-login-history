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

namespace Corepim\UserLoginHistoryBundle\Tool;

use Pimcore\Tool;
use Pimcore\Model\DataObject;
use Pimcore\Extension\Bundle\Installer\AbstractInstaller;
use Symfony\Component\Filesystem\Filesystem;
use Corepim\UserLoginHistoryBundle\CorepimUserLoginHistoryBundle;
use Corepim\UserLoginHistoryBundle\Configuration\Configuration;
use Symfony\Component\Yaml\Yaml;

class Installer extends AbstractInstaller {

    /**
     * @var string
     */
    private $installSourcesPath;

    /**
     * @var Filesystem
     */
    private $FileSystem;

    /**
     * @var array
     */
    private $classes = [
        'CorepimUserLoginHistory',
    ];

    public function __construct() {
        parent::__construct();
        $this->init();
    }

    /**
     * Do initial work
     */
    private function init() {
        $this->FileSystem = new FileSystem();
        $this->installSourcesPath = __DIR__ . '/../Resources/install';
    }

    /**
     * {@inheritdoc}
     */
    public function install() {
        $this->updateConfigFile();
        $this->installClasses();
        $this->createFolders();
    }

    /**
     * For now, just update the config file to the current version.
     * {@inheritdoc}
     */
    public function update() {
        $this->updateConfigFile();
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall() {
        if ($this->FileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH)) {
            $this->FileSystem->rename(
                    Configuration::SYSTEM_CONFIG_FILE_PATH, PIMCORE_PRIVATE_VAR . "/bundles/CorepimUserLoginHistoryBundle/config_backup.yml", TRUE
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled() {
        return $this->FileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeInstalled() {
        return !$this->FileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeUninstalled() {
        return $this->FileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH);
    }

    /**
     * {@inheritdoc}
     */
    public function canBeUpdated() {
        $needUpdate = FALSE;
        if ($this->FileSystem->exists(Configuration::SYSTEM_CONFIG_FILE_PATH)) {
            $config = Yaml::parse(file_get_contents(Configuration::SYSTEM_CONFIG_FILE_PATH));
            if ($config['version'] !== CorepimUserLoginHistoryBundle::BUNDLE_VERSION) {
                $needUpdate = TRUE;
            }
        }

        return $needUpdate;
    }

    /**
     * Update config file
     */
    private function updateConfigFile() {
        if (!$this->FileSystem->exists(Configuration::SYSTEM_CONFIG_DIR_PATH)) {
            $this->FileSystem->mkdir(Configuration::SYSTEM_CONFIG_DIR_PATH);
        }

        $config = ['version' => CorepimUserLoginHistoryBundle::BUNDLE_VERSION];
        $yml = Yaml::dump($config);
        file_put_contents(Configuration::SYSTEM_CONFIG_FILE_PATH, $yml);
    }

    /**
     * Install all the classes.
     */
    public function installClasses() {
        foreach ($this->getClasses() as $className => $path) {

            $class = new DataObject\ClassDefinition();
            $id = $class->getDao()->getIdByName($className);

            if ($id !== FALSE) {
                continue;
            }

            $class->setName($className);
            $data = file_get_contents($path);
            $success = DataObject\ClassDefinition\Service::importClassDefinitionFromJson($class, $data);
        }
    }

    /**
     * Get a list of all the classes.
     * @return array
     */
    protected function getClasses(): array {
        $result = [];

        foreach ($this->classes as $className) {
            $filename = sprintf('class_%s_export.json', $className);
            $path = realpath(dirname(__FILE__) . '/../Resources/install/classes') . '/' . $filename;
            $path = realpath($path);

            if (FALSE === $path || !is_file($path)) {
                throw new \RuntimeException(sprintf(
                        'Class export for class "%s" was expected in "%s" but file does not exist', $className, $path
                ));
            }

            $result[$className] = $path;
        }

        return $result;
    }

    private function createFolders() {
        $folderName = 'user-login-history';
        $Folder = DataObject\Folder::getByPath('/' . $folderName);

        if (!($Folder instanceof DataObject\Folder)) {
            $userId = $this->getCurrentUserId();
            $Folder = DataObject\Folder::create([
                        'o_parentId' => 1,
                        'o_creationDate' => time(),
                        'o_userOwner' => $userId,
                        'o_userModification' => $userId,
                        'o_key' => $folderName,
                        'o_published' => TRUE,
            ]);
        }

        return $Folder;
    }

    private function getCurrentUserId() {
        $user = Tool\Admin::getCurrentUser();
        return $user ? $user->getId() : FALSE;
    }

}
