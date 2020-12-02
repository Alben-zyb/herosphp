<?php
/*---------------------------------------------------------------------
 * 当前访问application配置信息.
 * 注意：此处的配置将会覆盖同名键值的系统配置
 * ---------------------------------------------------------------------
 * Copyright (c) 2013-now http://blog518.com All rights reserved.
 * ---------------------------------------------------------------------
 * Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 * ---------------------------------------------------------------------
 * Author: <yangjian102621@gmail.com>
 *-----------------------------------------------------------------------*/
defined('RES_SERVER_URL') || define('RES_SERVER_URL', 'http://learn.my/');
$config = array(

    'template' => 'default',    //默认模板
    /**
     * 模板编译缓存配置
     * 0 : 不启用缓存，每次请求都重新编译(建议开发阶段启用)
     * 1 : 开启部分缓存， 如果模板文件有修改的话则放弃缓存，重新编译(建议测试阶段启用)
     * -1 : 不管模板有没有修改都不重新编译，节省模板修改时间判断，性能较高(建议正式部署阶段开启)
     */
    'temp_cache' => 0,  //模板引擎缓存

    /**
     * 用户自定义模板标签编译规则
     * array( 'search_pattern' => 'replace_pattern'  );
     */
    'temp_rules' => array(),

    'host' => $_SERVER['HTTP_HOST'],     //网站主机名
    //默认访问的页面
    'default_url' => array(
        'module' => 'admin',
        'action' => 'identify',
        'method' => 'login'),


    //短链接映射
    'url_mapping_rules' => array(
        '^\/newsdetail-(\d+)\/?$' => '/news/article/detail/?id=${1}',
        '^\/admin\/?$' => '/admin/identify/login',
    ),

    //以上都框架内置的配置变量，请不要删除，下面是用户自定义的变量可以添加或者删除
    'site_name' => 'HerosPHP 快速开发平台',
    'site_desc' => 'HerosPHP 快速开发平台',
    'site_author' => 'yangjian102621@gmail.com',
    'site_copyright' => '2016 &copy; HerosPHP by BlackFox',

    'rsa_private_key' => __DIR__ . '/keys/rsa_private_key.pem',
    'rsa_public_key' => __DIR__ . '/keys/rsa_public_key.pem',

    // 后台权限分组
    'permission_group' => array(
        'system' => '系统管理',
        'user' => '用户管理'
    ),

    // 七牛文件空间配置
    'qiniu_upload_configs' => [
        "ACCESS_KEY" => "_-BMslq1mPL_zY0KN2iLD1-ym4TcHhQUi0_dDFPB",
        "SECRET_KEY" => "J_As9ApfpyCpk31l3hOAZe3QQTc8iYlEfdd6-5an",
        "BUCKET" => "test",
        "BUCKET_DOMAIN" => "http://test.img.r9it.com/",
    ],

    //app邮件收发在账号
    'email' => 'Alben_zyb@163.com',

    //用户头像存储路径
    'headUrl' => '/static/images/',

    //默认用户头像名称
    'headImg' => 'head.jpg',

    //默认用户密码
    'password' => '123456',

    //默认验证邮箱
    'email' => '867512099@qq.com',


    //app所有模块
    'modules' => [
        'admin' => '后台模块',
        'common' => '公共模块',
        'room' => '会议室模块',
        'vacation' => '休假模块',
        'supply' => '办公用品模块',
    ],

    //app模块对应控制器
    'admin_controller' => [
        'index' => '默认',
        'identify' => '身份认证',
    ],
    'common_controller' => [
        'department' => '部门',
        'position' => '岗位',
        'user' => '用户',
        'role' => '角色',
        'permission' => '权限',
    ],
    'room_controller' => [
        'entity' => '会议室实体',
        'apply' => '会议室申请',
    ],
    'vacation_controller' => [
        'vacation' => '休假记录',
        'vacationType' => '休假类型',
    ],
    'supply_controller' => [
        'storehouse' => '仓库',
        'goodsCategory' => '办公用品分类',
        'goodsRecord' => '流水记录',
    ],
    //app控制器对应方法
    'admin_index_method' => [
        'index' => '查看',
    ],
    'admin_identify_method' => [
        'login' => '登录',
        'register' => '注册',
    ],
    'common_department_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],

    'common_position_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],

    'common_user_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
        'role' => '角色查看',
        'updateUserRole' => '角色更新',
    ],

    'common_role_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    'common_permission_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],

    'room_entity_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    'room_apply_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    'vacation_vacation_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    'vacation_vacationType_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    'supply_storehouse_method' => [
        'index' => '查看办公用品',
        'add' => '添加办公用品',
        'edit' => '修改办公用品',
        'delete' => '删除办公用品',
    ],
    'supply_goodsCategory_method' => [
        'index' => '查看',
        'add' => '添加',
        'edit' => '修改',
        'delete' => '删除',
    ],
    //流水记录
    'supply_goodsRecord_method' => [
        'index' => '查看',
        'inHouse' => '入库',
        'outHouse' => '出库审核',
        //'add'=>'添加',
        //'edit'=>'修改流水状态',
        'delete' => '删除',
    ],
);

return $config;