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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::any('login','LoginController@login');//登录

Route::get('/','LoginController@index');//首页

Route::resource('bills','BillsController');//账单
Route::get('export/bills','BillsController@export');//导出账单
Route::resource('estimates','EstimatesController');//评价
Route::resource('complaints','ComplaintsController');//商家反馈
Route::resource('mpusers','MpuserController');//管理员
Route::resource('orders','OrdersController');//订单管理
Route::get('export','OrdersController@export');//导出订单
Route::resource('staffs','StaffController');//员工管理
Route::resource('stores','StoresController');//店铺管理
Route::resource('fields','FieldsController');//场地管理
Route::resource('items','ItemsController');//添加运动场地 和  品类
Route::get('/tickets/list','ItemsController@tickets_list');//票卡类列表



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
