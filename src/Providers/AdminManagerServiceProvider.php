<?php

namespace Kontenta\AdminManagerImplementation\Providers;

use Illuminate\Support\ServiceProvider;
use Kontenta\AdminManager\Concerns\RegistersAdminRoutes;
use Illuminate\Auth\AuthManager;
use Kontenta\AdminManagerImplementation\AdminRouteManager;
use Kontenta\AdminManagerImplementation\AdminViewManager;
use Kontenta\AdminManagerImplementation\Http\Middleware\RedirectIfAuthenticated;
use Kontenta\AdminManagerImplementation\Http\Middleware\AuthenticateAdmin;

class AdminManagerServiceProvider extends ServiceProvider
{
    use RegistersAdminRoutes;

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->configure();

        $this->app->bindIf(
            \Kontenta\AdminManager\Contracts\AdminGuard::class,
            function ($app) {
                /**
                 * @var $auth AuthManager
                 */
                $auth = $app->make(AuthManager::class);
                return $auth->guard(config('admin.guard'));
            },
            true
        );

        $this->app->bindIf(
            \Kontenta\AdminManager\Contracts\AdminRouteManager::class,
            AdminRouteManager::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\AdminManager\Contracts\AdminViewManager::class,
            AdminViewManager::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\AdminManager\Contracts\AdminAuthenticateMiddleware::class,
            AuthenticateAdmin::class
        );

        $this->app->bindIf(
            \Kontenta\AdminManager\Contracts\AdminGuestMiddleware::class,
            RedirectIfAuthenticated::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerResources();

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
        }
    }

    /**
     * Setup the configuration.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/admin.php', 'admin');
    }

    /**
     * Register views and other resources.
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'admin');
    }

    /**
     * Register the admin routes to index and auth
     */
    protected function registerRoutes()
    {
        $this->registerAdminRoutes(__DIR__ . '/../../routes/admin.php');
        $this->registerAdminGuestRoutes(__DIR__ . '/../../routes/auth.php');
        if (config('admin.passwords')) {
            $this->registerAdminGuestRoutes(__DIR__ . '/../../routes/passwords.php');
        }
    }

    /**
     * Setup the resource publishing groups.
     */
    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/admin'),
        ], 'admin-views');
    }
}
