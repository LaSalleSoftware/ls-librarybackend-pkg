<?php

/**
 * This file is part of the Lasalle Software library back-end package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

namespace Lasallesoftware\Librarybackend;

// LaSalle Software classes
use Lasallesoftware\Librarybackend\Commands\CustomdropCommand;
use Lasallesoftware\Librarybackend\Commands\CustomseedCommand;
use Lasallesoftware\Librarybackend\Commands\DeleteExpiredJWTCommand;
use Lasallesoftware\Librarybackend\Commands\DeleteExpiredLoginsCommand;
use Lasallesoftware\Librarybackend\Commands\DeleteExpiredUUIDCommand;
use Lasallesoftware\Librarybackend\Commands\DeleteActioneventsRecordsCommand;
use Lasallesoftware\Librarybackend\Commands\DusktestusebanallusersenvCommand;
use Lasallesoftware\Librarybackend\Commands\DusktestuseregularenvCommand;
use Lasallesoftware\Librarybackend\Commands\DusktestusetwofactorauthenvCommand;
use Lasallesoftware\Librarybackend\Commands\InstalleddomainseedCommand;
use Lasallesoftware\Librarybackend\Commands\LasalleinstalladminappCommand;
use Lasallesoftware\Librarybackend\Commands\LasalleinstallenvCommand;
use Lasallesoftware\Librarybackend\Commands\ReEncryptRunMeFirstCommand;
use Lasallesoftware\Librarybackend\Commands\ReEncryptRunMeSecondCommand;
use Lasallesoftware\Librarybackend\Firewall\Http\Middleware\Whitelist;
use Lasallesoftware\Librarybackend\JWT\Middleware\JWTMiddleware;

// Laravel Framework
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

// Laravel Nova 
use Laravel\Nova\Nova;


class LibrarybackendServiceProvider extends ServiceProvider
{
    use LibrarybackendPoliciesServiceProvider;

    /**
     * Register any application services.
     *
     * "Within the register method, you should only bind things into the service container.
     * You should never attempt to register any event listeners, routes, or any other piece of functionality within
     * the register method. Otherwise, you may accidentally use a service that is provided by a service provider
     * which has not loaded yet."
     * (https://laravel.com/docs/5.6/providers#the-register-method)
     */
    public function register()
    {
        $this->app->singleton('lslibrarybackend', function ($app) {
            return new LSLibrarybackend();
        });

        $this->registerArtisanCommands();
    }

    /**
     * Bootstrap any package services.
     *
     * "So, what if we need to register a view composer within our service provider?
     * This should be done within the boot method. This method is called after all other service providers
     * have been registered, meaning you have access to all other services that have been registered by the framework"
     * (https://laravel.com/docs/5.6/providers)
     */
    public function boot(Router $router)
    {
        $this->publishConfig();

        $this->loadMigrations();
      //  $this->loadDatabaseFactories();

        $this->loadTranslations();

        $this->registerPolicies();

        $this->registerMiddlewareRouter($router);
    }


     /**
     * Register middleware routes.
     *
     * @param Router $router
     */
    public function registerMiddlewareRouter($router)
    {
        // Add a middleware to the end of a middleware group
        $router->pushMiddlewareToGroup('web', Whitelist::class);
        $router->pushMiddlewareToGroup('jwt_auth', JWTMiddleware::class);
    }

    /**
     * Register the artisan commands for this package.
     */
    protected function registerArtisanCommands()
    {
        $this->app->bind('command.lslibrarybackend:customseeder', CustomseedCommand::class);
        $this->commands([
            'command.lslibrarybackend:customseeder',
        ]);

        $this->app->bind('command.lslibrarybackend:customdrop', CustomdropCommand::class);
        $this->commands([
            'command.lslibrarybackend:customdrop',
        ]);

        $this->app->bind('command.lslibrarybackend:installeddomainseeder', InstalleddomainseedCommand::class);
        $this->commands([
            'command.lslibrarybackend:installeddomainseeder',
        ]);

        $this->app->bind('command.lslibrarybackend:lasalleinstallenv', LasalleinstallenvCommand::class);
        $this->commands([
            'command.lslibrarybackend:lasalleinstallenv',
        ]);

        $this->app->bind('command.lslibrarybackend:lasalleinstalladminapp', LasalleinstalladminappCommand::class);
        $this->commands([
            'command.lslibrarybackend:lasalleinstalladminapp',
        ]);

        $this->app->bind('command.lslibrarybackend:deleteexpiredlogins', DeleteExpiredLoginsCommand::class);
        $this->commands([
            'command.lslibrarybackend:deleteexpiredlogins',
        ]);

        $this->app->bind('command.lslibrarybackend:deleteexpiredjwt', DeleteExpiredJWTCommand::class);
        $this->commands([
            'command.lslibrarybackend:deleteexpiredjwt',
        ]);

        $this->app->bind('command.lslibrarybackend:deleteexpireduuid', DeleteExpiredUUIDCommand::class);
        $this->commands([
            'command.lslibrarybackend:deleteexpireduuid',
        ]);

        $this->app->bind('command.lslibrarybackend:deleteactioneventsrecords', DeleteActioneventsRecordsCommand::class);
        $this->commands([
            'command.lslibrarybackend:deleteactioneventsrecords',
        ]);

        $this->app->bind('command.lslibrarybackend:dusktestusetwofactorauthenv', DusktestusetwofactorauthenvCommand::class);
        $this->commands([
            'command.lslibrarybackend:dusktestusetwofactorauthenv',
        ]);

        $this->app->bind('command.lslibrarybackend:dusktestuseregularenv', DusktestuseregularenvCommand::class);
        $this->commands([
            'command.lslibrarybackend:dusktestuseregularenv',
        ]);

        $this->app->bind('command.lslibrarybackend:dusktestusebanallusersenv', DusktestusebanallusersenvCommand::class);
        $this->commands([
            'command.lslibrarybackend:dusktestusebanallusersenv',
        ]);

        $this->app->bind('command.lslibrarybackend:reencryptrunmefirst', ReEncryptRunMeFirstCommand::class);
        $this->commands([
            'command.lslibrarybackend:reencryptrunmefirst',
        ]);

        $this->app->bind('command.lslibrarybackend:reencryptrunmesecond', ReEncryptRunMeSecondCommand::class);
        $this->commands([
            'command.lslibrarybackend:reencryptrunmesecond',
        ]);
    }

    /**
     * Publish this package's configuration file.
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/lasallesoftwarebackend-library.php' => config_path('lasallesoftwarebackend-library.php'),
        ], 'config');
    }

    /**
     * Load this package's migrations.
     */
    protected function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Load this package's database factories.
     */
    protected function loadDatabaseFactories()
    {
        $this->app
            ->make('Illuminate\Database\Eloquent\Factory')
            ->load(__DIR__.'/../database/factories')
        ;
    }

    /**
     * Load this package's translations.
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../translations/', 'lasallesoftwarelibrarybackend');
    }
}
