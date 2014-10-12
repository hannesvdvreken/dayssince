<?php
namespace Dayssince\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Authenticator;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;

class AuthMiddleware implements Middleware
{
    /**
     * The authenticator implementation.
     *
     * @var Authenticator
     */
    protected $auth;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param Authenticator $auth
     * @param ResponseFactory $response
     */
    public function __construct(Authenticator $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return $this->response->make('Unauthorized', 401);
            } else {
                return $this->response->redirectGuest('auth/login');
            }
        }

        return $next($request);
    }

    /**
     * Run the request filter.
     *
     * @param Route $route
     * @param Request $request
     * @return mixed
     */
    public function filter(Route $route, Request $request)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return $this->response->make('Unauthorized', 401);
            } else {
                return $this->response->redirectGuest('auth/login');
            }
        }
    }
}
