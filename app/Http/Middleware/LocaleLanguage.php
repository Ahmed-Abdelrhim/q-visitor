<?php

namespace App\Http\Middleware;

use App\Models\Languages;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $languages = Languages::query()
            ->select('iso')
            ->where('active', true)
            ->get();

        view()->share(['languages' => $languages]);

        if (Session::has('locale')) {
            // app()->setLocale(session('locale'));
            $locale = Session::get('locale');
            // Session::put('ss','went for locale');
            App::setLocale($locale);
        }

        else {
            App::setLocale('en');
        }


        return $next($request);
    }
}
