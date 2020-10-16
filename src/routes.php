<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'requests', 'namespace' => 'Kakhura\LaravelCheckRequest\Http\Controllers\Request', 'middleware' => array_merge(['web'], config('kakhura.check-requests.use_auth_user_check') ? ['auth'] : [])], function () {
    Route::get('/check/{requestId}', 'RequestController@check');
});
