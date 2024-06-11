<?php // Boot code

rex_yform::addTemplatePath(rex_path::addon('formslider', 'ytemplates'));

if (rex::isBackend()) {
  rex_extension::register('PACKAGES_INCLUDED', function ($params) {

      $plugin = rex_plugin::get('yform', 'manager');

      if ($plugin) {
          $pages = $plugin->getProperty('pages');
          $dataset = rex_yform_manager_table::getAll();
          $searchword = 'formslider';

          $ycom_tables = array_filter($dataset, function($var) use ($searchword) { return str_contains($var["table_name"], $searchword); });
          // $ycom_tables = ['rex_formslider_awo'];

          if (isset($pages) && is_array($pages)) {
              foreach ($pages as $page) {
                  if (in_array($page->getKey(), $ycom_tables)) {
                      $page->setBlock('formslider');
                  }
              }
          }
      }
  });
}