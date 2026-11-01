Ext.define('Tualo.dbchecker.lazy.models.View', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.tualo_dbchecker_view',
    data: {
        checktype: null,
        table: null
    },
    stores: {
        view: {
            type: 'json',
            fields: [{ 'dataIndex': 'checktype', 'name': 'checktype' }, { 'dataIndex': 'table_name', 'name': 'table_name' }],
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