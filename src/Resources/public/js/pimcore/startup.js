pimcore.registerNS("pimcore.plugin.CorepimUserLoginHistoryBundle");

pimcore.plugin.CorepimUserLoginHistoryBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.CorepimUserLoginHistoryBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("CorepimUserLoginHistoryBundle ready!");
    }
});

var CorepimUserLoginHistoryBundlePlugin = new pimcore.plugin.CorepimUserLoginHistoryBundle();
