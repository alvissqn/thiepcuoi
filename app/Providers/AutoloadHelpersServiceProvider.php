<?php
/*
 * Tự động load các file php trong thư mục App/Helpers/autoload
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AutoloadHelpersServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		foreach( glob( app_path('Helpers/autoload/*.php') ) as $file){
			require($file);
		}
	}

}