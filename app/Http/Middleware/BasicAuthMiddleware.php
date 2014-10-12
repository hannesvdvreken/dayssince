<?php
namespace Dayssince\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Auth\Authenticator;
use Illuminate\Http\Request;

class BasicAuthMiddleware implements Middleware
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
     * @param Authenticator $auth
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
        return $this->auth->basic() ?: $next($request);
    }

    /**
     * Run the request filter.
     *
     * @return mixed
     */
    public function filter()
    {
        return $this->auth->basic();
    }
}
