<?php

namespace Pingu\Media\Providers;

use Illuminate\Database\Eloquent\Factory;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Media\Config\MediaSettings;
use Pingu\Media\Entities\ImageStyle;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Entities\MediaType;
use Pingu\Media\Infos\MediaInfo;
use Pingu\Media\Media;
use Pingu\Media\Transformers\Orientate;
use Pingu\Media\Transformers\Resize;

class MediaServiceProvider extends ModuleServiceProvider
{
    protected $entities = [
        MediaModel::class,
        MediaType::class,
        ImageStyle::class,
        MediaTransformer::class
    ];

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
        $this->registerEntities($this->entities);
        \Settings::register(new MediaSettings, $this->app);
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'media');
        $this->registerFactories();
        $this->registerAssets();
        $this->registerRules();
        \Media::registerTransformer(Resize::class);
        \Media::registerTransformer(Orientate::class);
        \Infos::registerProvider(MediaInfo::class);
    }

    /**
     * Register js and css for this module
     */
    public function registerAssets()
    {
        \Asset::container('modules')
            ->add('media-js', 'module-assets/Media.js')
            ->add('media-css', 'module-assets/Media.css');
    }

    /**
     * Extends validator with custom rules
     */
    public function registerRules()
    {
        /**
         * Rule that checks if an uploaded file is a defined extension
         */
        \Validator::extend(
            'file_extension', function ($attribute, $file, $extensions, $validator) {
                $ext = $file->guessExtension();
                if(!in_array($ext, $extensions)) {
                    $validator->setCustomMessages(
                        [
                        $attribute.'.file_extension' => 'Extension is not valid, valid types are ('.implode(', ', $extensions).')'
                        ]
                    );
                    return false;
                }
                return true;
            }
        );

        /**
         * Checks if a name is unique for a media
         */
        \Validator::extend(
            'unique_media_name', function ($attribute, $name, $ids, $validator) {
                $media = MediaModel::findOrFail($ids[0]);
                if($media->name == $name) { return true;
                }
                return !$media->media_type->hasMediaCalled($name, $media);
            }
        );

        /**
         * Checks if an array of extensions is not already in use in other media types
         */
        \Validator::extend(
            'unique_extensions', function ($attribute, $extensions, $ids, $validator) {
                $ignore = null;
                if (isset($ids[0])) {
                    $ignore = MediaType::findOrFail($ids[0]);
                }
                $defined = \Media::getAvailableFileExtensions($ignore);
                $duplicates = [];
                $extensions = array_map(
                    function ($ext) {
                        return trim($ext);
                    }, explode(',', trim($extensions, ', '))
                );
                foreach ($extensions as $ext) {
                    if (in_array($ext, $defined)) {
                        $duplicates[] = $ext;
                    }
                }
                if ($duplicates) {
                    $validator->setCustomMessages(
                        [
                        $attribute.'.unique_extensions' => 'Extensions '.implode(',', $duplicates).' are already defined in other media types'
                        ]
                    );
                    return false;
                }
                return true;
            }
        );
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
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('module-media.php')
        ], 'config');
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
