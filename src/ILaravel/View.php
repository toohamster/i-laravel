<?php namespace ILaravel;

/**
 * 视图类
 */
class View
{

    /**
     * 视图文件所在目录
     *
     * @var string
     */
    public $view_dir;

    /**
     * 视图默认使用的布局
     *
     * @var string
     */
    public $view_layout;

    /**
     * 默认使用的视图
     *
     * @var string
     */
    public $viewname;

    /**
     * 视图变量
     *
     * @var array
     */
    public $vars;

    /**
     * 构造函数
     *
     * @param string $view_dir
     * @param string $viewname
     * @param array $vars
     */
    function __construct($view_dir, $viewname, array $vars)
    {
        $this->view_dir = $view_dir;
        $this->vars = $vars;
        $this->viewname = $viewname;
    }

    /**
     * 渲染一个视图文件，返回结果
     *
     * @return string
     */
    function execute()
    {
        $viewname = $this->viewname;
        $child = new ViewLayer($this, $viewname);

        $error_reporting = ini_get('error_reporting');
        error_reporting($error_reporting & ~E_NOTICE);
        $child->parse();

        $layer = $child;
        while (($parent = $layer->parent) != null) {
            $parent->parse($layer->blocks);
            $layer = $parent;
        }

        error_reporting($error_reporting);
        return $child->root()->contents;
    }

    /**
     * 查找指定视图文件
     *
     * @param string $viewname
     *
     * @return string
     */
    function view_filename($viewname)
    {
        $filename = str_replace('.', DIRECTORY_SEPARATOR, $viewname) . '.php';
        return $this->view_dir . DIRECTORY_SEPARATOR . $filename;
    }
}

/**
 * 视图层
 */
class ViewLayer
{
    
    /**
     * 该层所属的视图对象
     *
     * @var \ILaravel\View
     */
    public $view;

    /**
     * 父层对象
     *
     * @var \ILaravel\ViewLayer
     */
    public $parent;

    /**
     * 视图名称
     *
     * @var string
     */
    public $viewname;

    /**
     * 该层的内容
     *
     * @var string
     */
    public $contents;

    /**
     * 该层区块的内容
     *
     * @var array
     */
    public $blocks = array();

    /**
     * 该层的区块
     *
     * @var array
     */
    private $_block_stack = array();

    /**
     * 预定义的区块
     *
     * @var array
     */
    private $_predefined_blocks = array();

    /**
     * 构造函数
     *
     * @param \ILaravel\View $view
     * @param string $viewname
     */
    function __construct(View $view, $viewname)
    {
        $this->view = $view;
        $this->viewname = $viewname;
    }

    /**
     * 返回该层的顶级层（最底层的视图）
     *
     * @return \ILaravel\ViewLayer
     */
    function root()
    {
        return ($this->parent) ? $this->parent->root() : $this;
    }

    /**
     * 分析视图，并返回结果
     *
     * @param array $predefined_blocks
     */
    function parse(array $predefined_blocks = array())
    {
        $this->_predefined_blocks = $predefined_blocks;

        ob_start();
        extract($this->view->vars);
        include $this->view->view_filename($this->viewname);
        $this->contents = ob_get_clean();

        $this->_predefined_blocks = null;
        foreach ($this->blocks as $block_name => $contents) {
            $search = "%_view_block.{$block_name}_%";
            if (strpos($this->contents, $search) !== false) {
                $this->contents = str_replace($search, $contents, $this->contents);
            }
        }
    }

    /**
     * 从指定层继承
     *
     * @param string $viewname
     */
    function extend($viewname)
    {
        $this->parent = new ViewLayer($this->view, $viewname);
    }

    /**
     *
     * 定义一个区块
     *
     * @param string $block_name
     * @param boolean $append
     */
    function block($block_name, $append = false)
    {
        array_push($this->_block_stack, array($block_name, $append));
        ob_start();
    }

    /**
     * 结束最后定义的一个区块
     */
    function endblock()
    {
        list($block_name, $append) = array_pop($this->_block_stack);
        $contents = ob_get_clean();
        $this->_create_block($contents, $block_name, $append);
    }

    /**
     * 定义一个空区块
     *
     * @param string $block_name
     * @param boolean $append
     */
    function empty_block($block_name, $append = false)
    {
        $this->_create_block('', $block_name, $append);
    }

    /**
     * 载入一个视图片段
     *
     * @param string $viewname 视图片段名
     */
    function element($viewname)
    {
        $__filename = $this->view->view_filename("_elements/{$viewname}");
        extract($this->view->vars);
        include $__filename;
    }

	/**
	 * 分页
	 *
	 * @param  array $vars 		分页参数
	 * @param  string $viewname 分页模版
	 */
	public function paging(array $vars = [], $viewname = 'ilaravel-paing')
	{
	    $defaults = [
	        'total' => 0,
	        'current_page' => Input::get('page', 1),
	        'per_page' => 10,
	        'pagelen' => 7,// 要显示的页码个数
	        'url' => Request::fullUrl(), // 翻页的url
	    ];

	    $params = $vars + $defaults;
	    if ($params['total'] < 1) return;

	    $this->view->vars['_pager'] = $params;

	    if ($viewname == 'ilaravel-paing')
	    {
	    	extract($this->view->vars);
        	include __DIR__ . '/_data/view/paging.php';
	    }
	    else
	    {
	    	$this->element($viewname);
	    }	    
	}	

	/**
	 * 分页 url
	 *
	 * @param  string $url
	 * @param  int $page
	 * 
	 * @return string
	 */
	public function page_url($url, $page=1)
	{
	    $pars = parse_url($url);
	    if (!empty($pars)) {
	        parse_str($pars['query'], $query);
	        if (!is_array($query)) {
	            $query = array();
	        }
	        $query['page'] = $page;
	        $pars['query'] = http_build_query($query);

	        $url = "{$pars['scheme']}://{$pars['host']}{$pars['path']}?{$pars['query']}";
	        if (!empty($pars['fragment'])) {
	            $url .= "#{$pars['fragment']}";
	        }

	        return $url;
	    }
	    return 'javascript:;';
	}

    /**
     * 完成一个区块
     *
     * @param string $contents
     * @param string $block_name
     * @param boolean $append
     */
    private function _create_block($contents, $block_name, $append)
    {
        if (isset($this->_predefined_blocks[$block_name])) {
            if ($append) {
                $contents .= $this->_predefined_blocks[$block_name];
            } else {
                $contents = $this->_predefined_blocks[$block_name];
            }
        }

        $this->blocks[$block_name] = $contents;
        echo "%_view_block.{$block_name}_%";
    }

}
