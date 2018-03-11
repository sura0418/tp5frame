<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/8
 * Time: 下午4:13
 */
namespace app\api\helper;
use app\common\library\ErrorCode;
use think\Validate;

class UserHelper extends BaseHelper{

    public static function setUserInfoPostValid()
    {
        $rule = array( 'username' => 'require|max:10' , 'brief' => 'require|max:60' , 'portrait' => 'require|max:50' );
        $msg = array(
            'username.require' => '名称不可以为空' ,
            'username.max' => '名称最多不能超过10个字符',
            'brief.require' => '简介不可以为空',
            'brief.max' => '简介最多不能超过60个字符',
            'portrait.require' => '头像不可以为空',
            'portrait.max' => '头像最多为50个字符'
        );
        $validate = Validate::make($rule,$msg);
        if(!$validate->check(self::$ajax)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        self::$param = self::$ajax;
        return true;
    }

    public static function updateUserInfoPostValid()
    {
        if(count(self::$ajax) < 2 ) errorReturn(ErrorCode::BadParam,'没有修改不必提交');
        $origin = model('Owner')->getOne(intval(self::$ajax['id']),array_keys(self::$ajax));
        if(!$origin) errorReturn(ErrorCode::ErrorParam,'参数错误');
        $diff = array_diff_assoc(self::$ajax,$origin->toArray());
        if(count($diff) == 0)errorReturn(ErrorCode::BadParam,'没有修改不必提交');
        $rule = array();$msg = array();
        if(isset($diff['username'])){
            $rule['username'] = 'require|max:10';
            $msg['username.require'] = '名称不可以为空';
            $msg['username.max'] = '名称最多不能超过10个字符';
        }
        if(isset($diff['brief'])){
            $rule['brief'] = 'require|max:60';
            $msg['brief.require'] = '简介不可以为空';
            $msg['brief.max'] = '简介最多不能超过60个字符';
        }
        if(isset($diff['portrait'])){
            $rule['brief'] = 'require|max:50';
            $msg['brief.require'] = '头像不可以为空';
            $msg['brief.max'] = '头像最多为50个字符';
        }
        $validate = Validate::make($rule,$msg);
        if(!$validate->check($diff)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        $diff['id'] = self::$ajax['id'];
        self::$param = $diff;
        return true;
    }

    public static function deleteUserInfoPostValid()
    {
        $rule = array( 'id' => 'require|number' );
        $msg = array( 'id.require' => '参数必须存在' , 'id.number' => '参数必须是数字' );
        $validate = Validate::make($rule,$msg);
        if(!$validate->check(self::$ajax)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
            return false;
        }
        $info = model('Owner')->getOne(intval(self::$ajax['id']));
        if(!$info){
            errorReturn(ErrorCode::ErrorParam,'非法操作');
        }
        self::$param = self::$ajax;
        return true;
    }

    public static function checkLoginPostValid()
    {
        $rule = array( 'account' => 'require|max:10' , 'password' => 'require|max:12|min:6' , 'captcha|验证码' => 'require|captcha' );
        $msg = array(
            'account.require' => '请填写账号名',
            'account.max' => '账号长度在10以内',
            'password.require' => '请填写密码',
            'password.max' => '密码长度在6-12个字符',
            'password.min' => '密码长度在6-12个字符',
            'captcha.require' => '请填写验证码',
            'captcha.captcha' => '验证码错误',
        );
        $validate = Validate::make($rule,$msg);
        if( !$validate->check(self::$ajax) ){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        self::$param = self::$ajax;
        return true;
    }








}