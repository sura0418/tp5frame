<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/7
 * Time: 下午9:37
 */
namespace app\common\behavior;

class InitConfigBehavior{

    public function run(&$content)
    {
        $system_config = cache('SYSTEM_CONFIG_DATA');
        if(!$system_config){
            $system_config = \think\Loader::model('common/SystemConfig')->getConfig();
            cache('SYSTEM_CONFIG_DATA',null);
            cache('SYSTEM_CONFIG_DATA',$system_config,36000);
        }
        config($system_config);
    }

}