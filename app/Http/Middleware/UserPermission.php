<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserPermission
{
    protected $auth;

    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @param  $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if ($this->auth->guest() || $request->user()->hasAnyPermission($permissions)==false) {
            abort(403);
        }

        return $next($request);
    }
}
