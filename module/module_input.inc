<?php

$dataset = rex_yform_manager_table::getAll();

$searchword = 'formslider';

$tables = array_filter($dataset, function($var) use ($searchword) { return str_contains($var["table_name"], $searchword); });

echo '<select name="REX_INPUT_VALUE[1]">'.PHP_EOL;
foreach ($tables as $table) {
  echo '<option value="' . $table->getId() . '"'. ("REX_VALUE[1]" == $table->getId() ? ' selected' : '') .'>' . $table->getNameLocalized() . '</option>'.PHP_EOL;
}
echo '</select>'.PHP_EOL;