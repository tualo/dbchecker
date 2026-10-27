Ext.define('Tualo.routes.dbchecker.View', {
    statics: {
        load: async function () {
            return [
                {
                    name: 'LiveView',
                    path: '#dbchecker-view'
                }
            ]
        }
    },
    url: 'dbchecker-view(/:{checktype}/:{table})',
    handler: {
        action: function (values) {


            Ext.getApplication().addView('Tualo.dbchecker.lazy.View', {
                checktype: values.checktype,
                table: values.table
            });


        },
        before: async function (values, action) {
            console.log('Tualo.routes.dbchecker.View', 'Before action called');
            let result = await fetch('./js/dbchecker');
            result = await result.json();
            if (!result.success) {
                action.stop();
            } else {
                action.resume();
            }

        }

    }
});