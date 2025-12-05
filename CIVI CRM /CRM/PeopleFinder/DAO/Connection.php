<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 */
class CRM_PeopleFinder_DAO_Connection extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_peoplefinder_connection';
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
  public $searcher_id;
  /**
   * FK to Contact
   *
   * @var int unsigned
   */
  public $target_contact_id;
  /**
   * Connection status
   *
   * @var string
   */
  public $status;
  /**
   * Message from searcher
   *
   * @var text
   */
  public $message;
  /**
   * Created date
   *
   * @var datetime
   */
  public $created_date;
  /**
   * Response date
   *
   * @var datetime
   */
  public $response_date;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_peoplefinder_connection';
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'searcher_id', 'civicrm_peoplefinder_user', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'target_contact_id', 'civicrm_contact', 'id');
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
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
        ],
        'searcher_id' => [
          'name' => 'searcher_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Searcher ID'),
          'description' => E::ts('FK to peoplefinder_user'),
          'required' => TRUE,
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
          'FKClassName' => 'CRM_PeopleFinder_DAO_PeopleFinderUser',
        ],
        'target_contact_id' => [
          'name' => 'target_contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Target Contact ID'),
          'description' => E::ts('FK to Contact'),
          'required' => TRUE,
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ],
        'status' => [
          'name' => 'status',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Status'),
          'maxlength' => 20,
          'size' => CRM_Utils_Type::MEDIUM,
          'default' => 'pending',
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
        ],
        'message' => [
          'name' => 'message',
          'type' => CRM_Utils_Type::T_TEXT,
          'title' => E::ts('Message'),
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
        ],
        'response_date' => [
          'name' => 'response_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Response Date'),
          'table_name' => 'civicrm_peoplefinder_connection',
          'entity' => 'Connection',
          'bao' => 'CRM_PeopleFinder_BAO_Connection',
          'localizable' => 0,
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'peoplefinder_connection', $prefix, []);
    return $r;
  }
  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   *   An array of fields.
   */
  static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'peoplefinder_connection', $prefix, []);
    return $r;
  }
}

