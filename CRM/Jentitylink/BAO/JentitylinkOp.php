<?php
// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

class CRM_Jentitylink_BAO_JentitylinkOp extends CRM_Jentitylink_DAO_JentitylinkOp {

  /**
   * Create a new JentitylinkOp based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Jentitylink_DAO_JentitylinkOp|NULL
   */
  /*
  public static function create($params) {
    $className = 'CRM_Jentitylink_DAO_JentitylinkOp';
    $entityName = 'JentitylinkOp';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  */

}
