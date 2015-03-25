<?php


Route::post( '/login', 'RpbeAuth\Http\Controllers\AuthController@login' );

Route::group(['middleware' => '\RpbeAuth\Http\Middleware\Services\TokenUserService'] ,function() {
    Route::post( '/logout', 'RpbeAuth\Http\Controllers\AuthController@logout' );
});


