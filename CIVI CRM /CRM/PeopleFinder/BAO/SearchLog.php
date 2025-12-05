<?php

use CRM_PeopleFinder_ExtensionUtil as E;

/**
 * BAO for PeopleFinder SearchLog
 */
class CRM_PeopleFinder_BAO_SearchLog extends CRM_PeopleFinder_DAO_SearchLog {

  /**
   * Create or update a search log
   */
  public static function create($params) {
    $entityName = 'SearchLog';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new static();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
}

