<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('contacts', 'ContactController@index');
Route::group(['prefix' => 'contacts'], function () {
    Route::post('store', 'ContactController@store');
    Route::post('update/{id}', 'ContactController@update');
    Route::delete('destroy/{id}', 'ContactController@destroy');
});
