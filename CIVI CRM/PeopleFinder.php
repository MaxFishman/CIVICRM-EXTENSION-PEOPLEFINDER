<?php
/**
 * PeopleFinder Extension
 *
 * Allows users to search for friends from the past and connect without disclosing personal information
 */

use CRM_PeopleFinder_ExtensionUtil as E;

class CRM_PeopleFinder_Extension extends CRM_Extension_Base {

  /**
   * Install the extension
   */
  public function install() {
    $this->createTables();
    $this->createMenuItems();
  }

  /**
   * Uninstall the extension
   */
  public function uninstall() {
    $this->dropTables();
    $this->removeMenuItems();
  }

  /**
   * Enable the extension
   */
  public function enable() {
    // Clear menu cache
    CRM_Core_BAO_Navigation::resetNavigation();
  }

  /**
   * Disable the extension
   */
  public function disable() {
    // Clear menu cache
    CRM_Core_BAO_Navigation::resetNavigation();
  }

  /**
   * Create database tables
   */
  private function createTables() {
    $queries = [
      "CREATE TABLE IF NOT EXISTS `civicrm_peoplefinder_user` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `contact_id` int(10) unsigned NOT NULL COMMENT 'FK to Contact',
        `email` varchar(255) NOT NULL,
        `verification_token` varchar(64) DEFAULT NULL,
        `is_verified` tinyint(4) DEFAULT 0,
        `invitation_token` varchar(64) DEFAULT NULL,
        `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
        `last_login` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `contact_id` (`contact_id`),
        UNIQUE KEY `email` (`email`),
        KEY `verification_token` (`verification_token`),
        KEY `invitation_token` (`invitation_token`),
        CONSTRAINT `FK_civicrm_peoplefinder_user_contact_id` FOREIGN KEY (`contact_id`) REFERENCES `civicrm_contact` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
      
      "CREATE TABLE IF NOT EXISTS `civicrm_peoplefinder_connection` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `searcher_id` int(10) unsigned NOT NULL COMMENT 'FK to peoplefinder_user',
        `target_contact_id` int(10) unsigned NOT NULL COMMENT 'FK to Contact',
        `status` varchar(20) DEFAULT 'pending' COMMENT 'pending, accepted, declined',
        `message` text DEFAULT NULL,
        `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
        `response_date` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `searcher_id` (`searcher_id`),
        KEY `target_contact_id` (`target_contact_id`),
        KEY `status` (`status`),
        CONSTRAINT `FK_civicrm_peoplefinder_connection_searcher` FOREIGN KEY (`searcher_id`) REFERENCES `civicrm_peoplefinder_user` (`id`) ON DELETE CASCADE,
        CONSTRAINT `FK_civicrm_peoplefinder_connection_target` FOREIGN KEY (`target_contact_id`) REFERENCES `civicrm_contact` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;",
      
      "CREATE TABLE IF NOT EXISTS `civicrm_peoplefinder_search_log` (
        `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        `user_id` int(10) unsigned NOT NULL COMMENT 'FK to peoplefinder_user',
        `search_params` text DEFAULT NULL,
        `results_count` int(10) DEFAULT 0,
        `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        KEY `user_id` (`user_id`),
        CONSTRAINT `FK_civicrm_peoplefinder_search_log_user` FOREIGN KEY (`user_id`) REFERENCES `civicrm_peoplefinder_user` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;"
    ];

    foreach ($queries as $query) {
      CRM_Core_DAO::executeQuery($query);
    }
  }

  /**
   * Drop database tables
   */
  private function dropTables() {
    $tables = [
      'civicrm_peoplefinder_search_log',
      'civicrm_peoplefinder_connection',
      'civicrm_peoplefinder_user',
    ];

    foreach ($tables as $table) {
      CRM_Core_DAO::executeQuery("DROP TABLE IF EXISTS `{$table}`");
    }
  }

  /**
   * Create menu items
   */
  private function createMenuItems() {
    // Menu items are created via hook_civicrm_navigationMenu
    // Routes are registered via hook_civicrm_alterMenu
  }

  /**
   * Remove menu items
   */
  private function removeMenuItems() {
    // Menu items are removed automatically when extension is uninstalled
    CRM_Core_BAO_Navigation::resetNavigation();
  }
}

