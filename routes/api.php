<?php

use Illuminate\Support\Facades\Route;


Route::post('/register', 'AuthController@register');
Route::post('/me', "AuthController@me")->middleware('auth:sanctum');
Route::post('/login',  'AuthController@login');


Route::group(["middleware" => 'api', "namespace" => "api"], function () {
    Route::post('categories', "CategoryController@index");
    Route::delete('category/{id}', "CategoryController@delete");
    Route::post('addCategory', "CategoryController@add");
});

Route::get('user/get/{id}', 'AuthController@getById');
Route::get('users', 'AuthController@index');
Route::post('user/add', 'AuthController@store');
Route::put('user/put', 'AuthController@put');
Route::put('user/updateToken', 'AuthController@updateToken');
Route::post('user/avatar', 'AuthController@avatar');
Route::put('user/name', 'AuthController@updateName');
Route::delete('user/delete/{id}', 'AuthController@remove');

Route::get('product/generate', 'ProductController@generate');
Route::get('product/getById', 'ProductController@getById');
Route::get('products', 'ProductController@index');
Route::post('product/add', 'ProductController@store');
Route::put('product/put', 'ProductController@put');
Route::delete('product/{id}', 'ProductController@deleteProduct');
Route::get('product/getconfigSubjById', 'ProductController@getconfig');
Route::get('Products/absences', 'ProductController@getAbsences');
Route::post('product/configSubjByProductForAttendance', 'ProductConfigController@getconfigSubjsByProductforAttendance');

Route::get('client/getById', 'ClientController@getById');
Route::get('clients', 'ClientController@index');
Route::post('client/add', 'ClientController@store');
Route::put('client/put', 'ClientController@put');
Route::delete('client/{id}', 'ClientController@deleteclient');

Route::get('sale/getById', 'SaleController@getById');
Route::get('sales', 'SaleController@index');
Route::post('sale/add', 'SaleController@store');
Route::put('sale/put', 'SaleController@put');
Route::delete('sale/{id}', 'SaleController@remove');

Route::get('levelYear/getById', 'LevelYearController@getById');
Route::get('levelYears', 'LevelYearController@index');
Route::post('levelYear/add', 'LevelYearController@store');
Route::put('levelYear/put', 'LevelYearController@put');
Route::delete('levelYear/{id}', 'LevelYearController@deletesale');
Route::post('subjLevelYear/add', 'LevelYearSubjController@store');


Route::post('session/add', 'SessionController@store');
Route::get('sessions/{start}/{end}', 'SessionController@index');
Route::get('product/sessionsByProductId', 'SessionController@getSessionsByProductId');

Route::delete('session/{id}', 'SessionController@remove');
Route::delete('session/configId/{id}/{start}', 'SessionController@removeSubj');


Route::get('settings', 'ConfigController@index');
Route::put('setting/put', 'ConfigController@put');

Route::get('notificationsProducts', 'ProductConfigController@getNotifications');

Route::get('expenses', 'ExpenseController@index');
Route::get('expenses/expansesAnalytics', 'ExpenseController@getExpansesAnalytic');
Route::post('expense/add', 'ExpenseController@store');
Route::put('expense/put', 'ExpenseController@put');
Route::delete('expense/{id}', 'ExpenseController@remove');

Route::get('attendances', 'ChartController@index');
Route::get('attendance/getBenifitsclientsChart', 'ChartController@getclientsBenifitsChart');
Route::get('attendance/getBenifitsclients', 'ChartController@getclientsBenifits');
Route::get('chart/yearMonthChart', 'ChartController@getYearMonthChart');
Route::get('attendance/schoolBenifitPeriod', 'ChartController@getSchoolBenifitPeriod');
Route::get('attendance/getBenifitByclientId', 'ChartController@getclientBenifitById');
Route::post('attendance/add', 'ChartController@store');
Route::delete('attendance/{id}', 'ChartController@remove');


Route::get('payments', 'PaymentController@index');
Route::get('payments/getByclientId', 'PaymentController@getById');
Route::get('payments/getPayementconfiged', 'PaymentController@getconfiged');
Route::post('payment/add', 'PaymentController@store');
Route::put('payment/put', 'PaymentController@put');
Route::delete('payment/{id}', 'PaymentController@remove');

Route::post('subscription/add', 'SubscriptionController@store');
Route::get('subscriptions', 'SubscriptionController@index');
Route::get('subscriptions/subscriptionAmount', 'SubscriptionController@getSubscriptionAmount');
Route::get('subscriptions/getByProductId', 'SubscriptionController@getById');
Route::get('subscriptions/getProductconfiged', 'SubscriptionController@getconfiged');
Route::put('subscription/put', 'SubscriptionController@put');
Route::delete('subscription/{id}', 'SubscriptionController@remove');
