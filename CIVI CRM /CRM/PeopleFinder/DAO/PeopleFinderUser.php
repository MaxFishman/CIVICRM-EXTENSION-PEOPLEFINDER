<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2019
 */
class CRM_PeopleFinder_DAO_PeopleFinderUser extends CRM_Core_DAO {
  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_peoplefinder_user';
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
   * FK to Contact
   *
   * @var int unsigned
   */
  public $contact_id;
  /**
   * Email address
   *
   * @var string
   */
  public $email;
  /**
   * Verification token
   *
   * @var string
   */
  public $verification_token;
  /**
   * Is email verified
   *
   * @var bool
   */
  public $is_verified;
  /**
   * Invitation token
   *
   * @var string
   */
  public $invitation_token;
  /**
   * Created date
   *
   * @var datetime
   */
  public $created_date;
  /**
   * Last login date
   *
   * @var datetime
   */
  public $last_login;
  /**
   * Class constructor.
   */
  function __construct() {
    $this->__table = 'civicrm_peoplefinder_user';
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
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName() , 'contact_id', 'civicrm_contact', 'id');
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
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'contact_id' => [
          'name' => 'contact_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Contact ID'),
          'description' => E::ts('FK to Contact'),
          'required' => TRUE,
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
        ],
        'email' => [
          'name' => 'email',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Email'),
          'required' => TRUE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'verification_token' => [
          'name' => 'verification_token',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Verification Token'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'is_verified' => [
          'name' => 'is_verified',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is Verified'),
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'invitation_token' => [
          'name' => 'invitation_token',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Invitation Token'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'created_date' => [
          'name' => 'created_date',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Created Date'),
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
          'localizable' => 0,
        ],
        'last_login' => [
          'name' => 'last_login',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Last Login'),
          'table_name' => 'civicrm_peoplefinder_user',
          'entity' => 'PeopleFinderUser',
          'bao' => 'CRM_PeopleFinder_BAO_PeopleFinderUser',
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
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'peoplefinder_user', $prefix, []);
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
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'peoplefinder_user', $prefix, []);
    return $r;
  }
  /**
   * Returns the list of indices
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'index_contact_id' => [
        'name' => 'index_contact_id',
        'field' => [
          0 => 'contact_id',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
      ],
      'index_email' => [
        'name' => 'index_email',
        'field' => [
          0 => 'email',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }
}

