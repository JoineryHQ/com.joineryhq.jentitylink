<?php
// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

class CRM_Jentitylink_BAO_Jentitylink extends CRM_Jentitylink_DAO_Jentitylink {

  /**
   * Create a new Jentitylink based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Jentitylink_DAO_Jentitylink|NULL
   */
  /*
  public static function create($params) {
    $className = 'CRM_Jentitylink_DAO_Jentitylink';
    $entityName = 'Jentitylink';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  */

  /**
   * Fetch object based on array of properties.
   *
   * @param array $params
   *   (reference ) an assoc array of name/value pairs.
   * @param array $defaults
   *   (reference ) an assoc array to hold the flattened values.
   *
   * @return CRM_Core_BAO_OptionGroup
   */
  public static function retrieve(&$params, &$defaults) {
    $daoClassName = get_parent_class();
    $jentitylink = new $daoClassName();
    $jentitylink->copyValues($params);
    if ($jentitylink->find(TRUE)) {
      CRM_Core_DAO::storeValues($jentitylink, $defaults);
      return $jentitylink;
    }
    return NULL;
  }  

}
