<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AddApplicationJSONtoApiRoutes
{

    public $force = true;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if($request->is('api/*')):
            if (!$request->headers->has('Accept') || $this->force) {
                $request->headers->set('Accept', 'application/json');
            }
        // endif;

        return $next($request);
    }
}
