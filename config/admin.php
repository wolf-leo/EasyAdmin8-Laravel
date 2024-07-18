<?php

return [
    'controller_namespace' => 'App\Http\Controllers\admin\\',

    // 超级管理员ID
    'super_admin_id'       => 1,

    // 后台别名 默认后台访问路径
    'admin_alias_name'     => config('easyadmin.ADMIN', 'admin'),

    // 不需要验证登录的控制器
    'no_login_controller'  => [
        'login',
    ],

    // 不需要验证登录的节点
    'no_login_node'        => [
        'login/index',
        'login/captcha',
        'login/out',
    ],

    // 不需要验证权限的控制器
    'no_auth_controller'   => [
        'ajax',
        'login',
        'index',
    ],

    // 不需要验证权限的节点
    'no_auth_node'         => [
        'login/index',
        'login/out',
    ],

    //上传类型
    'upload_types'         => [
        'local' => '本地存储',
        'oss'   => '阿里云oss',
        'cos'   => '腾讯云cos',
        'qnoss' => '七牛云'
    ],

    // 默认编辑器
    'editor_types'         => [
        'ueditor'    => '百度编辑器(不建议使用)',
        'ckeditor'   => 'CK编辑器',
        'wangEditor' => 'wangEditor(推荐使用)',
    ],
];
