<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/22
 * Time: 下午7:02
 */
namespace app\common\library;

class Tree{

    private $formList = [];

    private function _format_tree($list , $level = 0,$title = 'title'){
        foreach ($list as $key=>$val) {
            $title_prefix = str_repeat("&nbsp;", $level*4);
            $title_prefix .= "┝ ";
            $val['level'] = $level;
            $val['title'] = $level == 0 ? $val[$title] : $title_prefix.$val[$title];
            if (!array_key_exists('_child', $val)) {
                array_push($this->formList, $val);
            } else {
                $child = $val['_child'];
                unset($val['_child']);
                array_push($this->formList, $val);
                $this->_format_tree($child, $level+1, $title); //进行下一层递归
            }
        }
    }

    public function toFormatTree($list,$pk = 'id',$pid = 'pid' , $child = '_child'){
        $list = $this->list_to_tree($list, $pk, $pid, $child);
        $this->_format_tree($list,0,'title');
        return $this->formList;
    }

    /**
     * 节点遍历
     *
     * @param        $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param int    $root
     * @return array
     */
    public function list_to_tree($list,$pk = 'id',$pid = 'pid',$child = '_child',$root = 0){
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                if ($data instanceof \think\Model) {
                    $list[$key] = $data->toArray();
                }
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                if (!isset($list[$key][$child])) {
                    $list[$key][$child] = [];
                    //将没有二级菜单对项赋予控制器
                    if($list[$key]['url']){
                        $urlC = explode('/',$list[$key]['url']);
                        $list[$key]['controller'] = $urlC[0];
                    }
                }
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                        //子级菜单对方法
                        if(isset($list[$key]['url'])){
                            $urlA = explode('/',$list[$key]['url']);
                            $list[$key]['action'] = $urlA[1];
                            if(!isset($parent['controller'])){
                                $parent['controller'] = $urlA[0];
                            }
                        }
                    }
                }
            }
        }
        return $tree;
    }

}