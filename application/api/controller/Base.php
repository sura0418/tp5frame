<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/7
 * Time: 下午8:58
 */
namespace app\api\controller;

use app\common\library\Helper;
use app\common\library\ErrorCode;

class Base{

    protected $request = null;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: http://localhost:8080');
        if(request()->isPost() || request()->isAjax()) {
            Helper::$ajax = json_decode(request()->getInput(),1);
        }else{
            errorReturn(ErrorCode::IlleglOperation,'非法请求');
        }
    }

}