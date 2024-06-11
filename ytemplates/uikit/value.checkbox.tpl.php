<?php

/**
 * @var rex_yform_value_checkbox $this
 * @psalm-scope-this rex_yform_value_checkbox
 */

$value ??= $this->getValue() ?? '';

$notices = [];
if ('' != $this->getElement('notice')) {
    $notices[] = rex_i18n::translate($this->getElement('notice'), false);
}
if (isset($this->params['warning_messages'][$this->getId()]) && !$this->params['hide_field_warning_messages']) {
    $notices[] = '<span class="text-warning">' . rex_i18n::translate($this->params['warning_messages'][$this->getId()], false) . '</span>'; //    var_dump();
}

$notice = '';
if (count($notices) > 0) {
    $notice = '<p class="help-block uk-form-small">' . implode('<br />', $notices) . '</p>';
}

$class_group = trim('checkbox uk-margin ' . $this->getHTMLClass() . ' ' . $this->getWarningClass());

$attributes = [
    'type' => 'checkbox',
    'id' => $this->getFieldId(),
    'name' => $this->getFieldName(),
    'value' => 1,
];
if (1 == $value) {
    $attributes['checked'] = 'checked';
}

$attributes = $this->getAttributeElements($attributes, ['required', 'disabled', 'autofocus']);

?>
<div class="<?= $class_group ?>" id="<?= $this->getHTMLId() ?>">
<label>
    <span class="label"><?= $this->getLabel() ?></span>
    <input <?= implode(' ', $attributes) ?> />
    <i class="form-helper"></i><br>
    <?php if(strpos(implode(' ', $attributes), 'role="switch"')) { ?>
    <span class="state">
        <span class="wrapper">
            <span class="position">
            </span>
        </span>
        <span class="on" aria-hidden="true">
            Ja
        </span>
        <span class="off" aria-hidden="true">
            Nein
        </span>
    </span>
    <?php } ?>
</label>
<?= $notice ?>
</div>
