<?php
/*
 * Set ngôn ngữ
 */
namespace App\Http\Middleware;

use Closure, App;

class SetLang {
	public function handle($request, Closure $next) {
		App::setLocale( \Option::get('settings__general_language', config('app.locale')) );
		return $next($request);
	}
}