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

            try {

                let fn = async function () {
                    let result = await fetch('./dbchecker/access');
                    result = await result.json();
                    if (!result.success) {

                    } else {
                        Ext.getApplication().addView('Tualo.dbchecker.lazy.View', {
                            checktype: values.checktype,
                            table: values.table
                        });
                    }
                }();


            } catch (e) {
                console.error(e);
            }
        },
        before: function (values, action) {
            console.log('Tualo.routes.dbchecker.View', 'Before action called', arguments);
            action.resume();
        }

    }
});