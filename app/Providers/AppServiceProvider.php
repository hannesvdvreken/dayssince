<?php
namespace Dayssince\Providers;

use Illuminate\Routing\Router;
use Illuminate\Routing\Stack\Builder as Stack;
use Illuminate\Foundation\Support\Providers\AppServiceProvider as ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the application's route middleware keys.
     *
     * @var array
     */
    protected $middleware = [
        'auth' => 'Dayssince\Http\Middleware\AuthMiddleware',
        'auth.basic' => 'Dayssince\Http\Middleware\BasicAuthMiddleware',
        'csrf' => 'Dayssince\Http\Middleware\CsrfMiddleware',
        'guest' => 'Dayssince\Http\Middleware\GuestMiddleware',
    ];

    /**
     * The application's middleware stack.
     *
     * @var array
     */
    protected $stack = [
        'Dayssince\Http\Middleware\MaintenanceMiddleware',
        'Illuminate\Cookie\Middleware\Guard',
        'Illuminate\Cookie\Middleware\Queue',
        'Illuminate\Session\Middleware\Reader',
        'Illuminate\Session\Middleware\Writer',
    ];

    /**
     * Build the application stack based on the provider properties.
     */
    public function stack()
    {
        $this->app->stack(function (Stack $stack, Router $router) {
            return $stack->middleware($this->stack)->then(function ($request) use ($router) {
                return $router->dispatch($request);
            });
        });
    }
}
