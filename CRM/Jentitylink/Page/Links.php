<?php

/**
 * Description of Links
 *
 * @author as
 */
use CRM_Jentitylink_ExtensionUtil as E;

class CRM_Jentitylink_Page_Links extends CRM_Core_Page_Basic {

  /**
   * @inheritDoc
   * @var bool
   */
  public $useLivePageJS = TRUE;

  /**
   * @inheritDoc
   * @var string
   */
  public static $_links = NULL;

  /**
   * @inheritDoc
   * @var string
   */
  public function getBAOName() {
    return 'CRM_Jentitylink_BAO_Jentitylink';
  }

  /**
   * @inheritDoc
   */
  public function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        (CRM_Core_Action::UPDATE) => array(
          'name' => E::ts('Edit'),
          'url' => 'civicrm/admin/jentitylink/manage/links/',
          'qs' => 'action=update&id=%%id%%&reset=1',
          'title' => E::ts('Edit Entity Link'),
        ),
        (CRM_Core_Action::DELETE) => array(
          'name' => E::ts('Delete'),
          'url' => 'civicrm/admin/jentitylink/manage/links/',
          'qs' => 'action=delete&id=%%id%%',
          'title' => E::ts('Delete Entity Link'),
        ),
      );
    }
    return self::$_links;
  }

  /**
   * @inheritDoc
   */
  public function run() {
    return parent::run();
  }

  /**
   * @inheritDoc
   */
  public function browse() {
    $jsVars = [];
    $userCid = CRM_Core_Session::singleton()->getLoggedInContactID();
    $setInspector = CRM_Utils_Request::retrieveValue('setInspector', 'Int');
    if (isset($setInspector)) {
      // We have a request to update the inpector setting. But we won't bother
      // unless it's different from the current setting.
      $initialSettings = \Civi\Api4\Setting::get()
        ->addSelect('jentitylink_enable_inspector')
        ->setContactId($userCid)
        ->execute();
      $initialInspectorStatus = $initialSettings[0]['value'];

      $newInspectorStatus = (bool)$setInspector;
      if ($newInspectorStatus != $initialInspectorStatus) {
        $results = \Civi\Api4\Setting::set()
          ->addValue('jentitylink_enable_inspector', $newInspectorStatus)
          ->setContactId($userCid)
          ->execute();
        if ($setInspector) {
          // Tell JS so we can highlight the inspector links on this page.
          $jsVars['inspectorEnabledNow'] = TRUE;
        }
      }
    }

    parent::browse();

    $opsByLinkId = [];
    $jentitylinkOps = \Civi\Api4\JentitylinkOp::get()
      ->setCheckPermissions(FALSE)
      ->addOrderBy('op')
      ->execute();
    foreach ($jentitylinkOps as $jentitylinkOp) {
      $opsByLinkId[$jentitylinkOp['jentitylink_id']][] = $jentitylinkOp['op'];
    }
    $this->assign('opsByLinkId', $opsByLinkId);

    $rows = $this->get_template_vars('rows');
    foreach ($rows as &$row) {
      $row['entity_type'] = CRM_Jentitylink_Util::arrayExplodePaddedTrim($row['entity_type']);
    }
    ksort($rows);
    $this->assign('rows', $rows);
    $this->assign('entity_type_options', CRM_Jentitylink_Util::getEntityTypeOptions());
    $this->assign('entity_name_options', CRM_Jentitylink_Linkbuilder::getSupportedEntityNames());


    // Add extra css and js for Context Inspector setting.
    $settings = \Civi\Api4\Setting::get()
      ->addSelect('jentitylink_enable_inspector')
      ->setContactId($userCid)
      ->execute();
    $inspectorStatus = $settings[0]['value'];
    if ($inspectorStatus) {
      $inspectorButtonLabel = E::ts('Disable Context Inspector');
      $inspectorButtonIcon = 'ban';
      $inspectorButtonSetValue = 0;
      $inspectorStatusLabel = E::ts('On');
      $inspectorStatusLabelClass = E::ts('status-1');
    }
    else {
      $inspectorButtonLabel = E::ts('Enable Context Inspector');
      $inspectorButtonIcon = 'bolt';
      $inspectorButtonSetValue = 1;
      $inspectorStatusLabel = E::ts('Off');
      $inspectorStatusLabelClass = E::ts('status-0');
    }
    $this->assign('inspectorButtonLabel', $inspectorButtonLabel);
    $this->assign('inspectorButtonIcon', $inspectorButtonIcon);
    $this->assign('inspectorButtonSetValue', $inspectorButtonSetValue);
    $this->assign('inspectorStatusLabel', $inspectorStatusLabel);
    $this->assign('inspectorStatusLabelClass', $inspectorStatusLabelClass);

    $inspectorStatus = Civi::contactSettings($userCid)->get('jentitylink_enable_inspector');
    if ($inspectorStatus) {
      $this->assign('jentitylink_inspector_set_value_checked', 'checked');
    }
    CRM_Core_Resources::singleton()->addVars('jentitylink', $jsVars);
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.jentitylink', 'js/CRM_Jentitylink_Page_Links.js');
    CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.jentitylink', 'css/CRM_Jentitylink_Page_Links.css');

  }

  /**
   * @inheritDoc
   */
  public function editForm() {
    return 'CRM_Jentitylink_Form_Link';
  }

  /**
   * @inheritDoc
   */
  public function editName() {
    return E::ts('Entity Link');
  }

  /**
   * @inheritDoc
   */
  public function userContext($mode = NULL) {
    return 'civicrm/admin/jentitylink/manage/links';
  }

}
