<?php namespace ILaravel;

use Illuminate\Support\ServiceProvider;

/**
 * 用于测试 ServiceProvider 注入机制的实现
 */
class O extends ServiceProvider
{

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		/*
		 服务提供类在用户代码加载之前就被执行,所以可以去除掉不使用的服务提供类来提升性能
		 */
		echo 'hello ' . __METHOD__;
	}

}
