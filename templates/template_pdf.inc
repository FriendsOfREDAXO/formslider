<?php

// template:formslider_pdf_template

//Bildname aus dem Medienpool
$logo = 'logo.png';
$mediapath = '';
// es wird der komplette Pfad zum Bild benötigt, damit DOMPDF das Bild einbinden kann
if(rex_media::get($logo)) {
    $mediapath = rex_path::media($logo);
}

// Damit Header, Footer und Aside auf jeder Seite erscheinen, müssen sie vor dem content definiert und mit position:fixed versehen werden

$content = '
<style>
    body {
        font-family: "Helvetica";
        font-size: 11pt;
        margin: 0;
        padding: 0;
    }

    @page {
        margin: 40mm 20mm 5mm 20mm;
    }

    .mt-10 {
        margin-top: 10mm;
    }

    .mt-10:first-of-type {
        margin-top: 0;
    }

    .mt-7 {
        margin-top: 7mm;
    }

    .mt-2 {
        margin-top: 2mm;
    }

    .mb-0 {
        margin-bottom: 0;
    }

    header {
        position: fixed;
        width: 100%;
        top: -30mm;
        left: 0;
        right: 0;
        height: 22mm;
        /* padding: 11mm 20mm; */
    }

    .pagenum-container {
        position: absolute;
        top: 0;
        left: 0;
        font-size: 9pt;
        color: #999;
    }

    header .pagenum:before {
        content: counter(page);
        font-weight: bold;
    }

    .logo {
        width: 18mm;
        float: right;
        margin-left: 6mm;
    }

    .logo-claim {
        font-size: 12pt;
        float: right;
        margin:  2mm 0 0 0;
        text-align: right;
    }

    .clearfix {
        clear: both;
    }

    .line {
        background-color: white;
        text-align: center;
        color: white;
        font-size: 16pt;
        font-weight: bold;
        margin-top: 1.5mm;
    }

    .signatures {
        page-break-inside: avoid;
        position: relative;
        padding: 40mm 0 0 0;
    }

    .signature {
        position: relative;
    }

    .signline {
        border-bottom: 1pt solid #000;
        margin: 10mm 0 1.5mm 0;
    }

    .placeanddate {
        position: relative;
        float: left;
        width: 30%;
    }

    .signee {
        position: relative;
        float: left;
        width: 60%;
    }

    .attestation {
        position: relative;
        padding: 20mm 0 0 0;
    }

    footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        text-align: center;
        font-size: 8pt;
        color: #999;
    }

    footer p, .placeanddate p, .signee p, .attestor p{
        margin: 0;
    }

    .fieldset {
        font-size: 14pt;
        font-weight: bold;
        border-bottom: 1pt solid #000;
    }

    aside {
        position: fixed;
        right: -30mm;
        bottom: 60mm;
        font-size: 8pt;
        color: #000;
        border: 1pt solid #000;
        padding: 2mm 5mm;
        transform: rotate(-90deg);
    }
</style>

<body>
    <header>
        <div class="pagenum-container">Seite <span class="pagenum"></span> von <strong>DOMPDF_PAGE_COUNT_PLACEHOLDER</strong> </div>';
      if($mediapath != '') {
        $content .= '<img class="logo" src="'.$mediapath.'" />';
      }
      $content .= '
        <p class="logo-claim">REDAXO</p>
        <div class="clearfix"></div>
        <div class="line">Mein toller Titel</div>
    </header>
    <footer>
        <p class="muted">REDAXO</p>
    </footer>
    <aside>Stand '.date('m').'/'.date('Y').'</aside>
    <div class="content">';
    foreach($values as $key => $value) {

        switch ($value->type) {
            case 'text':
                $content .= '<p>'.$value->label.' <strong>'.$value->value.'</strong></p>';
                break;
            case 'email':
                $content .= '<p>'.$value->label.' <strong>'.$value->value.'</strong></p>';
                break;
            case 'textarea':
                $content .= '<p>'.$value->label.'</p>';
                $content .= '<p>'.$value->value.'</p>';
                break;
            case 'select':
                $content .= '<p>'.$value->label.'</p>';
                $content .= '<p>'.$value->value.'</p>';
                break;
            case 'radio':
                $content .= '<p>'.$value->label.'</p>';
                $content .= '<p>'.$value->value.'</p>';
                break;
            case 'checkbox':
                $content .= '<p class="mb-0 mt-7">'.$value->label.'</p>';
                $content .= '<p class="mt-2" style="page-break-before:avoid"><strong>'. ( $value->value == 1 ? 'Ja' : 'Nein' ). '</strong></p>';
                break;
            case 'date':
                $content .= '<p>'.$value->label.' <strong>'.rex_formatter::intlDate($value->value, IntlDateFormatter::MEDIUM).'</strong></p>';
                break;
            case 'fieldset':
                $content .= '<p class="mt-10 fieldset"><strong>'.$value->label.'</strong></p>';
                break;
            case 'html':
                $content .= '<p><em>'.$value->label.'</em></p>';
                break;
        }
    }

$content .= '</div>
<div class="signatures">
    <div class="signature">
        <div class="signline"></div>
        <div class="placeanddate">
            <p>Ort, Datum</p>
        </div>
        <div class="signee">
            <p>Unterschrift der einen Partei</p>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="signature">
        <div class="signline"></div>
        <div class="placeanddate">
            <p>Ort, Datum</p>
        </div>
        <div class="signee">
            <p>Unterschrift der anderen Partei</p>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="attestation">
    <div class="signline"></div>
    <div class="attestor">
        <p><strong>Evtl. Beglaubigungsvermerk</strong> der Unterschrift durch irgendeine Instanz</p>
    </div>
    <div class="clearfix"></div>
</div>
</body>';

echo $content;