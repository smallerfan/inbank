<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware'=>'rbac'], function () use($router) {
    //框架
    $router->get('/','Admin\IndexController@index');
    //控制台
    $router->get('/console','Admin\IndexController@console');
    //403无访问权限
    $router->get('/403','Admin\IndexController@noPermission');
    $router->group(['prefix' => 'admin'], function () use($router) {
        //菜单管理
        $router->get('/menu/list', 'Admin\AdministratorController@menuList');
        $router->any('/menu/add', 'Admin\AdministratorController@menuAdd');
        $router->any('/menu/update/{id}', 'Admin\AdministratorController@menuUpdate');
        $router->post('/menu/del/{id}', 'Admin\AdministratorController@menuDel');
        //角色管理
        $router->get('/role/list', 'Admin\AdministratorController@roleList');
        $router->any('/role/add', 'Admin\AdministratorController@roleAdd');
        $router->any('/role/update/{id}', 'Admin\AdministratorController@roleUpdate');
        $router->post('/role/del/{id}', 'Admin\AdministratorController@roleDel');
        //权限管理
        $router->get('/permission/list','Admin\AdministratorController@permissionList');
        $router->any('/permission/add','Admin\AdministratorController@permissionAdd');
        $router->any('/permission/update/{id}','Admin\AdministratorController@permissionUpdate');
        $router->post('/permission/del/{id}','Admin\AdministratorController@permissionDel');
        //管理员管理
        $router->get('/administrator/list','Admin\AdministratorController@administratorList');
        $router->any('/administrator/add','Admin\AdministratorController@administratorAdd');
        $router->any('/administrator/update/{id}','Admin\AdministratorController@administratorUpdate');
        $router->post('/administrator/del/{id}','Admin\AdministratorController@administratorDel');
        //配置管理
        $router->get('/config/list','Admin\ConfigController@configList');
        $router->any('/config/add','Admin\ConfigController@configAdd');
        $router->any('/config/update/{id}','Admin\ConfigController@configUpdate');
        $router->post('/config/del/{id}','Admin\ConfigController@configDel');
        //图片上传
        $router->post('/upload','Admin\IndexController@upload');
        $router->post('/wangeditor/upload','Admin\IndexController@wangeditorUpload');
    });
    //修改个人信息
    $router->any('/edit/info/{id}','Admin\AdministratorController@editInfo');
    //退出登录
    $router->get('/logout','Admin\AdministratorController@logout');
    //new
    $router->get('home', 'HomeController@index')->name('home');
    $router->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

    $router->post('users/lock/{id}', 'UsersController@lock')->where(['id' => '[0-9]+'])->name('users.lock');
    Route::post('users/tree_list', 'UsersController@treeList')->name('users.tree_list');
    $router->post('users/check_line', 'UsersController@check_line')->name('users.check_line');
    $router->post('users/lock_line', 'UsersController@lock_line')->name('users.lock_line');
    $router->get('users/show/{id}', 'UsersController@show')->where(['id' => '[0-9]+'])->name('users.show');
    $router->post('users/searchUser', 'UsersController@searchUser')->name('users.searchUser');
    $router->post('users/move_line', 'UsersController@move_line')->name('users.move_line');
    $router->get('users/getChildArea', 'UsersController@getChildArea')->name('users.getChildArea');


    $router->resource("users", 'UsersController');
    $router->post("user/user_find", 'UsersController@userFind')->name('user.user_find');
    $router->get("user/user_relation", 'UsersController@userRelation')->name('user.user_relation');


    //用户等级管理
    $router->any("user/user_level/index", 'UsersController@userLevel')->name('user_level.index');
    $router->any("user/user_level/create", 'UsersController@userLevelAdd')->name('user_level.create');
    $router->any("user/user_level/edit/{id}", 'UsersController@userLevelEdit')->where(['id' => '[0-9]+'])->name('user_level.edit');
    $router->any("user/user_level/delete/{id}", 'UsersController@userLevelDelete')->where(['id' => '[0-9]+'])->name('user_level.delete');


    $router->post("users/update", 'UsersController@update')->name('users.update');
    $router->get('users/{user_id}/user_riches/new', 'UserRichesController@new')->name("user_riches.new");
    $router->get('users/{user_id}/user_riches/dk_logs', 'UserRichesController@dk_logs')->name("user_riches.dk_logs");
    $router->get('users/{user_id}/user_riches/dn_logs', 'UserRichesController@dn_logs')->name("user_riches.dn_logs");
    $router->post('users/{user_id}/user_riches', 'UserRichesController@store')->name('user_riches.create');
    $router->delete('users/{user_id}/user_riches/{id}', 'UserRichesController@destroy')->name("user_riches.destroy");

    $router->resource("admins", 'AdminsController');
    $router->resource('roles', 'RolesController');
    $router->resource('permissions', 'PermissionsController');

    //充值
    $router->resource("recharge", 'RechargeController');
    $router->get('recharge/lists/{flag}', 'RechargeController@lists')->name("recharge.lists");
    $router->post("recharge/update", 'RechargeController@update')->name('recharge.update');
    //提取
    $router->resource("extract", 'ExtractController');
    $router->get('extract/lists/{flag}', 'ExtractController@lists')->name("extract.lists");
    $router->post("extract/update", 'ExtractController@update')->name('extract.update');
    $router->resource("withdraw", 'WithdrawController');
    $router->post("withdraw/update", 'WithdrawController@update')->name('withdraw.update');
    //商城
    $router->group(array('prefix' => 'shop', 'namespace' => 'Shop'), function() use ($router) {
        //物流管理

        $router->resource("node", 'NodeController');
        $router->any("node/add", 'NodeController@addNode')->name('node.add');
        $router->any("node/detail/{id}", 'NodeController@detail')->name('node.detail');
        //订单
        $router->resource("order", 'OrderController');
        //发货
        $router->post('order/update', 'OrderController@updateExpress')->name('order.update');
        //物流详情
        $router->any('order/express_kd/{id}', 'OrderController@express_kd')->name('order.express_kd');
        //修改地址
        $router->post('order/edit_express', 'OrderController@editExpressInfo')->name('order.edit_express');
        //订单列表
        $router->any('order/list', 'OrderController@getList')->name('order.list');
        $router->get("order/edit_status", 'OrderController@editStatus')->name('order.edit_status');
        $router->any("order/ship/{id}", 'OrderController@ship')->where(['id' => '[0-9]+'])->name('order.ship');
        $router->any("order/cancel_order/{id}", 'OrderController@cancelOrder')->where(['id' => '[0-9]+'])->name('order.cancel');
        $router->any("order/order_detail/{id}", 'OrderController@orderDetail')->where(['id' => '[0-9]+'])->name('order.detail');
        $router->resource("creditOrder", 'CreditOrderController');
        $router->post('creditOrder/update', 'CreditOrderController@updateExpress')->name('creditOrder.update');

        //修改地址
        $router->post('creditOrder/edit_express', 'CreditOrderController@editExpressInfo')->name('creditOrder.edit_express');
        $router->any('creditOrder/express_kd/{id}', 'CreditOrderController@express_kd')->where(['id' => '[0-9]+'])->name('creditOrder.express_kd');
        $router->any("creditOrder/ship/{id}", 'CreditOrderController@ship')->where(['id' => '[0-9]+'])->name('creditOrder.ship');
        $router->any("creditOrder/order_detail/{id}", 'CreditOrderController@orderDetail')->where(['id' => '[0-9]+'])->name('creditOrder.detail');

        //奖金管理
        $router->resource("award", 'AwardController');
        //区域代理设置
        $router->resource("agent", 'AgentController');

        $router->any("agent/add", 'AgentController@addAgent')->name('agent.add');
        $router->any("agent/edit/{id}", 'AgentController@edit')->where(['id' => '[0-9]+'])->name('agent.edit');
        $router->any("agent/delete/{id}", 'AgentController@delete')->where(['id' => '[0-9]+'])->name('agent.delete');

        $router->resource('categories', 'CategoryController');
        $router->post('categories/store', 'CategoryController@add')->name('categories.store');
        $router->post('categories/update', 'CategoryController@update')->name('categories.update');

        //商品管理
        $router->resource('goods', 'GoodsController');

        $router->post('goods/add_goods', 'GoodsController@addGoods')->name('goods.add_goods');
        $router->any('goods/edit/{id}', 'GoodsController@edit')->where(['id' => '[0-9]+'])->name('goods.edit');
        $router->any('goods/delete/{id}', 'GoodsController@deleteGoods')->where(['id' => '[0-9]+'])->name('goods.delete');
        $router->post('goods/update', 'GoodsController@update')->name('goods.update');
        $router->any('goods/destroy/{id}', 'GoodsController@destroy')->where(['id' => '[0-9]+'])->name('goods.destroy');
        $router->post('goods/set_hot_sale/{id}', 'GoodsController@set_hot_sale')->where(['id' => '[0-9]+'])->name('goods.set_hot_sale');
        $router->post('goods/unset_hot_sale/{id}', 'GoodsController@unset_hot_sale')->where(['id' => '[0-9]+'])->name('goods.unset_hot_sale');
        $router->post('goods/set_choiceness_self/{id}', 'GoodsController@set_choiceness_self')->where(['id' => '[0-9]+'])->name('goods.set_choiceness_self');
        $router->post('goods/update_special/{id}', 'GoodsController@updateSpecial')->where(['id' => '[0-9]+'])->name('goods.update_special');
        $router->post('goods/unset_choiceness_self/{id}', 'GoodsController@unset_choiceness_self')->where(['id' => '[0-9]+'])->name('goods.unset_choiceness_self');
        $router->post('goods/set_enable/{id}', 'GoodsController@set_enable')->where(['id' => '[0-9]+'])->name('goods.set_enable');
        $router->post('goods/set_disable/{id}', 'GoodsController@set_disable')->where(['id' => '[0-9]+'])->name('goods.set_disable');
        $router->post('goods/set_sort', 'GoodsController@set_sort')->name('goods.set_sort');

        $router->resource('orders', 'OrderController', ['except' => 'show']);
        $router->resource('shopers', 'ShoperController');
        $router->post('shopers/update', 'ShoperController@update')->name('shopers.update');

        $router->post('shopers/set_enable/{id}', 'ShoperController@set_enable')->where(['id' => '[0-9]+'])->name('shopers.set_enable');
        $router->post('shopers/set_disable/{id}', 'ShoperController@set_disable')->where(['id' => '[0-9]+'])->name('shopers.set_disable');
        $router->post('shopers/set_no_self/{id}', 'ShoperController@set_no_self')->where(['id' => '[0-9]+'])->name('shopers.set_no_self');
        $router->post('shopers/set_self/{id}', 'ShoperController@set_self')->where(['id' => '[0-9]+'])->name('shopers.set_self');
        $router->post('shopers/pass/{id}', 'ShoperController@pass')->where(['id' => '[0-9]+'])->name('shopers.pass');
        $router->post('shopers/reject/{id}', 'ShoperController@reject')->where(['id' => '[0-9]+'])->name('shopers.reject');

        $router->resource('settings', 'SettingController', ['except' => 'show']);
        $router->post('settings/update', 'SettingController@update')->name('settings.update');
    });
    //c2c实名认证
    $router->group(array('prefix' => 'c2c', 'namespace' => 'C2C'), function() use ($router) {
        $router->resource('auth', 'C2cUserAuthController');
        $router->post('auth/update', 'C2cUserAuthController@update')->name('auth.update');
        $router->post('auth/recall', 'C2cUserAuthController@recall')->name('auth.recall');
        $router->resource('c2c_users', 'C2cUserController');
        $router->post('c2c_users/update', 'C2cUserController@update')->name('c2c_users.update');
        $router->resource('check', 'CheckController');
        $router->post('cancel', 'CheckController@cancel')->name('check.cancel');
        $router->post('complete', 'CheckController@complete')->name('check.complete');

    });
    $router->group(array('prefix' => 'data', 'namespace' => 'Data'), function() use ($router) {
        $router->resource('data', 'DataController');
        $router->get('goods_sale', 'DataController@goods_sale')->name('data.goods_sale');
//        Route::get('addUsers', 'DataController@addUsers')->name('addUsers');
//        Route::get('results', 'DataController@results')->name('results');
//
    });
    //轮播图
    $router->resource('banners', 'BannerController');
    $router->post('banners/update', 'BannerController@update')->name('banners.update');
    $router->post('banners/set_open/{id}', 'BannerController@set_open')->where(['id' => '[0-9]+'])->name('banners.set_open');
    $router->post('banners/set_close/{id}', 'BannerController@set_close')->where(['id' => '[0-9]+'])->name('banners.set_close');
    $router->post('banners/delete_banner', 'BannerController@delete')->name('banners.delete_banner');
    //投诉
    $router->resource('complaints', 'ComplaintController');
    $router->post('complaints/update', 'ComplaintController@update')->name('complaints.update');
    $router->post('complaints/read/{id}', 'ComplaintController@read')->where(['id' => '[0-9]+'])->name('complaints.read');
    //公告
    $router->resource('news', 'NewsController');
    $router->post('news/delete_news', 'NewsController@delete')->name('news.delete_news');
    $router->post('news/add', 'NewsController@add')->name('news.add');
    $router->post('news/update_news', 'NewsController@updateNews')->name('news.update_news');
    $router->post('news/set_open/{id}', 'NewsController@set_open')->where(['id' => '[0-9]+'])->name('news.set_open');
    $router->post('news/set_close/{id}', 'NewsController@set_close')->where(['id' => '[0-9]+'])->name('news.set_close');
    //认购
    $router->group(array('prefix' => 'subscribes', 'namespace' => 'Subscribe'), function() use ($router) {
        $router->resource('subscribes', 'SubscribeController');
        $router->get('statics', 'SubscribeController@statics')->name('statics');

    });
    //统计
    $router->group(array('prefix' => 'statics', 'namespace' => 'Statics'), function() use ($router) {
        $router->resource('dk-stir', 'StaticsController');
        $router->get('money', 'StaticsController@money')->name('money');
        $router->get('addUsers', 'StaticsController@addUsers')->name('addUsers');
        $router->get('results', 'StaticsController@results')->name('results');

    });
    //系统设置
    $router->group(array('prefix' => 'system', 'namespace' => 'System'), function() use ($router) {
        $router->resource('system', 'SystemController');
        $router->post('system/update', 'SystemController@update')->name('system_update');
        $router->resource('c2c', 'C2cController');
        $router->post('c2c/update', 'C2cController@update')->name('c2c_update');
        $router->resource('datc', 'DatcController');
        $router->post('datc/update', 'DatcController@update')->name('datc_update');
    });
    //理财管理
    $router->group(array('prefix' => 'gem', 'namespace' => 'Gem'),function () use ($router) {
        //币增宝
        $router->resource('coin', 'CoinincreategemsController');
        $router->post('coin/edit_plan', 'CoinincreategemsController@editPlan')->name('coin.edit_plan');
        $router->post('coin/add', 'CoinincreategemsController@add')->name('coin.add');
        $router->post('coin/set_open/{id}', 'CoinincreategemsController@set_open')->where(['id' => '[0-9]+'])->name('coin.set_open');
        $router->post('coin/set_close/{id}', 'CoinincreategemsController@set_close')->where(['id' => '[0-9]+'])->name('coin.set_close');

        //理财宝
        $router->resource('financing', 'FinancinggemsController');
        $router->post('financing/edit_plan', 'FinancinggemsController@editPlan')->name('financing.edit_plan');
        $router->post('financing/add', 'FinancinggemsController@add')->name('financing.add');
        $router->post('financing/set_open/{id}', 'FinancinggemsController@set_open')->where(['id' => '[0-9]+'])->name('financing.set_open');
        $router->post('financing/set_close/{id}', 'FinancinggemsController@set_close')->where(['id' => '[0-9]+'])->name('financing.set_close');

    });

});
$router->any('/login','Admin\AdministratorController@login');
$router->get('/icon', function(){
    return view('admin.icon');
});


