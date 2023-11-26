<?php

namespace ExtendMate\Bundle\UserLoginHistoryBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;


class ExtendMateUserLoginHistoryBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    public const  TABLE_NAME_USER_LOGIN_HISTORY = 'bundle_emulh_login_attempts';

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getJsPaths(): array
    {
        return [
            '/bundles/extendmateuserloginhistory/js/pimcore/startup.js'
        ];
    }

    public function getInstaller(): InstallerInterface
    {
        return $this->container->get(Installer::class);
    }

}