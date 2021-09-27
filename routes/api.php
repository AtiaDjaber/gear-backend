<?php

use App\Http\Controllers\api\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

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
Route::put('user/put', 'AuthController@updateToken');
Route::post('user/avatar', 'AuthController@avatar');
Route::put('user/name', 'AuthController@updateName');


Route::get('student/getById', 'StudentController@getById');
Route::get('student', 'StudentController@index');
Route::post('student/add', 'StudentController@store');
Route::put('student/put', 'StudentController@put');
Route::delete('student/{id}', 'StudentController@deleteStudent');

Route::post('article/{article}/comment', 'CommentController@store');
Route::post('comment/{comment}/best-comment', 'CommentController@best_comment');
Route::get('comments', 'CommentController@index');
Route::get('comment/{comment}', 'CommentController@show');
Route::delete('comment/{comment}', 'CommentController@destroy');

Route::post('orders/add', 'OrdersController@store');
Route::get('orders', 'OrdersController@index');
Route::put('orders/put', 'OrdersController@update');
Route::get('ordersByUserId/{user_id}', 'OrdersController@getOrdersByUserId');
Route::get('ordersByDriverId/{driver_id}', 'OrdersController@getOrdersByDriverId');

Route::post('offer/add', 'OfferController@store');


Route::get('offers/{orders_id}', 'OfferController@index');
Route::get('offer/{orders_id}/{driver_id}', 'OfferController@getDriverOffer');

Route::put('driver/avatar', 'DriverController@avatar');
Route::get('drivers', 'DriverController@index');
Route::get('driver/get/{id}', 'DriverController@getById');
Route::put('driver/put', 'DriverController@updateToken');
Route::post('driver/add', 'DriverController@store');
Route::put('rate', 'DriverController@rateDriver');
Route::post('driver/avatar', 'DriverController@avatar');
Route::put('driver/name', 'DriverController@updateName');

Route::get('car/all', 'CarController@index');
