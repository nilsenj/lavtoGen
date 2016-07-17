<?php

namespace Core\Modular;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Pingpong\Support\Stub;

class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();

        $this->registerModules();
        
        if (!class_exists('CreateModulesTable')) {
            
            $this->publishes([
                __DIR__.'/../modular/src/migrations/create_modules_table.php.stub' => database_path('migrations/2016_06_19_183613_create_modules_table.php'),
            ], 'migrations');
        }
        if (!class_exists('CreateLikesTable')) {
            
            $this->publishes([
                __DIR__.'/../modular/src/migrations/create_likes_table.php.stub' => database_path('migrations/2016_06_19_183644_create_likes_table.php'),
            ], 'migrations');
        }
        
        if (!class_exists('CreateCounterTable')) {

            $this->publishes([
                __DIR__.'/../modular/src/migrations/create_counter_table.php.stub' => database_path('migrations/2016_06_25_183644_create_counter_table.php'),
            ], 'migrations');
        }
    }

    /**
     * Register all modules.
     */
    protected function registerModules()
    {
        $this->app->register('Core\Modular\Providers\BootstrapServiceProvider');
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        $this->app->booted(function ($app) {
            Stub::setBasePath(__DIR__.'/Commands/stubs');

            if ($app['modules']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['modules']->config('stubs.path'));
            }
        });
    }

    /**
     * Register package's namespaces.
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__.'/src/config/config.php';
        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([
            $configPath => config_path('modules.php')
        ], 'config');
    }

    /**
     * Register laravel html package.
     */
    protected function registerHtml()
    {
        $this->app->register('Collective\Html\HtmlServiceProvider');

        $aliases = [
            'HTML' => 'Collective\Html\HtmlFacade',
            'Form' => 'Collective\Html\FormFacade',
            'Module' => 'Core\Modular\Facades\Module',
            'ModuleLayer' => 'Core\Modular\Facades\ModuleLayer',
            'Counter' => 'Core\Modular\Facades\Counter',
            'Like' => 'Core\Modular\Facades\Like',
            'LikeCounter' => 'Core\Modular\Facades\LikeCounter',
            'UserCounter' => 'Core\Modular\Facades\UserCounter',
        ];

        AliasLoader::getInstance($aliases)->register();
    }

    /**
     * Register the service provider.
     */
    protected function registerServices()
    {
        $this->app->singleton('modules', function ($app) {
            $path = $app['config']->get('modules.paths.modules');

            return new Repository($app, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('modules');
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(__NAMESPACE__.'\\Providers\\ConsoleServiceProvider');
        $this->app->register('Core\Modular\Providers\ContractsServiceProvider');
    }
}
