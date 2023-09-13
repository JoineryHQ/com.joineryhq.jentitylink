<?php

// phpcs:disable
use CRM_Jentitylink_ExtensionUtil as E;
// phpcs:enable

class CRM_Jentitylink_Linkbuilder {

  var $needsEntityLoad = FALSE;
  var $op;
  var $objectName;
  var $links = [];
  var $linkMeta = [];

  public function __construct($op, $objectName) {
    $this->op = strtolower($op);
    $this->objectName = strtolower($objectName);

    if (!$this->isObjectNameSupported()) {
      // Not all entity types are supported.
      return;
    }
    $jentitylinkOps = \Civi\Api4\JentitylinkOp::get()
      ->setCheckPermissions(FALSE)
      ->addWhere('op', '=', $op)
      ->addChain('jentitylink', \Civi\Api4\Jentitylink::get()
        ->setCheckPermissions(FALSE)
        ->addWhere('id', '=', '$jentitylink_id')
        ->addWhere('entity_name', '=', $objectName),
      0)
      ->execute();
    foreach ($jentitylinkOps as $jentitylinkOp) {
      $link = $jentitylinkOp['jentitylink'];
      $link['entity_type'] = CRM_Jentitylink_Util::arrayExplodePaddedTrim($link['entity_type']);

      // If user doesn't have permission, skip this link.
      if ($link['permission'] && !CRM_Core_Permission::check([$link['permission']])) {
        continue;
      }

      // Separate link values from link meta.
      $linkKeys = [
        'name',
        'url',
        'title',
        'class',
        'weight',
      ];
      $this->links[$link['id']] = array_intersect_key($link, array_flip($linkKeys));
      $linkMetaKeys = [
        'entity_name',
        'entity_type',
        'permission',
      ];
      $this->linkMeta[$link['id']] = array_intersect_key($link, array_flip($linkMetaKeys));

      // Note whether this link contains replacements for entity ID. This will
      // help with more efficient replacements elsewhere.
      if (
        strpos("{$link['url']}", '%%eid%%') !== FALSE
      ) {
        $this->linkMeta[$link['id']]['is_eid_replace'] = TRUE;
      }
      if (!empty($link['entity_type'])) {
        // Note that we must load each entity to determine whether this link
        // applies or not.
        $this->needsEntityLoad = TRUE;
      }
    }
  }

  /**
   * Among links in this context, get those that apply to a given entity.
   * @param int $objectID
   * @return array Array of links.
   */
  public function getEntityLinks($objectID) {
    $entityLinks = [];
    if ($this->isObjectNameSupported()) {
      // Not all entity types are supported. Those get an empty array.

      if (!$this->needsEntityLoad) {
        // If links don't vary on a per-entity basis, just return all links.
        $entityLinks = $this->links;
      }
      else {
        // If we're here it's because we need to vary the links based on entity
        // properties, so fetch the full entity.
        $entity = \Civi\Api4\Contact::get()
          ->setCheckPermissions(FALSE)
          ->addWhere('id', '=', $objectID)
          ->execute()
          ->first();
        foreach ($this->linkMeta as $linkMetaId => $linkMeta) {
          $includeLink = FALSE;
          foreach ($linkMeta['entity_type'] as $limitEntityType) {
            list($limitContactType, $limitContactSubType) = explode('__', $limitEntityType);
            if ($limitContactSubType && !empty($entity['contact_sub_type']) && in_array($limitContactSubType, $entity['contact_sub_type'])) {
              // If this is a limit with a sub-type (e.g. 'Individual: Student'), and the contact
              // has that sub-type, include the link. (No need to compare on Type if SubType matches,
              // because all Type and SubType names must be unique.)
              $includeLink = TRUE;
            }
            elseif (empty($limitContactSubType) && empty($entity['contact_sub_type']) && ($entity['contact_type'] == $limitContactType)) {
              // If this is a limit WITHOUT a sub-type (e.g. 'Individual'), and the contact
              // has NO sub-type, and the limit matches the contact type, include the link.
              $includeLink = TRUE;
            }
            if ($includeLink) {
              // We have one matching condition, which is enough, so don't bother
              // evaluating the rest.
              $entityLinks[$linkMetaId] = $this->links[$linkMetaId];
              break;
            }
          }
        }
      }
      foreach ($entityLinks as $linkId => &$link) {
        if ($this->linkMeta[$linkId]['is_eid_replace']) {
          // Modify the link url by replacing '%%eid%%' with the entity id.
          // Unfortunately, CiviCRM core seems to handle %% repladements
          // differently in different contexts, so we just do our own replacement here.
          $replaceValues = ['eid' => $objectID];
          CRM_Core_Action::replace($link['url'], $replaceValues);
        }
      }
    }
    // Insert a Context Inspector link (maybe).
    if ($inspectionLink = CRM_Jentitylink_Util::buildInspectionLink($this->op, $this->objectName, $objectID, $this->links)) {
      $entityLinks[] = $inspectionLink;
    }
    return $entityLinks;
  }

  /**
   * Check whether this entity type is supported.
   *
   * @return Bool
   */
  public function isObjectNameSupported() {
    return (bool) array_key_exists($this->objectName, self::getSupportedEntityNames());
  }

  public static function getSupportedEntityNames() {
    return [
      'contact' => E::ts('Contact'),
    ];
  }
}