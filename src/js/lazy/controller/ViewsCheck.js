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

        me.getView().setLoading(true);
        range.forEach(record => record.set('checktype', 'Pending'));
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
        } else {
            me.getView().setLoading(false);
        }
    },

    onRecreateView: function () {
        let me = this,
            model = me.getViewModel(),
            view = me.getView(),
            grid = view.getComponent('views'),
            selection = grid.getSelection();

        console.log('onRecreateView selection', selection);
        me.getView().setLoading(true);
        me.singleRecreate(selection);
    },

    singleRecreate: async function (range) {
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
            let result = fetch('./dbchecker/views/recreate', {
                method: 'POST',
                body: formData,
            }).then(response => response.json());
            let data = await result;
            if (data.success) {
                record.set('checktype', 'Recreated');
                record.set('msg', '');
            } else {
                record.set('checktype', 'Failed');
                record.set('msg', data.msg);
            }
        } catch (e) {
            record.set('checktype', 'Error');
            record.set('msg', e.message);
        }
        if (range.length > 0) {
            await me.singleRecreate(range);
        } else {
            me.getView().setLoading(false);
        }
    }
}); 