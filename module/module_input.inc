<?php

$searchword = 'formslider';
$dataset = rex_yform_manager_table::getAll();

$tables = array_filter($dataset, function($var) use ($searchword) { return str_contains($var["table_name"], $searchword); });
$templates = rex_sql::factory()->getArray('SELECT id, name FROM ' . rex::getTable('template') . ' WHERE name LIKE "%formslider%" OR name LIKE "%pdf%" ORDER BY name');

$sel = "REX_VALUE[19]";
$row_pad = 2;
$pdf_display   = ($sel=='' || $sel==0 || $sel==1 || $sel==2) ?' style="display:none"':'';
$mail_display = ($sel=='' || $sel==0 || $sel==3) ?' style="display:none"':'';
?>


<div id="formslider-builder">
  <fieldset class="form-horizontal">
    <legend>Formular</legend>
    <div class="form-group">
      <label class="text-left col-md-3 control-label">Tabelle / Formular</label>
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
      <label class="text-left col-md-3 control-label">yTemplate</label>
      <div class="col-md-9">
        <select class="form-control" name="REX_INPUT_VALUE[2]">
          <option value="bootstrap"<?= "REX_VALUE[2]" == 'bootstrap' ? ' selected' : '' ?>>bootstrap</option>
          <option value="uikit"<?= "REX_VALUE[2]" == 'uikit' ? ' selected' : '' ?>>UI Kit</option>
        </select>
        <p class="help-block">Bei Auswahl UI Kit wird bootstrap als Fallback genutzt für nicht vorhandene Templates.</p>
      </div>
    </div>
    <div class="form-group">
      <label class="text-left col-md-3 control-label">Wrapper Class (optional)</label>
      <div class="col-md-9">
        <input class="form-control" type="text" name="REX_INPUT_VALUE[20]" value="REX_VALUE[20]">
        <p class="help-block">CSS-Klasse außerhalb des Formulars.</p>
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-3 control-label">Meldung bei erfolgreichen Versand</label>
      <div class="col-md-9">
        <label class="radio-inline">
          <input type="radio" name="REX_INPUT_VALUE[11]" value="0"<?php if("REX_VALUE[11]" == '0') echo ' checked'; ?> /> Plaintext
        </label>
        <label class="radio-inline">
          <input type="radio" name="REX_INPUT_VALUE[11]" value="1"<?php if("REX_VALUE[11]" == '1') echo ' checked'; ?> /> HTML
        </label>
      </div>
      <div class="col-md-offset-3 col-md-9">
        <textarea class="form-control" name="REX_INPUT_VALUE[6]" rows="<?php echo (count(explode("\r",'REX_VALUE[6]'))+$row_pad);?>">REX_VALUE[6]</textarea>
      </div>
    </div>
  </fieldset>

  <fieldset class="form-horizontal">
    <legend>Vordefinierte Aktionen</legend>

    <div class="form-group">
      <label class="col-md-3 control-label">Bei Submit</label>
      <div class="col-md-9">
        <div class="yform-select-style">
          <select class="form-control" id="formslider-js-action-select" name="REX_INPUT_VALUE[19]" size="1">
            <option value=""<?php if("REX_VALUE[19]" == "")  echo " selected "; ?>>Nichts machen (actions im Formular definieren)</option>
            <option value="0"<?php if("REX_VALUE[19]" == "0") echo " selected "; ?>>Nur in Datenbank speichern</option>
            <option value="1"<?php if("REX_VALUE[19]" == "1") echo " selected "; ?>>Nur E-Mail versenden</option>
            <option value="2"<?php if("REX_VALUE[19]" == "2") echo " selected "; ?>>E-Mail versenden und in Datenbank speichern</option>
            <option value="3"<?php if("REX_VALUE[19]" == "3") echo " selected "; ?>>PDF generieren</option>
            <option value="4"<?php if("REX_VALUE[19]" == "4") echo " selected "; ?>>PDF generieren und E-Mail versenden</option>
            <option value="5"<?php if("REX_VALUE[19]" == "5") echo " selected "; ?>>PDF generieren, E-Mail versenden und in Datenbank speichern</option>
          </select>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset class="form-horizontal" id="formslider-js-mail-fieldset"<?php echo $mail_display;?> >
    <legend>E-Mail-Versand:</legend>

    <div class="form-group">
      <label class="col-md-2 control-label">Absender</label>
      <div class="col-md-10">
        <input class="form-control" type="text" name="REX_INPUT_VALUE[14]" value="REX_VALUE[14]" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-2 control-label">Empfänger</label>
      <div class="col-md-10">
        <input class="form-control" type="text" name="REX_INPUT_VALUE[15]" value="REX_VALUE[15]" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-2 control-label">Subject</label>
      <div class="col-md-10">
        <input class="form-control" type="text" name="REX_INPUT_VALUE[16]" value="REX_VALUE[16]" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-md-2 control-label">Mailbody</label>
      <div class="col-md-10">
        <textarea class="form-control" id="formslider-js-mail-body" name="REX_INPUT_VALUE[17]" rows="<?php echo (count(explode("\r",'REX_VALUE[17]'))+$row_pad);?>">REX_VALUE[17]</textarea>
      </div>
    </div>

  </fieldset>

  <fieldset class="form-horizontal" id="formslider-js-pdf-fieldset"<?php echo $pdf_display;?> >
    <legend>PDF Template:</legend>

    <div class="form-group">
      <label class="text-left col-md-3 control-label">PDF Template</label>
      <div class="col-md-9">
        <select class="form-control" name="REX_INPUT_VALUE[3]">
          <option disabled selected>- Bitte wählen -</option>
          <?php
          foreach ($templates as $template) {
            echo '<option value="' . $template['id'] . '"'. ("REX_VALUE[3]" == $template['id'] ? ' selected' : '') .'>' . $template['name'] . '</option>'.PHP_EOL;
          }
          ?>
        </select>
      </div>
    </div>
  </fieldset>
</div>

<p id="yform-formslider-info" class="text-right border-top small"><?=  rex_addon::get('formslider')->getName() . ' ' . rex_addon::get('formslider')->getVersion() ?></p>

<script type="text/javascript" nonce="<?php echo rex_response::getNonce(); ?>">
  (function($){
    // TOGGLE MAIL/PDF PANELS
    $('#formslider-js-action-select').change(function(){
      switch($(this).val()){
        case '':
          $('#formslider-js-pdf-fieldset').hide(0);
          $('#formslider-js-mail-fieldset').hide(0);
          break;
        case '1':
        case '2':
          $('#formslider-js-pdf-fieldset').hide(0);
          $('#formslider-js-mail-fieldset').show(0);
          break;
        case '0':
          $('#formslider-js-pdf-fieldset').hide(0);
          $('#formslider-js-mail-fieldset').hide(0);
          break;
        case '3':
          $('#formslider-js-pdf-fieldset').show(0);
          $('#formslider-js-mail-fieldset').hide(0);
          break;
        case '4':
        case '5':
          $('#formslider-js-pdf-fieldset').show(0);
          $('#formslider-js-mail-fieldset').show(0);
          break;
      }
    });

  })(jQuery)
</script>