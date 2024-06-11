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

## Mitgelieferte / Angepasste yTemplates

- checkbox wurde erweitert als Ja/Nein Schalter, wenn die Checkbox nur 1 Option hat und bei "Individuelle Attribute" `{"role":"switch"}` gesetzt ist.
- uikit, bootstrap und classic

### UIkit
- fieldset: legend die Klasse `uk-legend` bekommen, um diese zu stylen
- text: Klasse `uk-input` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- textarea: Klasse `uk-textarea` hinzugefügt, `form-group` ist `uk-margin`, der Help-Block `uk-form-small`
- submit: Klasse `uk-button` hinzugefügt