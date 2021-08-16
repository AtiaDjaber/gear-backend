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

Route::post('user/add', 'AuthController@store');
Route::put('user/put', 'AuthController@update');
Route::put('user/avatar', 'AuthController@avatar');

Route::post('article', 'ArticleController@store');
Route::get('article/{article}', 'ArticleController@show');
Route::get('article/{article}/comments', 'ArticleController@show_comments');
Route::get('article/{article}/best-comment', 'ArticleController@show_best_comment');
Route::get('articles', 'ArticleController@index');
Route::delete('article/{article}', 'ArticleController@destroy');

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
Route::post('driver/add', 'DriverController@store');
Route::put('rate', 'DriverController@rateDriver');

Route::get('users', 'AuthController@index');
Route::get('car/all', 'CarController@index');
