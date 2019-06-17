<?php

namespace Pingu\Media\Http\Controllers;

use Illuminate\Support\Collection;
use Pingu\Core\Http\Controllers\BaseController;
use Pingu\Settings\Contracts\SettingsController as SettingsControllerContract;
use Pingu\Settings\Http\Requests\SettingsRequest;
use Pingu\Settings\Traits\SettingsController;
use Route;

class MediaSettingsController extends BaseController implements SettingsControllerContract
{
	use SettingsController;

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
