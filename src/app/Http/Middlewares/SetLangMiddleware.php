<?php

namespace Different\DifferentCore\app\Http\Middlewares;


use Illuminate\Support\Facades\App;

class SetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if(!session()->has('lang'))
        {
            session()->put('lang', config('app.locale'));
        }

        App::setLocale(session()->get('lang'));

        return $next($request);
    }
}
