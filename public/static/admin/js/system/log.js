define(["jquery", "easy-admin"], function ($, ea) {


    var init = {
        table_elem: '#currentTable',
        table_render_id: 'currentTableRenderId',
        index_url: 'system.log/index',
        export_url: 'system.log/export',
    };

    return {
        index: function () {
            var util = layui.util;
            ea.table.render({
                init: init,
                lineStyle: 'height: auto;',
                toolbar: ['refresh', 'export',
                    [{
                        text: __('Framework log'),
                        url: 'system.log/record',
                        method: 'open',
                        auth: 'record',
                        class: 'layui-btn layui-btn-sm',
                        icon: 'fa fa-book',
                        extend: 'data-width="95%" data-height="95%"'
                    },
                    ]
                ],
                cols: [[
                    {field: 'id', width: 80, title: 'ID', search: false},
                    {field: 'month', width: 80, title: __('month'), hide: true, search: 'time', timeType: 'month', searchValue: util.toDateString(new Date(), 'yyyy-MM')},
                    {
                        field: 'admin.username', width: 100, title: __('username'), search: false, templet: function (res) {
                            let admin = res.admin
                            return admin ? admin.username : '-'
                        }
                    },
                    {field: 'method', width: 100, title: __('method')},
                    {field: 'title', width: 180, title: __('title')},
                    {field: 'ip', width: 150, title: 'ip'},
                    {field: 'url', minWidth: 150, title: 'url', align: "left"},
                    {
                        field: 'content', minWidth: 200, title: __('data'), align: "left", templet: function (res) {
                            let html = '<div class="layui-colla-item">' +
                                '<div class="layui-colla-title">' + __('Click to preview') + '</div>' +
                                '<div class="layui-colla-content">' + prettyFormat(res.content) + '</div>' +
                                '</div>'
                            return '<div class="layui-collapse" lay-accordion>' + html + '</div>'
                        }
                    },
                    {
                        field: 'response', minWidth: 200, title: __('response'), align: "left", templet: function (res) {
                            let html = '<div class="layui-colla-item">' +
                                '<div class="layui-colla-title">' + __('Click to preview') + '</div>' +
                                '<div class="layui-colla-content">' + prettyFormat(res.response) + '</div>' +
                                '</div>'
                            return '<div class="layui-collapse" lay-accordion>' + html + '</div>'
                        }
                    },
                    {field: 'create_time', minWidth: 100, title: __('create time'), search: 'range'},
                ]],
                done: function () {
                    layui.element.render('collapse')
                }
            });
            ea.listen();
        },
    };
});
