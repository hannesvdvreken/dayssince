<?php
namespace Dayssince\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

class CsrfMiddleware implements Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     *
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'GET' || $this->tokensMatch($request)) {
            return $next($request);
        }

        throw new TokenMismatchException;
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param Request $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        return $request->session()->token() == $request->input('_token');
    }
}
