<?php
Route::group('admin',function(){
   Route::get('actionWithHelloJob','admin/Jobtest/actionWithHelloJob');
    // Route::post('actionWithHelloJob','admin/Jobtest/actionWithMultiTask');
    Route::group('base',function(){
        Route::post('login','admin/base/login');
        Route::post('addto','admin/base/addto');
        Route::post('image','admin/base/image');
        Route::post('add','admin/base/add');
    });
    Route::group('user',function (){
       Route::post('getList','admin/user/getList');
    });
    Route::group('Legend',function (){
        Route::post('auto','admin/Legend/auto');
    });
    Route::group('type',function (){
        Route::post('auto','admin/type/auto');
        Route::post('getList','admin/type/getList');
        Route::post('getLists','admin/type/getLists');
        Route::post('cartoonAdd','admin/type/cartoonAdd');
        Route::post('cartoonList','admin/type/cartoonList');
    });
});
