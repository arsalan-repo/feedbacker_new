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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::prefix('/admin')->group(function () {

    Route::get('/', 'admin\Auth\LoginController@showLoginForm')->name('login');
    Route::post('/','admin\Auth\LoginController@login');


    //password reset
    Route::post('password/email', 'admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset', 'admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/reset', 'admin\Auth\ResetPasswordController@reset');
    Route::get('password/reset/{token}', 'admin\Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');

    Route::prefix('/dashboard')->group(function(){

        Route::post('/','admin\Auth\LoginController@logout');
        Route::get('/','AdminController@index');

        Route::get('getAdmin','AdminController@getAdmin');

        Route::post('saveSurvey','SurveyController@store');

        Route::get('getAllSurveys','SurveyController@allResources');

        Route::get('/survey/result/{surveyId}','SurveyController@showSurveyResult');
    });
});

Route::prefix('view-survey')->group(function (){
    Route::post('saveSurveyResult','SurveyController@saveSurveyResult');
    Route::get('/{url}','SurveyController@viewSurvey');
    Route::post('/getSurvey','SurveyController@getSurvey');
});
