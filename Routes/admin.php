<?php

use Pingu\Media\Entities\Media;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with admin which
| contains the "web" middleware group and the permission middleware "can:access admin area".
|
*/

/**
 * Medias
 */
Route::get(Media::getAdminUri('index'), ['uses' => 'MediaController@index'])
	->middleware('can:view media')
	->name('media.admin.media');
Route::get(Media::getAdminUri('create'), ['uses' => 'MediaController@create'])
	->middleware('can:upload media')
	->name('media.admin.media.create');
Route::post(Media::getAdminUri('store'), ['uses' => 'MediaController@store'])
	->middleware('can:upload media')
	->name('media.admin.media.create');

/**
 * Settings
 */
Route::get('/settings/media', ['uses' => 'MediaSettingsController@index'])
	->middleware('can:view media settings')
	->name('settings.admin.media');
Route::get('/settings/media/edit', ['uses' => 'MediaSettingsController@edit'])
	->middleware('can:edit media settings')
	->name('settings.admin.media.edit');
Route::post('/settings/media/edit', ['uses' => 'MediaSettingsController@update'])
	->middleware('can:edit media settings');