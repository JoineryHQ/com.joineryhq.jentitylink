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

  public static function buildInspectionLink($op, $objectName, $objectID, $links, $mask, $values) {
    $args = [
      'op' => $op,
      'objectName' => $objectName,
      'objectID' => $objectID
    ];
    $link = [
      'name' => __METHOD__,
      'url' => "#" . http_build_query($args),
      'title' => 'Click for pop-up details',
      'class' => 'jentitylink-inspection-link',
    ];    
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.jentitylink', 'js/inspectionLink.js');
    CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.jentitylink', 'css/inspectionLink.css');
    return $link;
  }
}