<?php

// module:formslider_basic_output

if (rex_yform_manager_table::getById(intval('REX_VALUE[1]'))) {
  $table = rex_yform_manager_table::getById(intval('REX_VALUE[1]'));
  $tablename = $table->getTableName();

  $dataset = rex_yform_manager_dataset::create($tablename);

  $yform = $dataset->getForm();
  $yform->setDebug(TRUE);
  $yform->setObjectparams('form_action', rex_getUrl(rex_article::getCurrentId(), REX_CLANG_ID));
  $yform->setObjectparams('form_wrap_class', 'formslider');
  $yform->setObjectparams('form_name', $tablename);
  $yform->setObjectparams('form_class', 'rex_formslider');
  $yform->setObjectparams('real_field_names',1);

  $ytemplate = 'bootstrap';
  if (strpos('REX_VALUE[2]', 'uikit') !== false) {
    $ytemplate = 'uikit, bootstrap';
  }
  $yform->setObjectparams('form_ytemplate', $ytemplate);

  // action - showtext
  if ('REX_VALUE[id=6]' != '') {
    $html = '0'; // plaintext
    if ('REX_VALUE[11]' == 1) {
        $html = '1'; // html
    }

    $e3 = '';
    $e4 = '';
    if ($html == '0') {
        $e3 = '<div class="alert alert-success">';
        $e4 = '</div>';
    }

    $t = str_replace('<br />', '', rex_yform::unhtmlentities('REX_VALUE[6]'));
    $yform->setActionField('showtext', [$t, $e3, $e4, $html]);
  }

  $form_type = 'REX_VALUE[19]';
  $pdf_template_id = 'REX_VALUE[3]';

  // action - pdf
  if(($form_type == 3 || $form_type == '4' || $form_type == '5') && $pdf_template_id != '') {
    $templateId = intval($pdf_template_id); // Setze hier die gewünschte Template-ID ein

    $template = new rex_template($templateId);
    $templateContent = $template->getTemplate();
    $rexVar = $templateContent;
    $yform->setActionField('callback', [static function (rex_yform_action_callback $action) use ($rexVar, $yform) {
      formsliderGeneratePDF($action->params, $rexVar);
    }], 'post');
  }

  // action - email
  if ($form_type == '1' || $form_type == '2' || $form_type == '4' || $form_type == '5') {
    $mail_from = ('REX_VALUE[14]' != '') ? 'REX_VALUE[14]' : rex::getErrorEmail();
    $mail_to = ('REX_VALUE[15]' != '') ? 'REX_VALUE[15]' : rex::getErrorEmail();
    $mail_subject = 'REX_VALUE[16]';
    $mail_body = str_replace('<br />', '', rex_yform::unhtmlentities('REX_VALUE[17]'));
    $yform->setActionField('email', [$mail_from, $mail_to, $mail_subject, $mail_body]);
  }

  // action - db
  if ($form_type == '0' || $form_type == '2' || $form_type == '5') {
    $yform->setObjectparams('main_table', 'REX_VALUE[1]');

    $yform->setActionField('db', ['REX_VALUE[1]', $yform->objparams['main_where']]);
  }

  $yform->setObjectparams('form_showformafterupdate', 0);

  echo '<div class="REX_VALUE[20]">';
  echo $dataset->executeForm($yform);
  echo '</div>';

} else {
  if (rex::isBackend()) {
    echo 'Table not found';
  }
}