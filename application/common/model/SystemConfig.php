<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/7
 * Time: 下午12:10
 */
namespace app\common\model;

class SystemConfig extends Base
{
    public function getConfig()
    {
        $list = $this->select();
        $data = array();
        foreach( $list as $key => $value ){
            $data[$value['name']] = $value['value'];
        }
        return $data;
    }

}