<?php
namespace Dayssince\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Auth\Authenticator;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\Request;

class GuestMiddleware implements Middleware
{
    /**
     * The authenticator implementation.
     *
     * @var Authenticator
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Authenticator  $auth
     */
    public function __construct(Authenticator $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->check()) {
            return new RedirectResponse(url('/'));
        }

        return $next($request);
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        if ($this->auth->check()) {
            return new RedirectResponse(url('/'));
        }
    }
}
