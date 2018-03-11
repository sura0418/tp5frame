<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 成功返回
 * @param string $msg
 * @param array $data
 * @throws \think\Exception
 * @Author: sxd <503324901@qq.com>
 */
function successReturn($msg = '',$data = [])
{
    response()->create([ 'code' => \app\common\library\ErrorCode::SuccessCode , 'msg' => $msg ,'data' => $data ],'json')->send();
}

/**
 * 错误返回
 * @param string $msg
 * @param array $data
 * @throws \think\Exception
 * @Author: sxd <503324901@qq.com>
 */
function errorReturn($code,$msg = '',$data = [])
{
    response()->create([ 'code' => $code, 'msg' => $msg, 'data' => $data ],'json')->send();exit();
}
/**
 * 用户密码加密方法
 * @param string $str    加密字符串
 * @param [type] $auth_key 加密符
 * @return string           加密后长度为32对字符串
 */
function user_md5($str , $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}