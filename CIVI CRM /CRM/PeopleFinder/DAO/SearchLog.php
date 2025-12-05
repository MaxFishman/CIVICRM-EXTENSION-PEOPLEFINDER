<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 */
class CRM_PeopleFinder_DAO_SearchLog extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_peoplefinder_search_log';
  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  static $_log = TRUE;
  /**
   * Unique ID
   *
   * @var int unsigned
   */
  public $id;
  /**
   * FK to peoplefinder_user
   *
   * @var int unsigned
   */
  public $user_id;
  /**
   * Search parameters
   *
   * @var text
   */
  public $search_params;
  /**
   * Results count
   *
   * @var int
   */
  public $results_count;
  /**
   * Created date
   *
   * @var datetime
   */
  public $created_date;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_peoplefinder_search_log';
    parent::__construct();
  }
  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static ::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'user_id', 'civicrm_peoplefinder_user', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }
  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'required' => TRUE,
          'table_name' => 'civicrm_peoplefinder_search_log',
          'entity' => 'SearchLog',
          'bao' => 'CRM_PeopleFinder_BAO_SearchLog',
          'localizable' => 0,
        ],
        'user_id' => [
          'name' => 'user_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('User ID'),
          'description' => E::ts('FK to peoplefinder_user'),
          'required' => TRUE,
          'table_name' => 'civicrm_peoplefinder_search_log',
          'entity' => 'SearchLog',
          'bao' => 'CRM_PeopleFinder_BAO_SearchLog',
          'localizable' => 0,
          'FKClassName' => 'CRM_PeopleFinder_DAO_PeopleFinderUser',
        ],
        'search_params' => [
          'name' => 'search_params',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Search Parameters'),
          'table_name' => 'civicrm_peoplefinder_search_log',
          'entity' => 'SearchLog',
          'bao' => 'CRM_PeopleFinder_BAO_SearchLog',
          'localizable' => 0,
        ],
        'results_count' => [
          'name' => 'results_count',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Results Count'),
          'default' => 0,
          'table_name' => 'civicrm_peoplefinder_search_log',
          'entity' => 'SearchLog',
          'bao' => 'CRM_PeopleFinder_BAO_SearchLog',
          'localizable' => 0,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'table_name' => 'civicrm_peoplefinder_search_log',
          'entity' => 'SearchLog',
          'bao' => 'CRM_PeopleFinder_BAO_SearchLog',
          'localizable' => '0',
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }
  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }
  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'peoplefinder_search_log', $prefix, []);
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   *
   * @return array
   *   An array of fields.
   */
  static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'peoplefinder_search_log', $prefix, []);
    return $r;
  }
}

