<?php
if (rex_yform_manager_table::getById(intval('REX_VALUE[1]'))) {
$table = rex_yform_manager_table::getById(intval('REX_VALUE[1]'));
$tablename = $table->getTableName();

$dataset = rex_yform_manager_dataset::create($tablename);
$yform = $dataset->getForm();
$yform->setDebug(TRUE);
$yform->setObjectparams('form_wrap_class', 'formslider');
$yform->setObjectparams('form_name', $tablename);
$yform->setObjectparams('form_class', 'rex_formbuilder');
$yform->setObjectparams('form_showformafterupdate', 0);
$yform->setObjectparams('real_field_names',1);
$yform->setObjectparams('form_ytemplate','uikit, bootstrap');
$yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId()));
$yform->setActionField('callback', array('generatePDF', 'post'));
$yform->setActionField('showtext', array('',"Thank you."));

echo '<div class="uk-container uk-padding-small uk-container-large">';
echo $dataset->executeForm($yform);
echo '</div>';
} else {
  if (rex::isBackend()) {
    echo 'Table not found';
  }
}

