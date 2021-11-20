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
Route::put('user/put', 'AuthController@put');
Route::put('user/updateToken', 'AuthController@updateToken');
Route::post('user/avatar', 'AuthController@avatar');
Route::put('user/name', 'AuthController@updateName');
Route::delete('user/delete/{id}', 'AuthController@remove');


Route::get('student/generate', 'StudentController@generate');
Route::get('student/getById', 'StudentController@getById');
Route::get('students', 'StudentController@index');
Route::post('student/add', 'StudentController@store');
Route::put('student/put', 'StudentController@put');
Route::delete('student/{id}', 'StudentController@deleteStudent');

Route::get('teacher/getById', 'TeacherController@getById');
Route::get('teachers', 'TeacherController@index');
Route::post('teacher/add', 'TeacherController@store');
Route::put('teacher/put', 'TeacherController@put');
Route::delete('teacher/{id}', 'TeacherController@deleteTeacher');

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
Route::get('sessions', 'SessionController@index');

Route::get('groups', 'GroupController@index');
Route::get('group/getById', 'GroupController@getById');
Route::post('group/add', 'GroupController@store');
Route::put('group/put', 'GroupController@put');
Route::delete('group/{id}', 'GroupController@remove');

// Route::post('groupTeacher/add', 'GroupTeacherController@store');
// Route::get('groupTeachers', 'GroupTeacherController@getAllData');
// Route::get('groupTeachers/getGroupSubjsByTeacher', 'GroupTeacherController@getGroupSubByTeacher');
// Route::get('groupTeachers/getStudentsByTeacher', 'GroupTeacherController@getStudentsByTeacher');


Route::get('teacher/getGroupSubjById', 'TeacherController@getGroupSubjById');
Route::get('teacher/getStudentsById', 'TeacherController@getStudentsById');

Route::get('student/getGroupSubjById', 'StudentController@getGroupById');

Route::get('sg', 'StudentGroupController@getAllGroupSubjs');
Route::get('sg/getGroupSubjByStudent', 'StudentGroupController@getGroupSubjsByStudent');
// Route::get('sg/getGroupSubjByBarcode', 'StudentGroupController@getGroupSubjsByStudentBarcode');
// Route::get('sg/getAllGroupSubj', 'StudentGroupController@getAllGroupSubjs');

Route::post('sg/add', 'StudentGroupController@store');


Route::get('expenses', 'ExpenseController@index');
Route::post('expense/add', 'ExpenseController@store');
Route::put('expense/put', 'ExpenseController@put');
Route::delete('expense/{id}', 'ExpenseController@remove');

Route::get('attendances', 'AttendanceController@index');
Route::get('attendance/getBenifitsTeachers', 'AttendanceController@getTeachersBenifits');
Route::get('attendance/getBenifitByTeacherId', 'AttendanceController@getTeacherBenifitById');
Route::post('attendance/add', 'AttendanceController@store');



Route::get('payments', 'PaymentController@index');
Route::get('payments/getByTeacherId', 'PaymentController@getById');
Route::get('payments/getPayementGrouped', 'PaymentController@getGrouped');

Route::post('payment/add', 'PaymentController@store');
Route::put('payment/put', 'PaymentController@put');
Route::delete('payment/{id}', 'PaymentController@remove');

Route::post('subscription/add', 'SubscriptionController@store');
Route::get('subscriptions', 'SubscriptionController@index');
Route::get('subscriptions/subscriptionAmount', 'SubscriptionController@getSubscriptionAmount');
Route::get('subscriptions/getByStudentId', 'SubscriptionController@getById');
Route::get('subscriptions/getStudentGrouped', 'SubscriptionController@getGrouped');
Route::put('subscription/put', 'SubscriptionController@put');
Route::delete('subscription/{id}', 'SubscriptionController@remove');
