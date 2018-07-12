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

Route::post('event/create', 'APIController@createEvent');
Route::post('location/create', 'APIController@createLocation');
Route::post('event/ticket/create', 'APIController@createTicket');
Route::get('event/get_info', 'APIController@getEvent');
Route::post('transaction/purchase', 'APIController@purchaseTicket');
Route::get('transaction/get_info', 'APIController@getTransactionDetail');
