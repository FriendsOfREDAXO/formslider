<?php

echo rex_view::title(rex_i18n::msg('formslider_title'));

$yform = $this->getProperty('yform', []);
$yform = $yform[rex_be_controller::getCurrentPage()] ?? [];

$dataset = rex_yform_manager_table::getAll();
$searchword = 'formslider';
$tables = array_filter($dataset, function($var) use ($searchword) { return str_contains($var["table_name"], $searchword); });

echo '<table class="table">';
foreach ($tables as $table) {
  echo '<tr>';
  echo '<td>' . $table
  ->getName() . '</td>';
  echo '<td align="right">';
  // echo '<a href="'.rex_url::currentBackendPage(['table_name' => $table->getTableName()]).'">Edit</a>';
  echo '<a href="index.php?page=yform/manager/table_field&table_name='. $table->getTableName() .'">Felder editieren</a>';
  echo '</td>';
  echo '</tr>';
}
echo '</table>';
