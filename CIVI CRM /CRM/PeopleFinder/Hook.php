<?php

/**
 * Hook implementations
 */
class CRM_PeopleFinder_Hook {

  /**
   * Implementation of hook_civicrm_navigationMenu
   */
  public static function navigationMenu(&$menu) {
    require_once 'CRM/PeopleFinder/Hook/Menu.php';
    return peoplefinder_civicrm_navigationMenu($menu);
  }

  /**
   * Implementation of hook_civicrm_alterMenu
   */
  public static function alterMenu(&$items) {
    require_once 'CRM/PeopleFinder/Hook/Menu.php';
    return peoplefinder_civicrm_alterMenu($items);
  }

}

