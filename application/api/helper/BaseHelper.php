<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/7
 * Time: 下午11:15
 */
namespace app\api\helper;

use app\common\library\Helper;
use app\common\library\ErrorCode;

class BaseHelper extends Helper{

    //这个是公共封装的检查页数页缓存参数
    protected static $basicRule = array(
        'current_page' => 'require|gt:0',
        'page_size' => 'require|gt:0',
        'refresh' => 'require|in:1,0'
    );

    //规则对应的信息
    protected static $basicMsg = array(
        'current_page.require' => '请填写当前页',
        'current_page.gt' => '当前页必须大于0',
        'page_size.require' => '请填写每一页的数量',
        'page_size.gt' => '数量必须大于0',
        'refresh.require' => '请填写refresh参数',
        'refresh.in' => 'refresh参数有误'
    );


}