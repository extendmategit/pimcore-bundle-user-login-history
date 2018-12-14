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

namespace Corepim\UserLoginHistoryBundle\EventListener;

use Pimcore\Event\Admin\Login\LoginCredentialsEvent;
use Pimcore\Event\Admin\Login\LoginFailedEvent;
use Pimcore\Event\Admin\Login\LogoutEvent;
use Pimcore\Model\DataObject\CorepimUserLoginHistory as UserLoginHistory;
use Pimcore\Model\DataObject;
use Pimcore\Model\User as PimcoreUser;
use Pimcore\Log\Simple;
use Pimcore\Tool\Session;
use Carbon\Carbon;
use Corepim\UserLoginHistoryBundle\Session\Configurator\SessionUserConfigurator;
use Corepim\UserLoginHistoryBundle\Helper\Object as ObjectHelper;

class LoginListener {

    /**
     * Key to store in session
     */
    const KEY_RECORD_ID = 'record_id';

    /**
     * Holds the folder name.
     */
    const FOLDER_NAME = 'user-login-history';

    /**
     * Login statuses
     */
    const LOGIN_STATUS_LOGIN = 'login';
    const LOGIN_STATUS_LOGOUT = 'logout';
    const LOGIN_STATUS_FAILED = 'fail';

    /**
     * Holds the instance of Modal
     * @var CorepimUserLoginHistory 
     */
    private $UserLoginHistory;

    /**
     * @var string 
     */
    private $username;

    /**
     * Holds the instance of Carbon.
     * @var Carbon 
     */
    private $currentDateTime;

    /**
     * Initialize the class
     */
    public function __construct() {
        $this->currentDateTime = new Carbon();
    }

    /**
     * @return string
     */
    private function getLogFileName() {
        return "corepim_user_login_history-" . $this->currentDateTime->toDateString();
    }

    /**
     * Create a new object
     * @throws \Exception
     */
    private function create() {
        $Folder = $this->getObjectFolder();
        $key = ObjectHelper::getValidKey($this->username . "-" . $this->currentDateTime->timestamp);
        $userId = FALSE;
        $status = FALSE;
        $username = trim($this->username);
        $user = PimcoreUser::getByName($username);

        if ($user) {
            $userId = $user->getId();
            $status = self::LOGIN_STATUS_LOGIN;
        }

        $this->UserLoginHistory = new UserLoginHistory();

        $this->UserLoginHistory->setParent($this->getObjectFolder());
        $this->UserLoginHistory->setKey(strtolower($key));
        $this->UserLoginHistory->setUserId($userId);
        $this->UserLoginHistory->setUsername($userId);
        $this->UserLoginHistory->setNicename($username);
        $this->UserLoginHistory->setLoginAt($this->currentDateTime);
        $this->UserLoginHistory->setStatus($status);
        $this->UserLoginHistory->setPublished(TRUE);
        $this->UserLoginHistory->save();
    }

    /**
     * Get the object folder.
     * If folder does not exist, create a new one.
     * 
     * @return DataObject\Folder
     */
    public function getObjectFolder() {
        $Folder = DataObject\Folder::getByPath('/' . self::FOLDER_NAME);
        if (!($Folder instanceof DataObject\Folder)) {
            throw new \Exception("Could not find the folder (" . self::FOLDER_NAME . ")");
        }
        return $Folder;
    }

    /**
     * Hooked with pimcore.admin.login.credentials
     * @param LoginCredentialsEvent $e
     */
    public function onLogin(LoginCredentialsEvent $e) {
        $credentials = $e->getCredentials();
        $this->username = $credentials['username'];

        try {
            $this->create();
            if ($this->UserLoginHistory->getId()) {
                $bag = $e->getRequest()->getSession()->getBag(SessionUserConfigurator::BAG_NAME);
                $bag->set(self::KEY_RECORD_ID, $this->UserLoginHistory->getId());
            }
        } catch (\Exception $exc) {
            Simple::log($this->getLogFileName(), $exc->getMessage());
        }
    }

    /**
     * Hooked with pimcore.admin.login.failed
     * @param LoginFailedEvent $e
     */
    public function onLoginFailed(LoginFailedEvent $e) {
        $this->UserLoginHistory->setStatus(self::LOGIN_STATUS_FAILED);
        $this->UserLoginHistory->save();
    }

    /**
     * Hooked with pimcore.admin.login.logout
     * @param LogoutEvent $e
     */
    public function onLogout(LogoutEvent $e) {
        $bag = $e->getRequest()->getSession()->getBag(SessionUserConfigurator::BAG_NAME);
        if (!$bag || !$bag->get(self::KEY_RECORD_ID)) {
            return;
        }

        $record = UserLoginHistory::getById($bag->get(self::KEY_RECORD_ID));
        if (!$record) {
            return;
        }

        try {
            $record->setStatus(self::LOGIN_STATUS_LOGOUT);
            $record->setLogoutAt($this->currentDateTime);
            $record->save();
        } catch (\Exception $exc) {
            Simple::log($this->getLogFileName(), $exc->getMessage());
        }
    }

}
