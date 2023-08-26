<?php

use CRM_Jentitylink_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Jentitylink_Form_Link extends CRM_Admin_Form {

  /**
   * Explicitly declare the entity api name.
   */
  public function getDefaultEntity() {
    return 'Jentitylink';
  }

  public function buildQuickForm() {
    parent::buildQuickForm();
    if ($this->_action & CRM_Core_Action::DELETE) {
      CRM_Utils_System::setTitle('Delete Entity Navigation Link');
    }
    elseif ($this->_action & CRM_Core_Action::UPDATE) {
      CRM_Utils_System::setTitle('Edit Entity Navigation Link');
    }
    elseif ($this->_action & CRM_Core_Action::ADD) {
      CRM_Utils_System::setTitle('Create Entity Navigation Link');
    }


    if ($this->_action & CRM_Core_Action::DELETE) {
      $descriptions['delete_warning'] = E::ts('Are you sure you want to delete this link configuration?');
    }
    else {
      $descriptions = [
        'entity_name' => E::ts('Link must be associated with a specific type of entity. Only "Contact" is supported at present.)'),
        'entity_type' => E::ts('If specified, this link will only appear for these types.'),
        'op' => E::ts('Where this link should appear.'),
        'permission' => E::ts('If specified, link is displayed only if user has this permission.'),
        'name' => E::ts('User-visible link text.'),
        'title' => E::ts('Link "title" attribute; most browsers display this like a "tool tip" on hover.'),
        'url' => E::ts('Destination URL, including query string. Typically begins with "civicrm/" but may be an absolute URL. Include the query string, if any. Replacements: the string "%%eid%%" will be replaced with the id of the linked entity.'),
        'class' => E::ts('Link "class" attribute.'),
        'weight' => E::ts('Placement order of this link, relative to others in the given context. Lower wights appear before higher weights.'),
      ];

      $supportedEntityTypes = CRM_Jentitylink_Linkbuilder::getSupportedEntityNames();
      $entityTypeOptions = CRM_Jentitylink_Util::getEntityTypeOptions();

      $this->add(
        // field type
        'select',
        // field name
        'entity_name',
        // field label
        E::ts('Entity'),
        // list of options
        $supportedEntityTypes,
        // is required
        TRUE,
        // attributes
        ['style' => 'width: 30rem;']
      );

      $this->add(
        // field type
        'select',
        // field name
        'entity_type',
        // field label
        E::ts('Limit to entities of type'),
        // list of options
        $entityTypeOptions,
        // is required
        FALSE,
        // attributes
        ['class' => 'crm-select2', 'multiple' => TRUE, 'placeholder' => E::ts('- all type(s) -'), 'style' => 'width: 30rem;']
      );

      $this->add(
        // field type
        'select',
        // field name
        'ops',
        // field label
        E::ts('Context(s)'),
        // list of options
        [
          'contact.selector.actions' => 'contact.selector.actions',
          'view.contact.activity' => 'view.contact.activity',
          'entitylink.contact.summary.main' => 'entitylink.contact.summary.main',
          'entitylink.contact.summary.other' => 'entitylink.contact.summary.other',
        ],
        // is required
        FALSE,
        // attributes
        ['class' => 'crm-select2', 'multiple' => TRUE, 'style' => 'width: 30rem;']
      );

      $getPerms = \Civi\Api4\Permission::get(0)
        ->addWhere('is_active', '=', 1)
        ->addWhere('group', 'IN', ['civicrm', 'cms', 'const'])
        ->setOrderBy(['title' => 'ASC'])
        ->execute();
      $this->add(
        // field type
        'select',
        // field name
        'permission',
        // field label
        E::ts('Required permission'),
        // list of options
        array_combine($getPerms->column('name'), $getPerms->column('title')),
        // is required
        FALSE,
        // attributes
        ['class' => 'crm-select2', 'placeholder' => E::ts('- No restriction -'), 'style' => 'width: 30rem;']

      );

      $this->add(
        // field type
        'text',
        // field name
        'name',
        // field label
        E::ts('Link Text'),
        // attributes
        ['style' => 'width: 30rem;'],
        // is required
        TRUE
      );
      $this->add(
        // field type
        'text',
        // field name
        'title',
        // field label
        E::ts('Link title'),
        // attributes
        ['style' => 'width: 30rem;'],
        // is required
        FALSE
      );
      $this->add(
        // field type
        'text',
        // field name
        'url',
        // field label
        E::ts('URL'),
        // attributes
        ['style' => 'width: 30rem;'],
        // is required
        TRUE
      );
      $this->add(
        // field type
        'text',
        // field name
        'class',
        // field label
        E::ts('CSS Class'),
        // attributes
        ['style' => 'width: 30rem;'],
        // is required
        FALSE
      );
      $this->add(
        // field type
        'number',
        // field name
        'weight',
        // field label
        E::ts('Weight'),
        // attributes
        ['class' => 'four'],
        // is required
        FALSE
      );
    }

    // export form elements
    $this->assign('elementNames', $this->getRenderableElementNames());
    $this->assign('descriptions', $descriptions);
    $this->assign('id', $this->_id);
  }

  /**
   * Set defaults for form.
   *
   * @see CRM_Core_Form::setDefaultValues()
   */
  public function setDefaultValues() {
    if ($this->_id && (!($this->_action & CRM_Core_Action::DELETE))) {
      $result = _jentitylink_civicrmapi('Jentitylink', 'getSingle', array(
        'id' => $this->_id,
      ));
      $defaultValues = $result;

      $apiParams = [
        'jentitylink_id' => $this->_id,
      ];
      $exitingLinkOps = _jentitylink_civicrmapi('JentitylinkOp', 'get', $apiParams);
      foreach ($exitingLinkOps['values'] as $linkOp) {
        $defaultValues['ops'][] = $linkOp['op'];
      }

      return $defaultValues;
    }
  }

  public function postProcess() {
    if ($this->_action & CRM_Core_Action::DELETE) {
      $apiParams['id'] = $this->_id;
      $link = _jentitylink_civicrmapi('Jentitylink', 'delete', $apiParams);
      CRM_Core_Session::setStatus(E::ts('Link has been saved.'), E::ts('Saved'), 'success');
    }
    else {
      // store the submitted values in an array
      $submitted = $this->exportValues();
      $apiParams = $submitted;
      $apiParams['entity_type'] = CRM_Utils_Array::implodePadded($submitted['entity_type']);
      unset($apiParams['ops']);
      if ($this->_action & CRM_Core_Action::UPDATE) {
        $apiParams['id'] = $this->_id;
      }

      $link = _jentitylink_civicrmapi('Jentitylink', 'create', $apiParams);

      // Identify and remove any jentitylinkOp records, because we'll add new ones as submitted.
      if ($this->_id) {
        $apiParams = [
          'jentitylink_id' => $this->_id,
        ];
        $existingLinkOps = _jentitylink_civicrmapi('JentitylinkOp', 'get', $apiParams);
        foreach ($existingLinkOps['values'] as $linkOp) {
          $result = _jentitylink_civicrmapi('JentitylinkOp', 'delete', ['id' => $linkOp['id']]);
        }
      }

      foreach ($submitted['ops'] as $op) {
        $apiParams = [
          'jentitylink_id' => $this->_id,
          'op' => $op,
        ];
        $linkOp = _jentitylink_civicrmapi('JentitylinkOp', 'create', $apiParams);
      }
      CRM_Core_Session::setStatus(E::ts('Link has been saved.'), E::ts('Saved'), 'success');
    }
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
