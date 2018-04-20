<?php

namespace MauticPlugin\HostnetAuthBundle;

use Doctrine\DBAL\Schema\Schema;
use Mautic\PluginBundle\Bundle\PluginBundleBase;
use Mautic\PluginBundle\Entity\Plugin;
use Mautic\CoreBundle\Factory\MauticFactory;

class HostnetAuthBundle extends PluginBundleBase
{
    public static function onPluginInstall(
        Plugin $plugin,
        MauticFactory $factory,
        $metadata = null,
        $installedSchema = null
    ) {

        $db             = $factory->getDatabase();
        $platform       = $db->getDatabasePlatform()->getName();
        $queries        = [];

        $queries[] = 'CREATE TABLE IF NOT EXISTS ' . MAUTIC_TABLE_PREFIX . 'plugin_auth_browsers ( id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , user_id INT(11) NOT NULL , hash VARCHAR(255) NOT NULL , date_added DATETIME NOT NULL , PRIMARY KEY (id))';

        if (!empty($queries)) {
            $db->beginTransaction();
            try {
                foreach ($queries as $q) {
                    $db->query($q);
                }

                $db->commit();
            } catch (\Exception $e) {
                $db->rollback();

                throw $e;
            }
        }
    }

    public static function onPluginUpdate(
        Plugin $plugin,
        MauticFactory $factory,
        $metadata = null,
        Schema $installedSchema = null
    ) {
        $db             = $factory->getDatabase();
        $platform       = $db->getDatabasePlatform()->getName();
        $queries        = array();
        $fromVersion    = $plugin->getVersion();

        $queries[] = 'CREATE TABLE IF NOT EXISTS ' . MAUTIC_TABLE_PREFIX . 'plugin_auth_browsers ( id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , user_id INT(11) NOT NULL , hash VARCHAR(255) NOT NULL , date_added DATETIME NOT NULL , PRIMARY KEY (id))';

        if (!empty($queries)) {
            $db->beginTransaction();
            try {
                foreach ($queries as $q) {
                    $db->query($q);
                }

                $db->commit();
            } catch (\Exception $e) {
                $db->rollback();

                throw $e;
            }
        }
    }
}
