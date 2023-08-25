<?php
use CRM_Jentitylink_ExtensionUtil as E;

class CRM_Jentitylink_Page_LinkInfo extends CRM_Core_Page {

  public function run() {
    $displayValues = [
      'op' => E::ts('Context'),
      'objectName' => E::ts('Entity'),
      'objectID' => E::ts('Entity ID'),
    ];
    $rows = [];
    foreach ($displayValues as $key => $label) {
      $rows[] = [
        'key' => $key,
        'label' => $label,
        'value' => CRM_Utils_Request::retrieveValue($key, 'String', NULL, 'GET'),
      ];
    }
    $this->assign('rows', $rows);
      
    CRM_Utils_System::crmURL($params);
    parent::run();
  }

}
