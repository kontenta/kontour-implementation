<?php

namespace Kontenta\KontourSupport\Providers;

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Kontenta\KontourSupport\AdminRouteManager;
use Kontenta\KontourSupport\AdminViewManager;
use Kontenta\KontourSupport\AdminWidgetManager;
use Kontenta\KontourSupport\Http\Middleware\AuthenticateAdmin;
use Kontenta\KontourSupport\Http\Middleware\RedirectIfAuthenticated;
use Kontenta\KontourSupport\RecentVisitsRepository;
use Kontenta\KontourSupport\Widgets\ItemHistoryWidget;
use Kontenta\KontourSupport\Widgets\CrumbtrailWidget;
use Kontenta\KontourSupport\Widgets\MenuWidget;
use Kontenta\KontourSupport\Widgets\PersonalRecentVisitsWidget;
use Kontenta\KontourSupport\Widgets\TeamRecentVisitsWidget;
use Kontenta\KontourSupport\Widgets\UserAccountWidget;
use Kontenta\Kontour\Concerns\RegistersAdminRoutes;
use Kontenta\Kontour\Concerns\RegistersAdminWidgets;

class KontourServiceProvider extends ServiceProvider
{
    use RegistersAdminRoutes, RegistersAdminWidgets;

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->configure();

        $this->app->bindIf(
            'kontour.guard',
            function ($app) {
                /**
                 * @var $auth AuthManager
                 */
                $auth = $app->make(AuthManager::class);
                return $auth->guard(config('kontour.guard'));
            },
            true
        );

        $this->app->when(WidgetManager::class)->needs(\Illuminate\Contracts\Auth\Guard::class)->give('kontour.guard');

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\AdminRouteManager::class,
            AdminRouteManager::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\AdminViewManager::class,
            AdminViewManager::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\AdminWidgetManager::class,
            AdminWidgetManager::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\AdminAuthenticateMiddleware::class,
            AuthenticateAdmin::class
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\AdminGuestMiddleware::class,
            RedirectIfAuthenticated::class
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\MenuWidget::class,
            MenuWidget::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\CrumbtrailWidget::class,
            CrumbtrailWidget::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\UserAccountWidget::class,
            UserAccountWidget::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\RecentVisitsRepository::class,
            RecentVisitsRepository::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\PersonalRecentVisitsWidget::class,
            PersonalRecentVisitsWidget::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\TeamRecentVisitsWidget::class,
            TeamRecentVisitsWidget::class,
            true
        );

        $this->app->bindIf(
            \Kontenta\Kontour\Contracts\ItemHistoryWidget::class,
            ItemHistoryWidget::class,
            true
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->registerResources();
        $this->registerRoutes();
        $this->registerEventListeners();
        $this->registerWidgets();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
        }
    }

    /**
     * Setup the configuration.
     */
    protected function configure()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/kontour.php', 'kontour');
    }

    /**
     * Register views and other resources.
     */
    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'kontour');
    }

    /**
     * Register the admin routes to index and auth
     */
    protected function registerRoutes()
    {
        $this->registerAdminRoutes(__DIR__ . '/../../routes/admin.php');
        $this->registerAdminGuestRoutes(__DIR__ . '/../../routes/auth.php');
        if (config('kontour.passwords')) {
            $this->registerAdminGuestRoutes(__DIR__ . '/../../routes/passwords.php');
        }
    }

    protected function registerEventListeners()
    {
        Event::subscribe(RecentVisitsRepository::class);
    }

    protected function registerWidgets()
    {
        $this->registerAdminWidget($this->app->make(\Kontenta\Kontour\Contracts\MenuWidget::class), $this->app->make(\Kontenta\Kontour\Contracts\AdminViewManager::class)->navSection());
        $this->registerAdminWidget($this->app->make(\Kontenta\Kontour\Contracts\UserAccountWidget::class), $this->app->make(\Kontenta\Kontour\Contracts\AdminViewManager::class)->headerSection());
    }

    /**
     * Setup the resource publishing groups.
     */
    protected function offerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../../config/kontour.php' => config_path('kontour.php'),
        ], 'kontour-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/kontour'),
        ], 'kontour-views');
    }
}
