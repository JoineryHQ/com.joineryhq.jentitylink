<?php

// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

class CRM_Jentitylink_Util {

  public static function getEntityTypeOptions() {
    $contactTypeOptions = [];
    $contactTypes = CRM_Contact_BAO_ContactType::contactTypeInfo();
    foreach ($contactTypes as $contactType) {
      $name = $contactType['name'];
      $label = $contactType['label'];
      $key = NULL;
      if ($parent = $contactType['parent']) {
        $key = "{$parent}__{$name}";
        $contactTypeOptions[$key] = $contactTypes[$parent]['label'] . ": " . $label;
      }
      else {
        $contactTypeOptions[$name] = $label . ' (contact has no sub-type)';
      }
    }
    asort($contactTypeOptions);
    return $contactTypeOptions;
  }

  public static function arrayExplodePaddedTrim($paddedString) {
    $trimmed = trim($paddedString, CRM_Core_DAO::VALUE_SEPARATOR);
    return CRM_Utils_Array::explodePadded($trimmed);
  }

  public static function buildInspectionLink($op, $objectName, $objectID, $links) {
    static $inspectorStatus;
    if (!isset($inspectorStatus)) {
      $userCid = CRM_Core_Session::singleton()->getLoggedInContactID();
      $inspectorStatus = (bool) Civi::contactSettings($userCid)->get('jentitylink_enable_inspector');
    }
    if ($inspectorStatus) {
      $args = [
        'op' => $op,
        'objectName' => $objectName,
        'objectID' => $objectID
      ];
      $link = [
        'name' => 'Context Inspector',
        'url' => "/civicrm/admin/jentitylink/link/info?" . http_build_query($args),
        'title' => 'Click for pop-up details',
        'class' => 'jentitylink-inspection-link crm-popup',
        'weight' => 100000,
      ];
      CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.jentitylink', 'js/inspectionLink.js');
      CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.jentitylink', 'css/inspectionLink.css');
      return $link;
    }
    else {
      return NULL;
    }
  }

  public static function renameLinkParams($params, $toHookType) {
    $ret = [];
    if ($toHookType == 'hook_civicrm_summaryActions') {
      $ret = [
        'title' => $params['name'],
        'weight' => $params['weight'],
        'href' => $params['url'],
        'class' => $params['class'],
      ];
      return $ret;
    }

  }

}