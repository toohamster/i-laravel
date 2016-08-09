<?php namespace ILaravel\View;

use Illuminate\Support\ServiceProvider;

/**
 * 视图服务提供器实现
 */
class Provider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bindShared('i-laravel.view', function($app)
		{
			$path = $app['path'] . '/views';
			return new Env($path);
		});
	}

}
