<?php

use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaType;

/*
|--------------------------------------------------------------------------
| Ajax Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ajax web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group prefixed with ajax which
| contains the "ajax" middleware group.
|
*/

/**
 * Media
 */
Route::get(Media::getUri('index'), ['uses' => 'JsGridMediaController@jsGridIndex'])
	->middleware('can:view medias');
Route::put(Media::getUri('update'), ['uses' => 'AjaxMediaController@update'])
	->middleware('can:edit medias');
Route::delete(Media::getUri('delete'), ['uses' => 'AjaxMediaController@delete'])
	->middleware('can:delete medias');

/**
 * Media Types
 */
Route::get(MediaType::getUri('index'), ['uses' => 'JsGridMediaTypeController@jsGridIndex'])
	->middleware('can:view media types');
Route::put(MediaType::getUri('update'), ['uses' => 'AjaxMediaTypeController@update'])
	->middleware('can:edit media types');
Route::delete(MediaType::getUri('delete'), ['uses' => 'AjaxMediaTypeController@delete'])
	->middleware('can:delete media types')
	->middleware('deletableModel:'.MediaType::routeSlug());