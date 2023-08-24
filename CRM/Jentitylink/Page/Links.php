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
    // Note on array keys:  for the following reasons, we're using non-existent
    // values of CRM_Core_Action::[constants] for array keys:
    //   * We want all the goodies that come with the link-building code invoked
    //     by parent::browse(). The alternative is hard-coding in the template or
    //     some other klunky thing, but we lose soem of those goodies.
    //   * Anyone who can access this page should have all the actions, so the
    //     one thing we don't need is permissions handling.
    //   * The link-building code invoked by parent::browse() expects a limited
    //     number of possible array keys, such as CRM_Core_Action::DELETE. We're
    //     using a couple of those, but also using some of our own. We could abuse
    //     some rately used ones like CRM_Core_Action::FOLLOWUP, but that becomes
    //     nonsensical after doing it once or twice.  So for these actions of our
    //     own, we need to create some new array key.
    //   * Any key with a value of CRM_Core_Action::MAX_ACTION or greater will
    //     cause some other keys to go missing (namely CRM_Core_Action::DELETE),
    //     so once we start down this path for one action, we need to stick to
    //     it for the rest of them.
    //   * No negative side-effects of this approach were found in development.
    //     If they arise, we should re-think this.
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
