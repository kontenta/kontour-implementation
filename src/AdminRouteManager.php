<?php

namespace Erik\AdminManagerImplementation;

use Erik\AdminManagerImplementation\Http\Middleware\AuthenticateAdmin;
use Erik\AdminManager\Contracts\AdminRouteManager as AdminRouteManagerContract;
use Illuminate\Routing\Router;

class AdminRouteManager implements AdminRouteManagerContract
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register given routes to admin tools with the Laravel router.
     * Will set any common admin route attributes such as prefix, middleware, and domain.
     *
     * @param \Closure|string $routes See \Illuminate\Routing\Router::loadRoutes
     * @return $this
     */
    public function registerRoutes($routes)
    {
        $this->router->group($this->getRouteAttributes(), $routes);
        return $this;
    }

    /**
     * Common admin route attributes for usage with \Illuminate\Routing\Router::group $attribute parameter
     * See https://laravel.com/docs/routing#route-groups
     * @return array
     */
    public function getRouteAttributes(): array
    {
        $attributes = [];
        $attributes['middleware'] = $this->getMiddleware();
        $attributes['prefix'] = $this->getPrefix();
        $attributes['domain'] = $this->getDomain();
        return array_filter($attributes);
    }

    /**
     * Common url prefix for admin routes
     * @return string
     */
    public function getPrefix(): string
    {
        return config('admin.url_prefix');
    }

    /**
     * Common middleware for admin routes
     * @return array
     */
    public function getMiddleware(): array
    {
        return ['web', AuthenticateAdmin::class];
    }

    /**
     * Common domain for admin routes
     * @return string|null
     */
    public function getDomain()
    {
        return config('admin.domain');
    }

    /**
     * Url for admin index page
     * @return string
     */
    public function indexUrl(): string
    {
        return route('admin.index');
    }

    /**
     * Url for admin login
     * @return string
     */
    public function loginUrl(): string
    {
        return route('admin.login');
    }

    /**
     * Url for admin logout
     * @return string
     */
    public function logoutUrl(): string
    {
        return route('admin.logout');
    }
}