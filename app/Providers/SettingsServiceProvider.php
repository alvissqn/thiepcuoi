<?php
/*
 * Load các file config trong App\Config
 */
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider {

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
		foreach( glob( app_path('Config/*.php') ) as $file){
			$filename = pathinfo($file, PATHINFO_FILENAME);
			$this->app['config'][ $filename ] = include $file;
		}
	}

}