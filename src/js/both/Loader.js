Ext.define('Tualo.dbchecker.Loader', {
    singleton: true,

    constructor: function () {
        Ext.Loader.setPath('Tualo.dbchecker.lazy', './js/dbchecker/lazy');
    }
});
