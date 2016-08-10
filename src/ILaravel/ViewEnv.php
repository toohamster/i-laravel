<?php namespace ILaravel;

class ViewEnv
{

	/**
     * 构造函数
     *
     * @param string $view_dir
     */
    function __construct($view_dir)
    {
        $this->view_dir = $view_dir;
    }

    /**
     * 返回视图所在目录
     * 
     * @return string
     */
    public function getViewDir()
    {
    	return $this->view_dir;
    }

    /**
	 * 渲染视图模版
	 *
	 * @param  string  $view
	 * @param  array   $data
	 * @param  string  $view_dir 视图目录
	 * 
	 * @return \ILaravel\View
	 */
	public function make($viewname, $data = [], $view_dir=null)
	{
		if ( empty($view_dir) ) $view_dir = $this->view_dir;

		$obj = new View($view_dir, $viewname, $data);
		return $obj->execute();
	}

}