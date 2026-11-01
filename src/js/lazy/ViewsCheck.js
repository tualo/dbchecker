Ext.define('Tualo.dbchecker.lazy.ViewsCheck', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.tualo_dbchecker_viewscheck',


    requires: [
        'Tualo.dbchecker.lazy.controller.ViewsCheck',
        'Tualo.dbchecker.lazy.models.ViewsCheck'
    ],
    controller: 'tualo_dbchecker_viewscheck',
    viewModel: {
        type: 'tualo_dbchecker_viewscheck'
    },
    listeners: {
        boxready: 'onBoxReady'
    },
    layout: 'fit',
    items: [
        {
            scrollable: 'y',
            title: 'Views Check',
            tbar: [
                {
                    text: 'Prüfen',
                    handler: 'onCheckView'
                }
            ],
            xtype: 'grid',
            plugins: {
                gridfilters: true
            },
            columns: [
                { text: 'Check Type', dataIndex: 'checktype', filter: 'list' },
                { text: 'Table Name', dataIndex: 'table_name', flex: 1 }
            ],
            bind: {
                store: '{views}'
            },
            itemId: 'views'
        }
    ]
});