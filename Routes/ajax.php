<?php

use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media;
use Pingu\Media\Entities\MediaTransformer;
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
// Route::get(Media::getUri('index'), ['uses' => 'JsGridMediaController@jsGridIndex'])
// 	->middleware('can:view medias');
// Route::put(Media::getUri('update'), ['uses' => 'AjaxMediaController@update'])
// 	->middleware('can:edit medias');
// Route::delete(Media::getUri('delete'), ['uses' => 'AjaxMediaController@delete'])
// 	->middleware('can:delete medias');

/**
 * Media Types
 */
// Route::get(MediaType::getUri('index'), ['uses' => 'JsGridMediaTypeController@jsGridIndex'])
// 	->middleware('can:view media types');
// Route::put(MediaType::getUri('update'), ['uses' => 'AjaxMediaTypeController@update'])
// 	->middleware('can:edit media types');
// Route::delete(MediaType::getUri('delete'), ['uses' => 'AjaxMediaTypeController@delete'])
// 	->middleware('can:delete media types')
// 	->middleware('deletableModel:'.MediaType::routeSlug());

/**
 * Image styles
 */
// Route::get(ImageStyle::getUri('create'), ['uses' => 'AjaxImageStylesController@create'])
// 	->middleware('can:add images styles');
// Route::post(ImageStyle::getUri('store'), ['uses' => 'AjaxImageStylesController@store'])
// 	->middleware('can:add images styles');
// Route::get(ImageStyle::getUri('edit'), ['uses' => 'AjaxImageStylesController@edit'])
// 	->middleware('can:edit images styles');
// Route::put(ImageStyle::getUri('update'), ['uses' => 'AjaxImageStylesController@update'])
// 	->middleware('can:edit images styles');
// Route::delete(ImageStyle::getUri('delete'), ['uses' => 'AjaxImageStylesController@delete'])
// 	->middleware('can:delete images styles');

/**
 * Image styles transformations
 */
// Route::get(MediaTransformer::getUri('create'), ['uses' => 'AjaxTransformationsController@create'])
// 	->middleware('can:edit images styles');
// Route::post(MediaTransformer::getUri('store'), ['uses' => 'AjaxTransformationsController@store'])
// 	->middleware('can:edit images styles');
// Route::get(MediaTransformer::getUri('edit'), ['uses' => 'AjaxTransformationsController@edit'])
// 	->middleware('can:edit images styles');
// Route::put(MediaTransformer::getUri('update'), ['uses' => 'AjaxTransformationsController@update'])
// 	->middleware('can:edit images styles');
// Route::delete(MediaTransformer::getUri('delete'), ['uses' => 'AjaxTransformationsController@delete'])
// 	->middleware('can:edit images styles');
// Route::patch(MediaTransformer::getUri('patch'), ['uses' => 'AjaxTransformationsController@patch'])
// 	->middleware('can:edit images styles');