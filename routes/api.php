<?php

use Illuminate\Support\Facades\Route;


Route::post('/register', 'AuthController@register');
Route::post('/me', "AuthController@me")->middleware('auth:sanctum');
Route::post('/login',  'AuthController@login');

// Route::middleware('api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::middleware('api')->get('/categories', function (Request $request) {
//     return CategoryController@index;
// });

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
Route::get('product/getGroupSubjById', 'ProductController@getGroup');
Route::get('Products/absences', 'ProductController@getAbsences');
Route::post('product/groupSubjByProductForAttendance', 'ProductGroupController@getGroupSubjsByProductforAttendance');

Route::get('client/getById', 'ClientController@getById');
Route::get('clients', 'ClientController@index');
Route::post('client/add', 'ClientController@store');
Route::put('client/put', 'ClientController@put');
Route::delete('client/{id}', 'ClientController@deleteclient');

Route::get('subject/getById', 'SubjectController@getById');
Route::get('subjects', 'SubjectController@index');
Route::post('subject/add', 'SubjectController@store');
Route::put('subject/put', 'SubjectController@put');
Route::delete('subject/{id}', 'SubjectController@deleteSubject');


Route::get('levelYear/getById', 'LevelYearController@getById');
Route::get('levelYears', 'LevelYearController@index');
Route::post('levelYear/add', 'LevelYearController@store');
Route::put('levelYear/put', 'LevelYearController@put');
Route::delete('levelYear/{id}', 'LevelYearController@deleteSubject');
Route::post('subjLevelYear/add', 'LevelYearSubjController@store');


Route::post('session/add', 'SessionController@store');
Route::get('sessions/{start}/{end}', 'SessionController@index');
Route::get('product/sessionsByProductId', 'SessionController@getSessionsByProductId');

Route::delete('session/{id}', 'SessionController@remove');
Route::delete('session/groupId/{id}/{start}', 'SessionController@removeSubj');


Route::get('groups', 'GroupController@index');
Route::get('group/getById', 'GroupController@getById');
Route::post('group/add', 'GroupController@store');
Route::put('group/put', 'GroupController@put');
Route::delete('group/{id}', 'GroupController@remove');
Route::get('groups/groupSubjByclientId', 'GroupController@getGroupSubjById');
Route::get('group/Products', 'GroupController@getProductByGroupId');


Route::get('client/getGroupSubjById', 'ClientController@getGroupSubjById');
Route::get('client/getProductsById', 'ClientController@getProductsById');


Route::get('notificationsProducts', 'ProductGroupController@getNotifications');

Route::get('sgs', 'ProductGroupController@getAllGroupSubjs');
Route::get('sg/getGroupSubjByProduct', 'ProductGroupController@getGroupSubjsByProduct');
Route::post('sg/add', 'ProductGroupController@store');
Route::post('sg/ProductsByGroups', 'ProductGroupController@getProductsByGroups');


Route::get('expenses', 'ExpenseController@index');
Route::get('expenses/expansesAnalytics', 'ExpenseController@getExpansesAnalytic');
Route::post('expense/add', 'ExpenseController@store');
Route::put('expense/put', 'ExpenseController@put');
Route::delete('expense/{id}', 'ExpenseController@remove');

Route::get('attendances', 'AttendanceController@index');
Route::get('attendance/getBenifitsclientsChart', 'AttendanceController@getclientsBenifitsChart');
Route::get('attendance/getBenifitsclients', 'AttendanceController@getclientsBenifits');
Route::get('attendance/schoolBenifitChart', 'AttendanceController@getSchoolBenifitChart');
Route::get('attendance/schoolBenifitPeriod', 'AttendanceController@getSchoolBenifitPeriod');
Route::get('attendance/getBenifitByclientId', 'AttendanceController@getclientBenifitById');
Route::post('attendance/add', 'AttendanceController@store');
Route::delete('attendance/{id}', 'AttendanceController@remove');


Route::get('payments', 'PaymentController@index');
Route::get('payments/getByclientId', 'PaymentController@getById');
Route::get('payments/getPayementGrouped', 'PaymentController@getGrouped');
Route::post('payment/add', 'PaymentController@store');
Route::put('payment/put', 'PaymentController@put');
Route::delete('payment/{id}', 'PaymentController@remove');

Route::post('subscription/add', 'SubscriptionController@store');
Route::get('subscriptions', 'SubscriptionController@index');
Route::get('subscriptions/subscriptionAmount', 'SubscriptionController@getSubscriptionAmount');
Route::get('subscriptions/getByProductId', 'SubscriptionController@getById');
Route::get('subscriptions/getProductGrouped', 'SubscriptionController@getGrouped');
Route::put('subscription/put', 'SubscriptionController@put');
Route::delete('subscription/{id}', 'SubscriptionController@remove');
