<?php

$dataset = rex_yform_manager_table::getAll();

$searchword = 'formslider';

$tables = array_filter($dataset, function($var) use ($searchword) { return str_contains($var["table_name"], $searchword); });
?>

<div id="formslider-builder">
  <fieldset class="form-horizontal">
    <legend>Formular</legend>
    <div class="form-group">
      <label class="text-left col-md-3 control-label" for="REX_INPUT_VALUE[1]">Tabelle / Formular</label>
      <div class="col-md-9">
        <select class="form-control" name="REX_INPUT_VALUE[1]">
          <option disabled selected>- Bitte wählen -</option>
          <?php
          foreach ($tables as $table) {
            echo '<option value="' . $table->getId() . '"'. ("REX_VALUE[1]" == $table->getId() ? ' selected' : '') .'>' . $table->getNameLocalized() . '</option>'.PHP_EOL;
          }
          ?>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label class="text-left col-md-3 control-label" for="REX_INPUT_VALUE[2]">yTemplate</label>
      <div class="col-md-9">
        <select class="form-control" name="REX_INPUT_VALUE[2]">
          <option value="bootstrap"<?= "REX_VALUE[2]" == 'bootstrap' ? ' selected' : '' ?>>bootstrap</option>
          <option value="uikit"<?= "REX_VALUE[2]" == 'uikit' ? ' selected' : '' ?>>UI Kit</option>
        </select>
        <p class="help-block">Bei Auswahl UI Kit wird bootstrap als Fallback genutzt für nicht vorhandene Templates.</p>
      </div>
    </div>
    <div class="form-group">
      <label class="text-left col-md-3 control-label" for="REX_INPUT_VALUE[20]">Wrapper Class (optional)</label>
      <div class="col-md-9">
        <input class="form-control" type="text" name="REX_INPUT_VALUE[20]" value="REX_VALUE[20]">
        <p class="help-block">CSS-Klasse außerhalb des Formulars.</p>
      </div>
    </div>
  </fieldset>
</div>