<?php // Boot code

rex_yform::addTemplatePath(rex_path::addon('formslider', 'ytemplates'));

// Handle PDF download requests
formsliderHandlePDFDownload();

// Clean up old PDF files periodically
if (rand(1, 100) === 1) { // 1% chance to run cleanup
    formsliderCleanupOldPDFs();
}


if (rex::isBackend()) {
    rex_extension::register('PACKAGES_INCLUDED', function ($params) {

        $addom = rex_addon::get('yform');

        if ($addon->isAvailable()) {
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

function formsliderGeneratePDF($params, $template = '', $shouldDownload = true, $formType = '3') {
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

    $pdfFileName = rex_yform_manager_table::get($params['main_table'])->getName().'_'.date('Y-m-d_H-i-s').'_'.random_int(0, 9999999);
    $pdf->setName($pdfFileName)
        ->setFont('Helvetica')
        ->setHtml($renderedContent, true)
        ->setOrientation('portrait')
        ->setPaper('A4')
        ->setAttachment(true)
        ->setRemoteFiles(true)
        ->setDpi(300);

    // $font = $pdf->getFontMetrics()->get_font("helvetica", "bold");
    // $pdf->getCanvas()->page_text(72, 18, "Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));

    $dataPath = $addon->getDataPath();
    
    // For PDF+Email combinations (types 4 and 5), save PDF and return path for email attachment
    if ($formType == '4' || $formType == '5') {
        // Save PDF file for email attachment
        $pdf->setSaveToPath($dataPath)->setSaveAndSend(false);
        $pdf->run();
        
        // Store PDF path for email attachment and later cleanup
        $pdfPath = $dataPath . $pdfFileName . '.pdf';
        return $pdfPath;
        
    } else {
        // For PDF-only (type 3), download immediately as before
        $pdf->setSaveToPath($dataPath)->setSaveAndSend($shouldDownload);
        $pdf->run();
        return null;
    }

}

// Function to clean up PDF files after a certain time period
function formsliderCleanupOldPDFs() {
    $addon = rex_addon::get('formslider');
    $dataPath = $addon->getDataPath();
    
    if (is_dir($dataPath)) {
        $files = glob($dataPath . '*.pdf');
        $now = time();
        
        foreach ($files as $file) {
            // Delete PDFs older than 1 hour
            if (is_file($file) && ($now - filemtime($file)) > 3600) {
                unlink($file);
            }
        }
    }
}

// Function to handle PDF download via AJAX request
function formsliderHandlePDFDownload() {
    if (isset($_GET['formslider_download_pdf']) && isset($_GET['pdf_file'])) {
        $addon = rex_addon::get('formslider');
        $dataPath = $addon->getDataPath();
        $filename = basename($_GET['pdf_file']); // Security: only filename, no path
        $pdfPath = $dataPath . $filename;
        
        if (file_exists($pdfPath) && pathinfo($pdfPath, PATHINFO_EXTENSION) === 'pdf') {
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($pdfPath));
            readfile($pdfPath);
            
            // Clean up: Delete the PDF file after download
            unlink($pdfPath);
            exit;
        }
    }
}
