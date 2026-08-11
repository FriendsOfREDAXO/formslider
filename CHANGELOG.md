# Changelog

## 1.0.1 (2026-08-11)

- NPM-Abhängigkeiten auf den aktuellen, kompatiblen Stand innerhalb der bestehenden Versionsbereiche aktualisiert.
- `@babel/core` auf `^7.29.7` angehoben (Issue #31).
- Lockfiles aktualisiert (package-lock.json, yarn.lock).
- Build-Prüfung nach dem Update erfolgreich ausgeführt.
- Boot-Code für YForm-Seitenzuordnung bereinigt und typischer Edge-Case bei nicht verfügbaren Seiten-Properties abgefangen.
- PDF-Generierung auf aktuelle pdfout-Integration aktualisiert (Namespace-Klasse FriendsOfRedaxo\\PdfOut\\PdfOut).
- PDF-Renderpfad robuster gemacht: Verfügbarkeitscheck für pdfout, sicheres Temp-Template-Handling mit garantiertem Cleanup.
- PDF-Output auf REDAXO-Best-Practice gehärtet: Output-Buffer wird vor der Ausgabe mit rex_response::cleanOutputBuffers() geleert.
- Backend-Seiten statisch verbessert (Null-sichere User-Prüfungen, konsistente i18n-Zugriffe, initialisierte Variablen).
- yTemplate-Typannotationen präzisiert, damit statische Analyse sauber aufgelöst wird.
- RexStan-Analyse für das Addon erfolgreich bereinigt (aktueller Stand: keine Fehler).
