<?php

return [
  'jentitylink_enable_inspector' => [
    'name' => 'jentitylink_enable_inspector',
    'type' => 'Boolean',
    'default' => FALSE,
    'html_type' => 'radio',
    'add' => '5.58',
    'title' => ts('Enable the on-page Context Inspector for this contact?'),
    'is_domain' => 0,
    'is_contact' => 1,
    'description' => ts('If enabled, the Entity Navigation Links extension will automatically insert helpful links at all locations where properly configured links could be inserted.'),
  ],
];
