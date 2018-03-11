<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/16
 * Time: 下午5:05
 */
namespace app\common\model;

class AdminNode extends Base{


    /**
     * 获取菜单
     * @return mixed
     */
    public function getMenu(){
        $menu = $this->getAll(['status' => 1]);
        return $menu;
    }

    /**
     * 获取菜单并且值获取标题
     * @return mixed
     */
    public function getMenuNode(){
        $menu = $this->getAll(['status' => 1],['id','title','pid','url','sort','icon','level']);
        return collection($menu)->toArray();
    }


}