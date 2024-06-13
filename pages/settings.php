<?php

echo rex_view::title(rex_i18n::msg('formslider_title'));

if (rex::getUser()->isAdmin() && rex_addon::get('structure')->isAvailable()) {
  $content = '';
  $searchtext = 'module:formslider_basic_output';

  $gm = rex_sql::factory();
  $gm->setQuery('select * from ' . rex::getTable('module') . ' where output LIKE "%' . $searchtext . '%"');

  $module_id = 0;
  $module_name = '';
  foreach ($gm->getArray() as $module) {
      $module_id = $module['id'];
      $module_name = $module['name'];
  }

  $formslider_module_name = 'Formslider Ausgabe';

  if (1 == rex_request('install', 'integer')) {
      $input = rex_file::get(rex_path::addon('formslider', 'module/module_input.inc'));
      $output = rex_file::get(rex_path::addon('formslider', 'module/module_output.inc'));

      $mi = rex_sql::factory();
      // $mi->debugsql = 1;
      $mi->setTable(rex::getTable('module'));
      $mi->setValue('input', $input);
      $mi->setValue('output', $output);

      if ($module_id == rex_request('module_id', 'integer', -1)) {
          $mi->setWhere('id="' . $module_id . '"');
          $mi->update();
          echo rex_view::success('Modul "' . $module_name . '" wurde aktualisiert');
      } else {
          $mi->setValue('name', $formslider_module_name);
          $mi->insert();
          $module_id = (int) $mi->getLastId();
          $module_name = $formslider_module_name;
          echo rex_view::success('Formslider Modul wurde angelegt unter "' . $formslider_module_name . '"');
      }
  }

  $content .= '<p>' . $this->i18n('install_module_description') . '</p>';

  if ($module_id > 0) {
      $content .= '<p><a class="btn btn-primary" href="index.php?page=formslider/settings&amp;install=1&amp;module_id=' . $module_id . '" class="rex-button">' . $this->i18n('install_update_module', rex_escape((string) $module_name)) . '</a></p>';
  } else {
      $content .= '<p><a class="btn btn-primary" href="index.php?page=formslider/settings&amp;install=1" class="rex-button">' . $this->i18n('install_formslider_module', $formslider_module_name) . '</a></p>';
  }

  $fragment = new rex_fragment();
  $fragment->setVar('title', $this->i18n('install_module'), false);
  $fragment->setVar('body', $content, false);
  echo $fragment->parse('core/page/section.php');
}

$addon = rex_addon::get('formslider');

$form = rex_config_form::factory($addon->getName());

$field = $form->addInputField('text', 'mytextfield', null, ['class' => 'form-control']);
$field->setLabel(rex_i18n::msg('formslider_config_mytextfield_label'));
$field->setNotice(rex_i18n::msg('formslider_config_mytextfield_notice'));

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', $addon->i18n('formslider_config'), false);
$fragment->setVar('body', $form->get(), false);
echo $fragment->parse('core/page/section.php');
