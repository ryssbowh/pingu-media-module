<?php

namespace Pingu\Media\Providers;

use Illuminate\Database\Eloquent\Factory;
use Illuminate\Http\UploadedFile;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\Media\Bundles\MediaBundle;
use Pingu\Media\Config\MediaSettings;
use Pingu\Media\Entities\FieldMedia;
use Pingu\Media\Entities\ImageStyle as ImageStyleModel;
use Pingu\Media\Entities\Media as MediaModel;
use Pingu\Media\Entities\MediaFolder;
use Pingu\Media\Entities\MediaTransformer;
use Pingu\Media\Entities\MediaType as MediaTypeModel;
use Pingu\Media\Forms\Fields\UploadMedia;
use Pingu\Media\ImageStyle;
use Pingu\Media\Infos\MediaInfo;
use Pingu\Media\Media;
use Pingu\Media\MediaType;
use Pingu\Media\Observers\MediaObserver;
use Pingu\Media\Support\Fields\Media as MediaField;
use Pingu\Media\Transformers\Orientate;
use Pingu\Media\Transformers\Resize;
use Pingu\Media\Validation\MediaRules;

class MediaServiceProvider extends ModuleServiceProvider
{
    protected $entities = [
        MediaModel::class,
        MediaTypeModel::class,
        ImageStyleModel::class,
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
        $this->app->singleton('media.mediaType', MediaType::class);
        $this->app->singleton('media.imageStyle', ImageStyle::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(AuthServiceProvider::class);
        (new MediaBundle)->register();
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
        $this->loadModuleViewsFrom(__DIR__ . '/../Resources/views', 'media');
        $this->registerFactories();
        $this->registerAssets();
        $this->registerRules();
        \FormField::registerFields(UploadMedia::class);
        \Media::registerTransformer(Resize::class);
        \Media::registerTransformer(Orientate::class);
        \Infos::registerProvider(MediaInfo::class);
        \Field::registerBundleFields(FieldMedia::class);
        MediaField::register();
        MediaModel::observe(MediaObserver::class);
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
        \Validator::extend('file_extension', MediaRules::class.'@fileExtension');
        \Validator::extend('unique_extensions', MediaRules::class.'@uniqueExtensions');
        \Validator::extend('defined_extension', MediaRules::class.'@definedExtension');
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
            __DIR__.'/../Config/config.php' => config_path('media.php')
        ], 'media-config');
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

}
