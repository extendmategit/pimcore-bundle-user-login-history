<?php

/**
 * Created by Elements.at New Media Solutions GmbH
 *
 */

namespace ExtendMate\Bundle\UserLoginHistoryBundle;

use Doctrine\DBAL\Connection;
use Pimcore\Extension\Bundle\Installer\SettingsStoreAwareInstaller;

class Installer extends SettingsStoreAwareInstaller
{

    public function install(): void
    {
        $this->createTables();
        parent::install();
    }

    /**
     * {@inheritdoc}
     */
    public function needsReloadAfterInstall(): bool
    {
        return true;
    }

    protected function getDb(): Connection
    {
        return \Pimcore\Db::get();
    }

    protected function createTables(): void
    {

        $db = $this->getDb();

        $db->executeQuery("CREATE TABLE IF NOT EXISTS `" . ExtendMateUserLoginHistoryBundle::TABLE_NAME_USER_LOGIN_HISTORY . "` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `userId` int(11) unsigned DEFAULT NULL,
            `username` varchar(100) DEFAULT NULL,
            `roles` varchar(1000) DEFAULT NULL,
            `ipAddress` varchar(50) DEFAULT NULL,
            `userAgent` varchar(100) DEFAULT NULL,
            `lastSeenAt` int(11) unsigned DEFAULT NULL,
            `loginAt` int(11) unsigned DEFAULT NULL,
            `logoutAt` int(11) unsigned DEFAULT NULL,
            `status` enum('login','logout','fail', 'error') NOT NULL,
            PRIMARY KEY (`id`),
            KEY `userId` (`userId`),
            KEY `username` (`username`),
            KEY `status` (`status`),
            CONSTRAINT `" . ExtendMateUserLoginHistoryBundle::TABLE_NAME_USER_LOGIN_HISTORY . "_userId` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    public function uninstall(): void
    {
        $tables = [ExtendMateUserLoginHistoryBundle::TABLE_NAME_USER_LOGIN_HISTORY];
        foreach ($tables as $table) {
            $this->getDb()->executeQuery('DROP TABLE IF EXISTS ' . $table);
        }

        parent::uninstall();
    }

    public function getLastMigrationVersionClassName(): ?string
    {
        return parent::getLastMigrationVersionClassName();
    }
}
