import formslider from './formslider';

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
