<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/7
 * Time: 下午9:06
 */
namespace app\common\library;
class Helper
{
    public static $ajax = array();

    public static $param = array();

    public static function doString($str)
    {
        return trim(strip_tags($str));
    }

    public static function doHtml($html)
    {
        if(!get_magic_quotes_gpc()){
            $html = addslashes($html);
        }
        return $html;
    }

    /**
     * 验证邮编
     */
    public static function checkZip($zip)
    {
        $isZip = "/^[0-9]{6}$/";
        if (!preg_match($isZip, $zip)) {
            return false;
        }

        return $zip;
    }

    /**
     * 验证身份证号码
     */
    public static function checkIdCard($idcard)
    {
        // 只能是18位
        if(strlen($idcard) != 18) {
            return false;
        }
        // 取出本体码
        $idcard_base = substr($idcard, 0, 17);
        // 取出校验码
        $verify_code = substr($idcard, 17, 1);
        // 加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码对应值
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        // 根据前17位计算校验码
        $total = 0;
        for($i=0; $i<17; $i++){
            $total += (int)substr($idcard_base, $i, 1) * $factor[$i];
        }
        // 取模
        $mod = $total % 11;
        // 比较校验码
        if($verify_code == $verify_code_list[$mod]) {
            return $idcard;
        }else{
            return false;
        }
    }

    /**
     * 验证银行卡号
     * 16-19 位卡号校验位采用 Luhm 校验方法计算：
     * 1，将未带校验位的 15 位卡号从右依次编号 1 到 15，位于奇数位号上的数字乘以 2
     * 2，将奇位乘积的个十位全部相加，再加上所有偶数位上的数字
     * 3，将加法和加上校验位能被 10 整除。
     */
    public static function checkBlank($s)
    {
        $n = 0;
        for ($i = strlen($s); $i >= 1; $i--) {
            $index=$i-1;
            //偶数位
            if ($i % 2==0) {
                $n += $s{$index};
            } else {//奇数位
                $t = $s{$index} * 2;
                if ($t > 9) {
                    $t = (int)($t/10)+ $t%10;
                }
                $n += $t;
            }
        }
        return ($n % 10) == 0;
    }


}