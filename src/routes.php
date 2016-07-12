<?php
/**
 * 作者: 神奇的胖子  http://zhangxihai.cn
 * 时间: 2016/2/16 14:58
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Juhedao\LaravelAdminGenerator\Http\Controllers'], function () {
    Route::get('/generator','AdminGeneratorController@getIndex');
    Route::controller('generator','AdminGeneratorController');
});
