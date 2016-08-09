<?php namespace ILaravel\View;

class Env
{

	/**
     * 构造函数
     *
     * @param string $path
     */
    function __construct($path)
    {
        $this->path = $path;
    }

	/**
	 * Get the evaluated view contents for the given view.
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  string  $dir 视图目录,缺省使用系统路径
	 * 
	 * @return \ILaravel\View\Template
	 */
	public function make($viewname, $data = [], $dir=null)
	{
		if ( empty($dir) ) $dir = $this->path;
		$obj = new Template($dir, $viewname, $data);
		
		return $obj->execute();
	}

}