<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/10
 * Time: 下午9:46
 */
namespace app\api\controller;

use app\api\helper\FriendsHelper;
use app\common\library\Helper;
use app\common\library\ErrorCode;

class Friends extends Base{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 新增友链
     * @return mixed
     */
    public function addFriendLinksPost()
    {
        $valid = FriendsHelper::addFriendLinksPostValid();
        if($valid){
            $result = model('FriendlyLinks')->addOne(Helper::$param);
            if($result){
                successReturn('添加成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'添加失败');
            }
        }
    }

    /**
     * 修改链接
     * @return mixed
     */
    public function updateFriendLinksPost()
    {
        $valid = FriendsHelper::updateFriendLinksPostValid();
        if($valid){
            $result = model('FriendlyLinks')->updateOne(Helper::$param);
            if($result){
                successReturn('修改成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'修改失败');
            }
        }
    }

    /**
     * 删除链接
     * @return mixed
     */
    public function deleteFriendLinksPost()
    {
        $valid = FriendsHelper::deleteFriendLinksPostValid();
        if($valid){
            $result = model('FriendlyLinks')->delData(Helper::$param['ids']);
            if($result){
                successReturn('修改成功',$result);
            }else{
                errorReturn(ErrorCode::ErrorParam,'修改失败');
            }
        }
    }

    /**
     * 获取所有在状态的链接
     * @return mixed
     */
    public function getALLFriendsPost()
    {
        $result = model('FriendlyLinks')->getAll(['status' => 1],true,['sort' => 'desc']);
        if($result){
            successReturn('获取成功',$result);
        }else{
            errorReturn(ErrorCode::ErrorParam,'修改失败');
        }
    }




}