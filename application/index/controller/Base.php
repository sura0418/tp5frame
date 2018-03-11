<?php
/**
 * Script: Base.php
 * Function:
 * Created: 2018/3/11 18:07
 * Author: sxd<503324901@qq.com>
 */
namespace app\index\controller;

\think\Loader::import('controller/Jump', TRAIT_PATH, EXT);
use app\common\library\Tree;
use think\Config;
use think\Session;
use think\View;
use think\Request;
use think\Loader;

class Base{
    use \traits\controller\Jump;
    /**
     * @var View 视图类实例
     */
    protected $view;
    /**
     * @var Request 请求类实例
     */
    protected $request;

    public function __construct()
    {
        if( null === $this->view ){
            $this->view = View::instance();
        }
        if( null === $this->request ){
            $this->request = Request::instance();
        }

        /*defined('UID') or define('UID',Session::get('auth_id'));
        if(null === UID){
            $this->noLogin();
        }
        if( is_null($this->tree) ){
            $this->tree = new Tree();
        }*/
        //获取菜单并且形成父子关系对节点
        /*$nodes = Loader::model('AdminNode')->getMenu();
        $tree = $this->tree->list_to_tree($nodes);*/
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        //$this->view->assign('menu',$tree);
        $this->view->assign('controller',$controller);
        $this->view->assign('action',$action);
        //$this->view->assign('user',PubLogic::getOwner());
    }
}