Ext.define('Tualo.dbchecker.lazy.models.ViewsCheck', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.tualo_dbchecker_viewscheck',
    data: {
        checktype: null,
        table: null
    },
    stores: {
        views: {
            type: 'json',
            autoLoad: true,
            fields: [
                { 'dataIndex': 'checktype', 'name': 'checktype' },
                { 'dataIndex': 'table_name', 'name': 'table_name' },
                { 'dataIndex': 'msg', 'name': 'msg' }
            ],
            proxy: {
                type: 'ajax',
                url: './dbchecker/views',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            }
        }
    }
});             