<?php

// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

class CRM_Jentitylink_Util {
  public static function getEntityLinks($op, $objectName, $objectID) {
    
    $jentitylinkOps = \Civi\Api4\JentitylinkOp::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('op', '=', $op)
      ->addChain('jentitylink', \Civi\Api4\Jentitylink::get()
        ->setCheckPermissions(FALSE)
        ->addWhere('id', '=', '$jentitylink_id')
        ->addWhere('entity_name', '=', $objectName),
      0)
      ->execute();
    foreach ($fixme as $fixme) {
      
    }
  }
}