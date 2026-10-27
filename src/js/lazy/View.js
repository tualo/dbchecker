Ext.define('Tualo.dbchecker.lazy.View', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.tualo_dbchecker_view',


    requires: [
        'Tualo.dbchecker.lazy.controller.View',
        'Tualo.dbchecker.lazy.models.View'
    ],


    controller: 'tualo_dbchecker_view',
    viewModel: {
        type: 'tualo_dbchecker_view'
    },
    title: null,
    bodyPadding: 10,
    listeners: {
        boxready: 'onBoxReady'
    },
    /*
    tools: [
        {
            type: 'refresh',
            handler: 'onRefresh'
        }
    ],
    */
    config: {
        checktype: null,
        table: null
    },
    applyChecktype: function (checktype) {
        console.log('Tualo.dbchecker.lazy.View', 'applyChecktype', checktype);
        this.getViewModel().set('checktype', checktype);
        this.getController().update();
    },
    applyTable: function (table) {
        console.log('Tualo.dbchecker.lazy.View', 'applyTable', table);
        this.getViewModel().set('table', table);
        this.getController().update();
    },

    items: [
        {
            xtype: 'panel',
            itemId: 'stage',

        }
    ]
});