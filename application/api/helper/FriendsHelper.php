<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/10
 * Time: 下午9:49
 */
namespace app\api\helper;

use app\common\library\ErrorCode;
use think\Paginator;
use think\Validate;

class FriendsHelper extends BaseHelper{

    protected static $_rule = array(
        'blob_name' => 'require|max:30',
        'remark' => 'max:60',
        'is_https' => 'require|in:0,1',
        'links' => 'url|require|max:50',
        'sort' => 'number'
    );

    protected static $_msg = array(
        'blob_name.require' => '请填写博客名',
        'blob_name.max' => '博客名称长度少于30',
        'remark.max' => '备注的长度少于60',
        'is_https.require' => '请填写是否加密',
        'is_https.in' => '加密项参数有误',
        'links.require' => '请填写链接',
        'links.max' => '链接长度少于50',
        'links.url' => '链接格式有误',
        'sort.number' => '排序字段必须为数字'
    );

    public static function addFriendLinksPostValid(){
        $validate = Validate::make(self::$_rule,self::$_msg);
        if(!$validate->check(self::$ajax)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        self::$param = self::$ajax;
        return true;
    }

    public static function updateFriendLinksPostValid(){
        if(count(self::$ajax) < 2 ) errorReturn(ErrorCode::BadParam,'没有修改不必提交');
        $origin = model('FriendlyLinks')->getOne(intval(self::$ajax['id']),array_keys(self::$ajax));
        if(!$origin) errorReturn(ErrorCode::ErrorParam,'参数错误');
        $diff = array_diff_assoc(self::$ajax,$origin->toArray());
        if(count($diff) == 0)errorReturn(ErrorCode::BadParam,'没有修改不必提交');
        $rule = array();$msg = array();
        if(isset($diff['blob_name'])){
            $rule['blob_name'] = self::$_rule['blob_name'];
            $msg['blob_name.require'] = self::$_msg['blob_name.require'];
            $msg['blob_name.max'] = self::$_msg['blob_name.max'];
        }
        if(isset($diff['remark'])){
            $rule['remark'] = 'require|max:60';
            $msg['remark.require'] = '请填写备注';$msg['remark.max'] = self::$_msg['remark.max'];
        }
        if(isset($diff['is_https'])){
            $rule['is_https'] = self::$_rule['is_https'];
            $msg['is_https.require'] = self::$_msg['is_https.require'];
            $msg['is_https.in'] = self::$_msg['is_https.in'];
        }
        if(isset($diff['links'])){
            $rule['links'] = self::$_rule['links'];
            $msg['links.require'] = self::$_msg['links.require'];
            $msg['links.max'] = self::$_msg['links.max'];
            $msg['links.url'] = self::$_msg['links.url'];
        }
        if(isset($diff['sort'])){
            $rule['sort'] = 'require|number';
            $msg['sort.require'] = '请填写排序参数';$msg['sort.number'] = self::$_msg['sort.number'];
        }
        $validate = Validate::make($rule,$msg);
        if(!$validate->check($diff)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        self::$param = self::$ajax;
        self::$param['id'] = self::$ajax['id'];
        return true;
    }

    public static function deleteFriendLinksPostValid(){
        $rule = array(
            'ids' => 'require|array'
        );
        $msg = array(
            'ids.require' => '请填写参数',
            'ids.array' => '参数只能是数组'
        );
        self::$ajax['ids'] = explode(',',self::$ajax['ids']);
        $validate = Validate::make($rule,$msg);
        if(!$validate->check(self::$ajax)){
            errorReturn(ErrorCode::BadParam,$validate->getError());
        }
        self::$param = self::$ajax;
        return true;
    }







}