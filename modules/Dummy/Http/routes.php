<?php

Route::group(['middleware' => 'web', 'prefix' => 'dummy', 'namespace' => 'Modules\Dummy\Http\Controllers'], function()
{
	Route::get('/', 'DummyController@index');
});