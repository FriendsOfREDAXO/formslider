# Form Slider

Das Form Slider Addon ermöglicht es, Formulare in einem Slider anzuzeigen. Die Formulare können über YForm oder innerhalb des Addons erstellt und verwaltet werden.

## Features

- Formulare in einem Slider anzeigen
- localStorage Unterstützung
- PDF Generierung
- Anpassbarkeit der Klassen und IDs

## Installation

1. Über Installer laden oder Zip-Datei im Addon-Ordner entpacken, der Ordner muss `formslider` heißen
2. Addon installieren und aktivieren
3. Formulare erstellen, im mitgelieferten Modul Formular auswählen und im Slider anzeigen

## Konventionen zur Nutzung

- Beim anlegen eines Tables für ein Formular `formslider` irgendwo im Namen verwenden, also z.B. `rex_formslider_meinformular`
- In YForm alles mit Fieldsets gruppieren, jedes Fieldset wird zu einem Slide (es muss mindestens ein Fieldset geben)
- Das Modul setzt das ObjectParameter `realfieldnames` auf `true`, um die Feldnamen zu verwenden (macht es einfacher für localStorage)
- Will man eine Checkbox als Ja/Nein Switch nutzen bei "Individuelle Attribute" `{"role":"switch"}` hinzufügen
- Felder die im Formular wiederholt werden sollen, mit eine `_again` versehen, z.B. `name_again` (optional nein `{"disabled":"disabled"}` bei den *Individuelle Attribute* hinzufügen)
- Wenn man mit dem Formular keine Daten sammeln will, kann man den Table für das Formular auf "In Navigation verstecken" setzen
- Wenn man mit dem Formular Daten sammeln will, sollte man den Table nicht in der Navigation verstecken - dieser wird dann im Block Formslider angezeigt


## Nutzung im Frontend

Wer die Ausgabe des Formslider im Frontend nutzen möchte, kann dies über die Einbindung im Head der Seite für die CSS-Datei und im Footer für die JS-Datei tun.

```html
<!-- im Header-->
<link rel="stylesheet" href="<?= rex_url::addonAssets('formslider', 'formslider.css') ?>">

<!-- im Footer -->
<script src="<?= rex_url::addonAssets('formslider', 'formslider.js') ?>"></script>
```

Wer die genutzen Klassen, IDs, Buttonbezeichnungen etc. anpassen will, kann dies folgendermaßen tun:

```html
  <script type="module">
    import { f as formslider } from './assets/addons/formslider/fsm.js';
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize formslider
      formslider(
        { // Options
          nextButtonLabel: 'Weiter',
          nextButtonClasses: 'uk-button uk-button-default',
          prevButtonLabel: 'Zurück',
          prevButtonClasses: 'uk-button uk-button-default',
          deleteDivClasses: 'uk-margin-large-top',
          deleteDataButtonLabel: 'Lösche meine Daten',
          deleteDataButtonClasses: 'uk-button uk-button-default',
          deleteDataHeadline: '<h6>Daten löschen</h6>',
          deleteDataText: '<p>Ihre Daten gehören Ihnen. Alle eingegebenen Daten werden lokal in Ihrem Browser gespeichert. Kehren Sie zu dieser Seite zurück, werden Ihre eingegeben Daten wiederhergestellt. Sie können die Daten löschen, indem Sie ihren Browser-Cache / Cookies löschen oder indem Sie auf den untentstehenden Button klicken. (An einem öffentlichen Rechner sollten Sie die Daten auf jeden Fall löschen.)</p>',
          prependProgress: true
        }
      );
    })
  </script>
```

### Optionen der Formslider JS

```js
options = {
    selector: '.formslider',
    showProgress: true,
    showProgressCount: false,
    nextButtonLabel: 'Next',
    nextButtonClasses: '',
    prevButtonLabel: 'Prev',
    prevButtonClasses: '',
    deleteDivClasses: '',
    deleteDataButtonLabel: 'Delete my data',
    deleteDataButtonClasses: '',
    deleteDataHeadline: '<h6>Delete data</h6>',
    deleteDataText: '<p>Your data is stored locally in your browser. If you want to delete your data, you can do so by clicking the button below. (If you are on a public computer, you should delete your data.)</p>',
    prependProgress: false,
    progressClickable: true
}
```

## Style Anpassungen

### Formslider

Wer die Styles des Formsliders anpassen möchte, kann die über CSS Variablen tun. Hier ein Beispiel:

```css
.formslider {

  --formprogress-bgcolor: antiquewhite;
  --formprogress-border-radius: 4px;
  --formprogress-step-height: 1em;
  --formprogress-step-bgcolor: lightgray;
  --formprogress-step-active-bgcolor: darkgray;
  --formprogress-step-done-bgcolor: green;
  --formprogress-step-active-done-bgcolor: limegreen;

  --formslider-danger-color: red;
  --formslider-danger-bgcolor: #f8d7da;
}
```

### Checkbox Switch

Ebenfalls ist es möglich den Checkbox Switch über CSS Variablen anzupassen:

```css
:root {
  --switch-width: 40px;
  --switch-height: 21px;
  --switch-border-radius: 11px;
  --switch-position-width: 14px;
  --switch-position-height: 14px;
  --switch-position-border-radius: 9px;
  --switch-border-color: black;
  --switch-position-border-color: black;
  --switch-position-background-color: black;
  --switch-position-opacity: 0.6;
  --switch-checked-color: green;
  --switch-checked-background-color: green;
  --switch-checked-opacity: 1;
  --switch-hover-background-color: #def;
  --switch-hover-wrapper-background-color: white;
}
```

## Mitgelieferte / Angepasste yTemplates

- checkbox wurde erweitert als Ja/Nein Schalter, wenn die Checkbox nur 1 Option hat und bei "Individuelle Attribute" `{"role":"switch"}` gesetzt ist.
- uikit und bootstrap

### UIkit
- fieldset: legend die Klasse `uk-legend` bekommen, um diese zu stylen
- text: Klasse `uk-input` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- textarea: Klasse `uk-textarea` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- submit: Klasse `uk-button` hinzugefügt