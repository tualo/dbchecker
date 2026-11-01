Ext.define('Tualo.dbchecker.lazy.controller.ViewsCheck', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.tualo_dbchecker_viewscheck',

    onBoxReady: function () {

    },

    onCheckView: function () {
        let me = this,
            model = me.getViewModel(),
            store = model.getStore('views'),
            range = store.getRange();

        me.singleCheck(range);
    },
    singleCheck: async function (range) {
        let me = this,
            model = me.getViewModel(),
            store = model.getStore('views'),
            record = range.pop();
        if (!record) {
            return;
        }



        let viewName = record.get('table_name');
        const formData = new FormData();
        formData.append("view", viewName);
        try {
            let result = fetch('./dbchecker/views/check', {
                method: 'POST',
                body: formData,
            }).then(response => response.json());
            let data = await result;
            if (data.success) {
                record.set('checktype', 'Success');
            } else {
                record.set('checktype', 'Failed: ' + data.msg);
            }
        } catch (e) {
            record.set('checktype', 'Error: ' + e.message);
        }
        if (range.length > 0) {
            await me.singleCheck(range);
        }
    }
}); 