<?php namespace ILaravel;

/**
 * 控制器基类
 */
class Controller extends \Illuminate\Routing\Controller
{

    /**
     * 视图变量
     * @var array
     */
    protected $viewVars = [];

    /**
     * 返回视图所在目录
     * 
     * @return string
     */
    protected function getViewDir()
    {
        return \View::getViewDir();
    }

    /**
     * 注入 视图中使用的工具对象,在模版中可以使用 $_vt['工具名'] 来引用
     *
     * @return array
     */
    protected function getViewTools()
    {
        return [];
    }

    /**
     * 渲染视图模版
     *
     * @param  string $viewname
     * @return string
     */
    protected function view($viewname)
    {
        $this->viewVars['_vt'] = $this->getViewTools();
        return \View::make($viewname, $this->viewVars, $this->getViewDir());
    }

    /**
     * API 接口调用成功 JSON 格式
     *
     * @param array $data 数据
     * @param string $msg 操作成功提示
     *
     * @return string
     */
    protected function apiSuccess($data = [], $msg = '操作成功')
    {
        $d = ['errcode' => 0, 'msg' => $msg, 'data' => $data];
        return json_encode($d);
    }

    /**
     * API 接口调用失败 JSON 格式
     *
     * @param int $errcode 错误码
     * @param string $msg  操作失败提示
     * @param array $data  额外数据
     *
     * @return string
     */
    protected function apiFailure($errcode = 1, $msg = '操作失败', $data = [])
    {
        $d = ['errcode' => $errcode, 'msg' => $msg, 'data' => $data];
        return json_encode($d);
    }

}