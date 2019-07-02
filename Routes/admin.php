<?php

use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaType;

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
Route::get(Media::getAdminUri('index'), ['uses' => 'JsGridMediaController@index'])
	->middleware('can:view media')
	->name('media.admin.media');

Route::get(Media::getAdminUri('create'), ['uses' => 'AdminMediaController@create'])
	->middleware('can:upload media')
	->name('media.admin.media.create');
Route::post(Media::getAdminUri('store'), ['uses' => 'AdminMediaController@store'])
	->middleware('can:upload media');

Route::get(Media::getAdminUri('edit'), ['uses' => 'AdminMediaController@edit'])
	->middleware('can:edit media');
Route::put(Media::getAdminUri('update'), ['uses' => 'AdminMediaController@update'])
	->middleware('can:edit media');

Route::get(Media::getAdminUri('confirmDelete'), ['uses' => 'AdminMediaController@confirmDelete'])
	->middleware('can:delete media');
Route::delete(Media::getAdminUri('delete'), ['uses' => 'AdminMediaController@delete'])
	->middleware('can:delete media');

/**
 * Media types
 */

Route::get(MediaType::getAdminUri('index'), ['uses' => 'JsGridMediaTypeController@index'])
	->middleware('can:view media types')
	->name('media.admin.mediaTypes');
Route::get(MediaType::getAdminUri('create'), ['uses' => 'AdminMediaTypeController@create'])
	->middleware('can:create media types')
	->name('media.admin.mediaTypes.create');
Route::post(MediaType::getAdminUri('store'), ['uses' => 'AdminMediaTypeController@store'])
	->middleware('can:create media types');

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