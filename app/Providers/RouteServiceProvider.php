<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    public $namespace = "\App\Http\Controllers";

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::domain(config('app.APP_API_URL'))
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::domain(config('app.APP_API_URL'))
                ->middleware('api')
                ->prefix('auth')
                ->namespace($this->namespace)
                ->group(base_path('routes/auth.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('apiRate10', function (Request $request) {
            return Limit::perMinute(10)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('apiRate1', function (Request $request) {
            return Limit::perMinute(1)->by(optional($request->user())->id ?: $request->ip());
        });        

        RateLimiter::for('apiRatePerDay1', function (Request $request) {
            return Limit::perDay(1)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('apiRatePerDay3', function (Request $request) {
            return Limit::perDay(3)->by(optional($request->user())->id ?: $request->ip());
        });        

        RateLimiter::for('xrel', function ($job) {
            return Limit::perMinute(1); //->by(optional($job->item['fulltitle']));
        });
    }
}
