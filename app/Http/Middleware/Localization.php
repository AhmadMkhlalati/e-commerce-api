<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale('en');
        return $next($request);

        if (Session::get('locale') != null) {
            App::setlocale(Session::get('locale'));
        } else {
            Session::put('locale', 'en');
            App::setlocale(Session::get('locale'));
        }
        return $next($request);
    }
}
