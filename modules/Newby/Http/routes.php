<?php

Route::group(['middleware' => 'web', 'prefix' => 'newby', 'namespace' => 'Modules\Newby\Http\Controllers'], function()
{
	Route::get('/', 'NewbyController@index');
});