<?php

namespace Pingu\Media\Providers;

use Illuminate\Database\Eloquent\Factory;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Media\Media;

class MediaServiceProvider extends ModuleServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /*
     * Where are the models located
     */
    protected $modelFolder = 'Entities';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('media.media', Media::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerModelSlugs(__DIR__.'/../'.$this->modelFolder);
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'media');
        $this->registerFactories();
        $this->registerAssets();
        $this->registerRules();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register js and css for this module
     */
    public function registerAssets()
    {
        \Asset::container('modules')->add('media-js', 'module-assets/Media/js/Media.js');
        \Asset::container('modules')->add('media-css', 'module-assets/Media/css/Media.css');
    }

    /**
     * Extends validator with custom rules
     */
    public function registerRules()
    {
        /**
         * url rule that check if an uploaded file has an extension
         */
        \Validator::extend('file_extension', function ($attribute, $file, $extensions, $validator) {
            $ext = $file->guessExtension();
            if(!in_array($ext, $extensions)){
                $validator->setCustomMessages([
                    $attribute.'.file_extension' => 'Extension is not valid, valid types are ('.implode(', ', $extensions).')'
                ]);
                return false;
            }
            return true;
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'media'
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/media');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'media');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'media');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
