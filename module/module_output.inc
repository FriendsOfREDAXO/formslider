<?php
if (rex_yform_manager_table::getById(intval('REX_VALUE[1]'))) {
$table = rex_yform_manager_table::getById(intval('REX_VALUE[1]'));
$tablename = $table->getTableName();

$dataset = rex_yform_manager_dataset::create($tablename);

$ytemplate = 'bootstrap';
if (strpos('REX_VALUE[2]', 'uikit') !== false) {
  $ytemplate = 'uikit, bootstrap';
}

$yform = $dataset->getForm();
$yform->setDebug(TRUE);
$yform->setObjectparams('form_wrap_class', 'formslider');
$yform->setObjectparams('form_name', $tablename);
$yform->setObjectparams('form_class', 'rex_formbuilder');
$yform->setObjectparams('form_showformafterupdate', 0);
$yform->setObjectparams('real_field_names',1);
$yform->setObjectparams('form_ytemplate', $ytemplate);
$yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId()));
$yform->setActionField('callback', array('generatePDF', 'post'));
$yform->setActionField('showtext', array('',"Thank you."));

echo '<div class="REX_VALUE[20] uk-background-default">';
echo $dataset->executeForm($yform);
echo '</div>';
} else {
  if (rex::isBackend()) {
    echo 'Table not found';
  }
}