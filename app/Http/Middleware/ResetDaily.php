<?php

namespace GameScores\Http\Middleware;

use Closure;
use Cache;
use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ResetDaily
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $lastReset = Cache::rememberForever('reset_at', function() { return Carbon::now(); });
        $now = Carbon::now();

        if ($lastReset->addHours(10)->lt($now)) {
            Artisan::call('db:seed', [
                '--class' => 'APIResetSeeder',
            ]);

            $this->setResetTime();
        }

        return $next($request);
    }

    protected function setResetTime()
    {
        \Log::info('reset API');
        Cache::forever('reset_at', Carbon::now());
    }
}
