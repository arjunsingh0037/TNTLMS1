require(['core/first'], function() {
    require(['tool_datatables/init'], function(dt) {
        selector = '#page-admin-report-customsql-index .generaltable, body#page-user-profile .generaltable';
        dt.init(selector, {});
    });
});