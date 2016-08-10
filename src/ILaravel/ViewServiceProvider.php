<?php namespace ILaravel;

use Illuminate\Support\ServiceProvider;

/**
 * 视图服务提供器实现
 */
class ViewServiceProvider extends ServiceProvider
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
			$view_dir = $app['config']['view.dir'];
			if ( empty($view_dir) || !is_string($view_dir) )
			{
				$view_dir = $app['path'] . '/views';
			}

			return new ViewEnv($view_dir);
		});
	}

}
