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

## Nutzung im Frontend

Wer die Ausgabe des Formslider im Frontend nutzen möchte, kann dies über die Einbindung im Head der Seite für die CSS-Datei und im Footer für die JS-Datei tun.

```html
<!-- im Header-->
<link rel="stylesheet" href="<?= rex_url::addonAssets('formslider', 'formslider.css') ?>">

<!-- im Footer -->
<script src="<?= rex_url::addonAssets('formslider', 'formslider.js') ?>"></script>
```

Wer die genutzen Klassen, IDs, Buttonbezeichnungen anpssen will, kann dies forlgendermaßen tun:

```html
  <script type="module">
    import { f as formslider } from './assets/addons/formslider/fsm.js';
    document.addEventListener('DOMContentLoaded', function () {
      // Initialize formslider
      formslider(
        { // Options
          nextButtonLabel: 'Weiter',
          prevButtonLabel: 'Zurück',
          deleteDataButtonLabel: 'Lösche meine Daten',
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
    prevButtonLabel: 'Prev',
    deleteDataButtonLabel: 'Delete my data',
    deleteDataHeadline: '<h6>Delete data</h6>',
    deleteDataText: '<p>Your data is stored locally in your browser. If you want to delete your data, you can do so by clicking the button below. (If you are on a public computer, you should delete your data.)</p>',
    prependProgress: false,
    progressClickable: true
}
```

## Mitgelieferte / Angepasste yTemplates

- checkbox wurde erweitert als Ja/Nein Schalter, wenn die Checkbox nur 1 Option hat und bei "Individuelle Attribute" `{"role":"switch"}` gesetzt ist.
- uikit, bootstrap und classic

### UIkit
- fieldset: legend die Klasse `uk-legend` bekommen, um diese zu stylen
- text: Klasse `uk-input` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- textarea: Klasse `uk-textarea` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- submit: Klasse `uk-button` hinzugefügt