<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Settings\Http\Controllers\SettingsController;
use Pingu\Settings\Http\Requests\SettingsRequest;
use Route;

class AdminMediaSettingsController extends SettingsController
{
	/**
	 * @inheritDoc
	 */
	public function getSection()
	{
		return request()->segments()[2];
	}

	/**
	 * @inheritDoc
	 */
	public function getUpdateRoute()
	{
		return route_by_name('settings.admin.media.edit');
	}

	/**
     * @inheritDoc
     */
    public function afterUpdatingSettings(SettingsRequest $request, Collection $settings)
    {
    	return redirect()->route('settings.admin.media');
    }

    /**
     * @inheritDoc
     */
    public function canEdit()
    {
    	return \Auth::user()->hasPermissionTo('edit media settings');
    }
}
