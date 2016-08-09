<?php namespace ILaravel\Facades; 

use Illuminate\Support\Facades\Facade;

/**
 * @see \ILaravel\View\Environment
 */
class View extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'i-laravel.view'; }

}