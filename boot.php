<?php // Boot code

rex_yform::addTemplatePath(rex_path::addon('formslider', 'ytemplates'));


if (rex::isBackend()) {
    rex_extension::register('PACKAGES_INCLUDED', function ($params) {

        $addom = rex_addon::get('yform');

        if ($addon->isAvailable() {
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

function formsliderGeneratePDF($params, $template = '') {
    Locale::setDefault('de-DE');
    $valuePool = $params['value_pool']['sql'];
    $addon = rex_addon::get('formslider');
    
    // Speichere den Template-Inhalt in eine temporäre Datei, um sie als PHP einzubinden
    $tempTemplatePath = rex_path::addonData('formslider', 'temp_template'.date('Y-m-d_H-i-s').'_'.random_int(0, 9999999).'.php');
    rex_file::put($tempTemplatePath, $template, LOCK_EX);

    $values = $params['values'];

    extract($values);

    // Das Template mit den übergebenen Parametern rendern
    ob_start();
    include $tempTemplatePath;
    $renderedContent = ob_get_clean();

    // Die temporäre Datei löschen
    unlink($tempTemplatePath);

    $pdf = new PdfOut();

    $pdf->setName(rex_yform_manager_table::get($params['main_table'])->getName().'_'.date('Y-m-d_H-i-s').'_'.random_int(0, 9999999))
        ->setFont('Helvetica')
        ->setHtml($renderedContent, true)
        ->setOrientation('portrait')
        ->setPaper('A4')
        ->setAttachment(true)
        ->setRemoteFiles(true)
        ->setDpi(300);

    // $font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
    // $pdf->getCanvas()->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));

    // Save File to path and send File
    $dataPath = $addon->getDataPath();
    $pdf->setSaveToPath($dataPath)->setSaveAndSend(true);

    // execute and generate
    $pdf->run();

}
