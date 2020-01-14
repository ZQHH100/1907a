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

Route::get('/', function () {
    return view('welcome');
});
//微信开发者配置服务器
// Route::any('/weixin/index','Weixin@index');

Route::view('/login','login');
Route::get('/logindo','admin\Login@logindo');//

Route::prefix('weixin')->group(function(){
		Route::get('/','admin\Weixin@index');
		Route::get('/indes','admin\Weixin@indes');
		Route::get('/index_v1','admin\Weixin@index_v1');
		Route::get('/graph_echarts','admin\Weixin@graph_echarts');


});
Route::prefix('student')->group(function(){
		Route::get('/','admin\Student@index');

		Route::get('/create','admin\Student@create');
		Route::post('/store','admin\Student@store');
		Route::get('/aaa','admin\Student@aaa');

});
Route::prefix('newss')->group(function(){
		Route::get('/','admin\Newss@index');

		Route::get('/create','admin\Newss@create');
		Route::post('/store','admin\Newss@store');
		Route::get('/edit/{id}','admin\newss@edit');
		Route::post('/update/{id}','admin\newss@update');//执行修改
		Route::get('delete/{id}','admin\newss@destroy');//执行删除
		Route::get('show/{id}','admin\newss@show');


});
Route::prefix('channel')->group(function(){
		Route::get('/','admin\Channel@index');
		Route::get('create','admin\Channel@create');
		Route::post('store','admin\Channel@store');
		Route::get('aph','admin\Channel@aph');
		Route::get('weather','admin\Channel@weather');
		Route::get('getweather','admin\Channel@getweather');
});
	
Route::get('img','admin\ImgController@img');
Route::get('sendAllOpenId','Weixin@sendAllOpenId');
Route::get('test','Weixin@test');