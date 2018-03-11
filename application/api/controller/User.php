<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/6
 * Time: 下午10:41
 */
namespace app\api\controller;

use app\api\helper\UserHelper;
use app\common\library\ErrorCode;
use app\common\library\Helper;
use think\Session;
use app\common\library\Agent;

class User extends Base
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 获取网站人物信息
     * @return mixed
     */
    public function getUserInfoPost()
    {
        $result = model('Owner')->getByMaps(['status' => 1]);
        if($result){
            successReturn('获取成功',$result);
        }else{
            errorReturn(ErrorCode::ErrorParam,'获取失败');
        }
    }
    /**
     * 设置网站人物信息
     * @author sxd
     */
    public function setUserInfoPost()
    {
        $valid = UserHelper::setUserInfoPostValid();
        if($valid){
            $ownerModel = model('Owner');
            $result = $ownerModel->addOne(Helper::$param);
            if($result){
                successReturn('添加成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'添加失败');
            }
        }
    }
    /**
     * 修改网站人物信息
     * @return mixed
     */
    public function updateUserInfoPost()
    {
        $valid = UserHelper::updateUserInfoPostValid();
        if($valid){
            $ownerModel = model('Owner');
            $result = $ownerModel->updateOne(Helper::$param);
            if($result){
                successReturn('修改成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'修改失败');
            }
        }
    }
    /**
     * 删除
     * @return mixed
     */
    public function deleteUserInfoPost()
    {
        $valid = UserHelper::deleteUserInfoPostValid();
        if($valid){
            $result = model('Owner')->delData(Helper::$param);
            if($result){
                successReturn('删除成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'删除失败');
            }
        }
    }

    /**
     * 登录身份验证
     * @return mixed
     */
    public function checkLoginPost()
    {
        $valid = UserHelper::checkLoginPostValid();
        if($valid){
            $user = model('AdminUser')->getByMaps([ 'account' => Helper::$param['account'] ]);
            if(!$user){
                errorReturn(ErrorCode::BadParam,'该用户不存在');
            }
            if( $user['password'] !== user_md5(Helper::$param['password']) ){
                errorReturn(ErrorCode::BadParam,'密码错误');
            }
            if( $user['status'] === 0 ){
                errorReturn(ErrorCode::BadParam,'账号已经被禁用');
            }
            $updateData['last_login_time'] = time();
            $updateData['last_login_ip'] = sprintf('%u',ip2long(request()->ip()));
            $updateData['login_times']= ['exp','login_times+1'];
            $updateData['id'] = $user['id'];
            $updateData['os'] = Agent::getOs();
            $updateData['browser'] = Agent::getBroswer();
            model('AdminUser')->updateOne($updateData);
            Session::set('auth_id',$user['id']);
            Session::set('user_name',$user['account']);
            Session::set('portrait',$user['portrait']);
            Session::set('last_login_time',$user['last_login_time']);
            $session_id = session_id();
            $authKey = user_md5($user['account'].$user['password'].$session_id);
            session::set('Auth_'.$authKey,null);
            session::set('Auth_'.$authKey,$authKey);
            $data['auth_key'] = $authKey;
            successReturn('登录成功',$data);
        }
    }


}