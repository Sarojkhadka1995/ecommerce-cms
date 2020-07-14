<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;
use Illuminate\Support\Facades\View;
use Config;
use Cookie;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct(LanguageService $languageService){
        $this->languageService = $languageService;
    }
    public function handle($request, Closure $next)
    {
        $locale = Config::get('constants.DEFAULT_LOCALE');
        if(session()->get('lang') !== null){
            $locale = session()->get('lang');
        }
        View::share('globalLocale', $locale);

        $languages = $this->languageService->getBackendLanguages();
        View::share('globalLanguages', $languages);
        
        return $next($request);
    }
}