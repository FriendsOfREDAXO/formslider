<?php

use FriendsOfRedaxo\PdfOut\PdfOut;

rex_yform::addTemplatePath(rex_path::addon('formslider', 'ytemplates'));

if (rex::isBackend()) {
    rex_extension::register('PACKAGES_INCLUDED', static function (): void {
        $yformAddon = rex_addon::get('yform');
        if (!$yformAddon->isAvailable()) {
            return;
        }

        $pages = $yformAddon->getProperty('pages');
        if (!is_array($pages)) {
            return;
        }

        $tableNames = [];
        foreach (rex_yform_manager_table::getAll() as $table) {
            $tableName = $table->getTableName();
            if (str_contains($tableName, 'formslider')) {
                $tableNames[] = $tableName;
            }
        }

        foreach ($pages as $page) {
            if ($page instanceof rex_be_page && in_array($page->getKey(), $tableNames, true) && is_callable([$page, 'setBlock'])) {
                call_user_func([$page, 'setBlock'], 'formslider');
            }
        }
    });
}

/**
 * @param array<string, mixed> $params
 */
function formsliderGeneratePDF(array $params, string $template = ''): void
{
    rex_response::cleanOutputBuffers();
    \Locale::setDefault('de-DE');

    if (!rex_addon::get('pdfout')->isAvailable() || !class_exists(PdfOut::class)) {
        throw new rex_exception('Das Addon "pdfout" ist nicht verfügbar.');
    }

    $addon = rex_addon::get('formslider');
    $tempTemplatePath = rex_path::addonData('formslider', 'temp_template' . date('Y-m-d_H-i-s') . '_' . random_int(0, 9999999) . '.php');
    rex_file::put($tempTemplatePath, $template);

    $values = [];
    if (isset($params['values']) && is_array($params['values'])) {
        $values = $params['values'];
    }
    extract($values, EXTR_SKIP);

    ob_start();
    try {
        include $tempTemplatePath;
        $renderedContent = (string) ob_get_clean();
    } finally {
        rex_file::delete($tempTemplatePath);
    }

    $tableLabel = 'formslider';
    if (isset($params['main_table']) && is_string($params['main_table'])) {
        $table = rex_yform_manager_table::get($params['main_table']);
        if (null !== $table) {
            $tableLabel = $table->getName();
        }
    }

    $pdf = new PdfOut();
    $pdf->setName($tableLabel . '_' . date('Y-m-d_H-i-s') . '_' . random_int(0, 9999999))
        ->setFont('Helvetica')
        ->setHtml($renderedContent, true)
        ->setPaperSize('A4', 'portrait')
        ->setAttachment(true)
        ->setRemoteFiles(true)
        ->setDpi(300);

    $pdf->setSaveToPath($addon->getDataPath())->setSaveAndSend(true);
    $pdf->run();
}
