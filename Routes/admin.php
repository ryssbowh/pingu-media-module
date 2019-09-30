<?php

use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaTransformer;
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
Route::get(Media::getUri('index'), ['uses' => 'JsGridMediaController@index'])
	->middleware('can:view media')
	->name('media.admin.media');

Route::get(Media::getUri('create'), ['uses' => 'AdminMediaController@create', 'friendly' => 'Admin: Upload Media'])
	->middleware('can:upload media')
	->name('media.admin.media.create');
Route::post(Media::getUri('store'), ['uses' => 'AdminMediaController@store'])
	->middleware('can:upload media');

Route::get(Media::getUri('edit'), ['uses' => 'AdminMediaController@edit'])
	->middleware('can:edit media');
Route::put(Media::getUri('update'), ['uses' => 'AdminMediaController@update'])
	->middleware('can:edit media');

Route::get(Media::getUri('delete'), ['uses' => 'AdminMediaController@confirmDelete'])
	->middleware('can:delete media');
Route::delete(Media::getUri('delete'), ['uses' => 'AdminMediaController@delete'])
	->middleware('can:delete media');

/**
 * Media types
 */

Route::get(MediaType::getUri('index'), ['uses' => 'JsGridMediaTypeController@index'])
	->middleware('can:view media types')
	->name('media.admin.mediaTypes');
Route::get(MediaType::getUri('create'), ['uses' => 'AdminMediaTypeController@create'])
	->middleware('can:create media types')
	->name('media.admin.mediaTypes.create');
Route::post(MediaType::getUri('store'), ['uses' => 'AdminMediaTypeController@store'])
	->middleware('can:create media types');
Route::get(MediaType::getUri('edit'), ['uses' => 'AdminMediaTypeController@edit'])
	->middleware('can:edit media types');
Route::put(MediaType::getUri('update'), ['uses' => 'AdminMediaTypeController@update'])
	->middleware('can:edit media types');

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

/**
 * Image styles
 */
Route::get(ImageStyle::getUri('create'), ['uses' => 'AdminImageStylesController@create'])
	->middleware('can:add images styles');
Route::post(ImageStyle::getUri('store'), ['uses' => 'AdminImageStylesController@store'])
	->name('media.admin.imagesStyles.create')
	->middleware('can:add images styles');
Route::get(ImageStyle::getUri('index'), ['uses' => 'AdminImageStylesController@index'])
	->middleware('can:view images styles')
	->name('media.admin.imagesStyles');
Route::get(ImageStyle::getUri('edit'), ['uses' => 'AdminImageStylesController@edit'])
	->middleware('can:edit images styles');
Route::put(ImageStyle::getUri('update'), ['uses' => 'AdminImageStylesController@update'])
	->middleware('can:edit images styles');
Route::get(ImageStyle::getUri('delete'), ['uses' => 'AdminImageStylesController@confirmDelete'])
	->middleware('can:delete images styles');
Route::delete(ImageStyle::getUri('delete'), ['uses' => 'AdminImageStylesController@delete'])
	->middleware('can:delete images styles');

/**
 * Transformers
 */
Route::get(MediaTransformer::getUri('create'), ['uses' => 'AdminTransformationsController@create'])
	->middleware('can:edit images styles');
Route::post(MediaTransformer::getUri('store'), ['uses' => 'AdminTransformationsController@store'])
	->middleware('can:edit images styles');
Route::get(MediaTransformer::getUri('index'), ['uses' => 'AdminTransformationsController@index'])
	->middleware('can:edit images styles');
Route::get(MediaTransformer::getUri('delete'), ['uses' => 'AdminTransformationsController@confirmDelete'])
	->middleware('can:edit images styles');
Route::delete(MediaTransformer::getUri('delete'), ['uses' => 'AdminTransformationsController@delete'])
	->middleware('can:edit images styles');
Route::get(MediaTransformer::getUri('edit'), ['uses' => 'AdminTransformationsController@edit'])
	->middleware('can:edit images styles');
Route::put(MediaTransformer::getUri('update'), ['uses' => 'AdminTransformationsController@update'])
	->middleware('can:edit images styles');
Route::patch(MediaTransformer::getUri('patch'), ['uses' => 'AdminTransformationsController@patch'])
	->middleware('can:edit images styles');