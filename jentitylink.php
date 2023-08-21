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
function jentitylink_civicrm_links(string $op, string $objectName, int $objectID, array &$links, int &$mask = NULL, array &$values): void {
  $entityLinks = CRM_Jentitylink_Util::getEntityLinks($op, $objectName, $objectID);
  $props = [
    'entityName' => 'Contact',
    'entityType' => 'Individual',
    'entitySubType' => 'Client',
    'op' => [
      'view.contact.activity',
      'contact.selector.row',
    ],
    'permission' => '',
    'name' => 'View FaceSheet',
    'title' => '',
    'url' => 'civicrm/view/facesheet/[cid]',
    'qs' => '',
    'class' => 'no-popup',
    'weight' => '10',
  ];
  if (1 || $op == 'view.contact.activity') {
    $links[] = [
      'name' => E::ts('My Module Actions'),
      'url' => 'mymodule/civicrm/actions/203',
      'title' => 'New Thing',
      'class' => 'no-popup',
      'weight' => -1,
      'qs' => 'id=%%myObjectID%%',
    ];
  $values['myObjectID'] = $objectID;
  }
  $a = 1; 
  
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
//function jentitylink_civicrm_navigationMenu(&$menu): void {
//  _jentitylink_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _jentitylink_civix_navigationMenu($menu);
//}
