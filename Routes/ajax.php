<?php

use Pingu\Media\Entities\Media;

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

Route::get(Media::getAjaxUri('index'), ['uses' => 'MediaController@jsGridIndex'])
	->middleware('can:view medias');