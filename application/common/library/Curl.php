<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/18
 * Time: 下午6:49
 */
namespace app\common\library;

class Curl{

    //静态实例
    private static $instance;
    //地址
    private $url;
    //参数
    private $param = array();
    //构造函数
    public function __construct($url = '',$param = [])
    {
        $this->url = $url;
        $this->param = $param;
    }
    //静态实例
    public static function Instance($url = '',$param = []){
        if(is_null(self::$instance)){
            self::$instance = new self($url,$param);
        }
        return self::$instance;
    }

    //设置请求地址
    public function setUrl($url = ''){
        $this->url = $url;
    }

    //设置参数
    public function setParam($param = []){
        $this->param = array_merge($this->param,$param);
    }

    //执行代理操作
    public function doRequest($post = 1){
        if(!$this->url){
            echo '请设置地址参数';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        if( 1 === $post ){
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen(json_encode($this->param))));
        curl_setopt($ch, CURLOPT_POSTFIELDS , json_encode($this->param));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,1);
    }


}