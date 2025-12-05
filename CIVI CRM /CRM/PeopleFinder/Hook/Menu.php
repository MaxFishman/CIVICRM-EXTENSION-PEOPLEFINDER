<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * Implementation of hook_civicrm_navigationMenu
 */
function peoplefinder_civicrm_navigationMenu(&$menu) {
  // Find the Search menu
  $searchMenuId = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Search', 'id', 'name');
  
  $menu[] = [
    'attributes' => [
      'label' => E::ts('People Finder'),
      'name' => 'People Finder',
      'url' => 'civicrm/peoplefinder',
      'permission' => 'access CiviCRM',
      'operator' => 'OR',
      'separator' => 0,
      'parentID' => $searchMenuId,
      'navID' => NULL,
      'active' => 1,
    ],
  ];
}

/**
 * Implementation of hook_civicrm_alterMenu
 */
function peoplefinder_civicrm_alterMenu(&$items) {
  // Register routes
  $items['civicrm/peoplefinder'] = [
    'page_callback' => 'CRM_PeopleFinder_Page_PeopleFinder',
    'access_arguments' => [['access CiviCRM']],
  ];
  
  $items['civicrm/peoplefinder/login'] = [
    'page_callback' => 'CRM_PeopleFinder_Page_Login',
    'access_arguments' => [['access CiviCRM']],
  ];
  
  $items['civicrm/peoplefinder/verify'] = [
    'page_callback' => 'CRM_PeopleFinder_Page_Verify',
    'access_arguments' => [['access CiviCRM']],
  ];
  
  $items['civicrm/peoplefinder/logout'] = [
    'page_callback' => 'CRM_PeopleFinder_Page_Logout',
    'access_arguments' => [['access CiviCRM']],
  ];
  
  $items['civicrm/peoplefinder/admin'] = [
    'page_callback' => 'CRM_PeopleFinder_Admin_UserManagement',
    'access_arguments' => [['administer CiviCRM']],
  ];
}

