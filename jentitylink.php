<?php

require_once 'jentitylink.civix.php';
// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_links().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_links/
 */
function jentitylink_civicrm_links(string $op, string $objectName, $objectID, array &$links, int &$mask = NULL, array &$values): void {
  static $linkBuilders = [];
  $key = "$op|$objectName";
  if (!isset($linkBuilders[$key])) {
    $linkBuilders[$key] = new CRM_Jentitylink_Linkbuilder($op, $objectName);
  }
  $newLinks = $linkBuilders[$key]->getEntityLinks($objectID);
  $links = array_merge($links, $newLinks);
}

/**
 * Implements hook_civicrm_summaryActions().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_summaryActions/
 */
function jentitylink_civicrm_summaryActions(&$links, $objectID): void {
  $objectName = 'Contact';
  static $linkBuilders = [];
  $ops = [
    'entitylink.contact.summary.other',
    'entitylink.contact.summary.main',
  ];
  foreach ($ops as $op) {
    $key = "$op|Contact";
    if (!isset($linkBuilders[$key])) {
      $linkBuilders[$key] = new CRM_Jentitylink_Linkbuilder($op, $objectName);
    }
    $newLinks = $linkBuilders[$key]->getEntityLinks($objectID);
    foreach ($newLinks as &$newLink) {
      $newLink = CRM_Jentitylink_Util::renameLinkParams($newLink, 'hook_civicrm_summaryActions');
    }
    if ($op == 'entitylink.contact.summary.other') {
      $links['otherActions'] = array_merge($links['otherActions'], $newLinks);
    }
    else {
      $links = array_merge($links, $newLinks);
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function jentitylink_civicrm_config(&$config): void {
  _jentitylink_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function jentitylink_civicrm_install(): void {
  _jentitylink_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function jentitylink_civicrm_enable(): void {
  _jentitylink_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function jentitylink_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function jentitylink_civicrm_navigationMenu(&$menu): void {
  _jentitylink_civix_insert_navigation_menu($menu, 'Administer/Customize Data and Screens', [
    'label' => E::ts('Entity Navigation Links'),
    'name' => 'jentitylink_list',
    'url' => 'civicrm/admin/jentitylink/manage/links?reset=1&action=browse',
    'permission' => 'administer CiviCRM',
    'operator' => 'AND',
    'separator' => 0,
  ]);
  _jentitylink_civix_navigationMenu($menu);
}

/**
 * Log CiviCRM API errors to CiviCRM log.
 */
function _jentitylink_log_api_error(CiviCRM_API3_Exception $e, $entity, $action, $contextMessage = NULL, $params) {
  $message = "CiviCRM API Error '{$entity}.{$action}': " . $e->getMessage() . '; ';
  $message .= "API parameters when this error happened: " . json_encode($params) . '; ';
  $bt = debug_backtrace();
  $error_location = "{$bt[1]['file']}::{$bt[1]['line']}";
  $message .= "Error API called from: $error_location";
  CRM_Core_Error::debug_log_message($message);

  $jentitylinkLogMessage = $message;
  if ($contextMessage) {
    $jentitylinkLogMessage .= "; Context: $contextMessage";
  }
}

/**
 * CiviCRM API wrapper. Wraps with try/catch, redirects errors to log, saves
 * typing.
 *
 * @param string $entity as in civicrm_api3($ENTITY, ..., ...)
 * @param string $action as in civicrm_api3(..., $ACTION, ...)
 * @param array $params as in civicrm_api3(..., ..., $PARAMS)
 * @param string $contextMessage Additional message for inclusion in log upon any failures.
 * @param bool $silence_errors If TRUE, throw any exceptions we catch; otherwise don't.
 *
 * @return Array result of civicrm_api3()
 * @throws CiviCRM_API3_Exception
 */
function _jentitylink_civicrmapi($entity, $action, $params, $contextMessage = NULL, $silence_errors = FALSE) {
  try {
    $result = civicrm_api3($entity, $action, $params);
  }
  catch (CiviCRM_API3_Exception $e) {
    _jentitylink_log_api_error($e, $entity, $action, $contextMessage, $params);
    if (!$silence_errors) {
      throw $e;
    }
  }

  return $result;
}