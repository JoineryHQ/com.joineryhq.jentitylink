<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from com.joineryhq.jentitylink/xml/schema/CRM/Jentitylink/jentitylink.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:4c8b62ba6ab26d1dc0db748ad81b8c29)
 */
use CRM_Jentitylink_ExtensionUtil as E;

/**
 * Database access object for the Jentitylink entity.
 */
class CRM_Jentitylink_DAO_Jentitylink extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_jentitylink';

  /**
   * Field to show when displaying a record.
   *
   * @var string
   */
  public static $_labelField = 'title';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Unique Jentitylink ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * e.g. "Contact"
   *
   * @var string|null
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $entity_name;

  /**
   * e.g. "Individual"; stores packed arrays delimited with CRM_Core_DAO::VALUE_SEPARATOR
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $entity_type;

  /**
   * require this permisison to display the link
   *
   * @var string|null
   *   (SQL type: varchar(128))
   *   Note that values will be retrieved from the database as a string.
   */
  public $permission;

  /**
   * user-visible link text
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * link title attribute
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $title;

  /**
   * link url
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $url;

  /**
   * link class attribute
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $class;

  /**
   * link weight/order
   *
   * @var int|string
   *   (SQL type: int)
   *   Note that values will be retrieved from the database as a string.
   */
  public $weight;

  /**
   * is this link active
   *
   * @var bool|string
   *   (SQL type: tinyint)
   *   Note that values will be retrieved from the database as a string.
   */
  public $is_active;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_jentitylink';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Jentitylinks') : E::ts('Jentitylink');
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('ID'),
          'description' => E::ts('Unique Jentitylink ID'),
          'required' => TRUE,
          'where' => 'civicrm_jentitylink.id',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'entity_name' => [
          'name' => 'entity_name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Entity Name'),
          'description' => E::ts('e.g. "Contact"'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_jentitylink.entity_name',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'entity_type' => [
          'name' => 'entity_type',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Entity Type'),
          'description' => E::ts('e.g. "Individual"; stores packed arrays delimited with CRM_Core_DAO::VALUE_SEPARATOR'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.entity_type',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'permission' => [
          'name' => 'permission',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Permission'),
          'description' => E::ts('require this permisison to display the link'),
          'maxlength' => 128,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.permission',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('user-visible link text'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.name',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'description' => E::ts('link title attribute'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.title',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'url' => [
          'name' => 'url',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Url'),
          'description' => E::ts('link url'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.url',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'class' => [
          'name' => 'class',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Class'),
          'description' => E::ts('link class attribute'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_jentitylink.class',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Weight'),
          'description' => E::ts('link weight/order'),
          'required' => TRUE,
          'where' => 'civicrm_jentitylink.weight',
          'default' => '0',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Enabled'),
          'description' => E::ts('is this link active'),
          'required' => TRUE,
          'where' => 'civicrm_jentitylink.is_active',
          'default' => '1',
          'table_name' => 'civicrm_jentitylink',
          'entity' => 'Jentitylink',
          'bao' => 'CRM_Jentitylink_DAO_Jentitylink',
          'localizable' => 0,
          'add' => NULL,
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
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'jentitylink', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'jentitylink', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
