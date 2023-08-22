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
  var $supportedEntityTypes = [
    'contact',
  ];

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
      // If user doesn't have permission, skip this link.
      if (!CRM_Core_Permission::check([$link['permission']])) {
        continue;
      }
      // Merge url and qs properties into one. Unfortunately, CiviCRM core seems
      // to either qs or not, variously, depending on context. To avoid inconsistency,
      // we just merge qs into url here.
      $link['url'] .= "?{$link['qs']}";
      unset($link['qs']);

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
        'entity_sub_type',
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
      if (
        !empty($link['entity_type'])
        || !empty($link['entity_sub_type'])
      ) {
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
    if (!$this->isObjectNameSupported()) {
      // Not all entity types are supported.
      return $entityLinks;
    }

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
        if (
          !empty($linkMeta['entity_type'])
          && ($linkMeta['entity_type'] != $entity['contact_type'])
        ) {
          // Entity does not match link entity type, so skip this link.
          continue;
        }
        if (
          !empty($linkMeta['entity_sub_type'])
          && (!in_array($linkMeta['entity_sub_type'], $entity['contact_sub_type'] ?? array()))
        ) {
          // Entity does not match link entity sub-type, so skip this link.
          continue;
        }
        // Make a copy of the link array, so we can modify it per-contact.
        $link = $this->links[$linkMetaId];
        if ($linkMeta['is_eid_replace']) {
          // Modify the link url and qs by replacing '%%eid%%' with the entity id.
          // Unfortunately, CiviCRM core seems to handle %% repladements
          // differently in different contexts, so we just do our own replacement here.
          $replaceValues = ['eid' => $objectID];
          CRM_Core_Action::replace($link['url'], $replaceValues);
        }
        $entityLinks[] = $link;
      }
    }
    return $entityLinks;
  }

  /**
   * Check whether this entity type is supported.
   *
   * @return Bool
   */
  public function isObjectNameSupported() {
    return (bool) in_array($this->objectName, $this->supportedEntityTypes);
  }

}