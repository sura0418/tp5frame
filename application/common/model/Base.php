<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/6
 * Time: 下午11:44
 */
namespace app\common\model;

use think\Model;

class Base extends Model{

    /**
     * 数据库字段类型
     * @var array
     */
    protected $type = array(
        'id' => 'integer',
        'current_page' => 'integer',
        'page_size' => 'integer',
        'refresh' => 'integer',
    );

    /**
     * 查询的主键
     * @var string
     */
    protected $pk = 'id';
    /**
     * 是否开启缓存
     * @var boolean
     */
    protected $isCache = false;
    /**
     * 缓存时间
     * @var int(一个小时)
     */
    protected $expire = 3600;

    /**
     * 根据主键查询数据
     * @param int $pk
     * @param boolean|array $field
     * @param boolean $refresh
     * @return mixed
     * @author: sxd<503324901@qq.com>
     */
    public function getOne($pk = 0,$field = true,$refresh = 0)
    {
        $cache_key = $this->getTable() . '::' . $pk;
        $refresh && cache($cache_key,null);
        $selector = $this->db()->field($field);
        if($this->isCache){
            $selector->cache($cache_key,$this->expire);
        }
        $data = $selector->find($pk);
        unset($cache_key);
        return $data;
    }

    /**
     * 新增一条记录
     * @param array $data
     * @param boolean|array $field (为了获取第二次数据)
     * @param boolean $refresh
     * @return mixed
     * @author: sxd<503324901@qq.com>
     */
    public function addOne($data = [],$field = true,$refresh = 0)
    {
        $this->data($data)->save();
        if($this->id){
            $insert_data = $this->getOne($this->id,$field,$refresh);
            return $insert_data;
        }
        return false;
    }

    /**
     * 修改一条记录
     * @param array $data
     * @param boolean|array $field (为了获取第二次数据)
     * @param boolean $refresh
     * @return mixed
     * @author: sxd<503324901@qq.com>
     */
    public function updateOne($data = [],$field = true,$refresh = 0)
    {
        if(empty($data[$this->pk])){
            return false;
        }
        $res = $this->update($data);
        if($res){
            $new = $this->getOne($data[$this->pk],$field,$refresh);
            return $new;
        }
        return false;
    }

    /**
     * 删除记录(可多条)
     * @param array|int $pk
     * @return bool
     * @author: sxd<503324901@qq.com>
     */
    public function delData($pk)
    {
        $res = self::destroy($pk);
        if($res){
            if(is_array($pk)){
                foreach($pk as $value ){
                    cache($this->getTable() . '::' . $value,null);
                }
                unset($value);
            }else{
                cache($this->getTable() . '::' . $pk,null);
            }
            return true;
        }
        return false;
    }

    /**
     * 按条件查询
     * @param array $con
     * @param array|boolean $field
     * @param boolean $refresh
     * @return mixed
     * @author: sxd<503324901@qq.com>
     */
    public function getByMaps($con = [],$field = true,$refresh = 0)
    {
        $cache_key = $this->getTable() . '::' . serialize($con);
        $refresh && cache($cache_key,null);
        $selector = $this->db()->field($field)->where($con);
        if($this->isCache){
            $selector->cache($cache_key,$this->expire);
        }
        $data = $selector->find();
        unset($cache_key);
        return $data;
    }

    /**
     * 获取所有数据
     * @param array $con
     * @param boolean|array $field
     * @param array $order
     * @param boolean $refresh
     * @return mixed
     * @author sxd<503324901@qq.com>
     */
    public function getAll($con = [],$field = true,$order = [],$refresh = 0)
    {
        $cache_key = $this->getTable() . '::' . serialize($con) . ':' . serialize($field) . ':' . serialize($order);
        $refresh && cache($cache_key,null);
        $selector = $this->db()->field($field);
        if($this->isCache){
            $selector->cache($cache_key,$this->expire);
        }
        $data = $selector->where($con)->order($order)->select();
        unset($cache_key);
        return $data;
    }

    /**
     * 自带分页
     * @param int $page_size
     * @param array $con
     * @param boolean $field
     * @param array $order
     * @param boolean $refresh
     * @return mixed
     * @author sxd<503324901@qq.com>
     */
    public function page($page_size = 10,$con = [],$field = true,$order = [],$refresh = 0)
    {
        $cache_key = $this->getTable() . ':page_size:' . $page_size . ':' . serialize($con) . ':' . serialize($order);
        $refresh && cache($cache_key,null);
        $data = cache($cache_key);
        if(empty($data)){
            $data = $this->db()->field($field)->where($con)->order($order)->paginate($page_size);
            $this->isCache && cache($cache_key,$data,$this->expire);
        }
        unset($cache_key);
        return $data;
    }

    /**
     * 获取分页数据
     * @param array $limit
     * @param array $con
     * @param array $order
     * @param array $field
     * @param boolean $refresh
     * @return mixed
     * @author sxd<503324901@qq.com>
     */
    public function getList($limit = [],$con = [],$order = [],$field = [],$refresh = 0)
    {
        $cache_key = $this->getTable() . ':limit:' . serialize($limit) . ':' . serialize($con) . ':' . serialize($order);
        $refresh && cache($cache_key,null);
        $data = cache($cache_key);
        if(empty($data)){
            $data = $this->db()->field($field)->where($con)->order($order)->page($limit[0], $limit[1])->select();
            $this->isCache && cache($cache_key,$data,$this->expire);
        }
        unset($cache_key);
        return $data;
    }

    /**
     * 获取总数
     * @param array $con
     * @param boolean $refresh
     * @return mixed
     * @author sxd<503324901@qq.com>
     */
    public function total($con = [],$refresh = 0)
    {
        $cache_key = $this->getTable() . ':where:' . serialize($con);
        $refresh && cache($cache_key,null);
        $selector = $this->db();
        $con && $selector->where($con);
        if($this->isCache){
            $selector->cache($cache_key,$this->expire);
        }
        $data = $selector->count();
        unset($cache_key);
        return $data;
    }

    /**
     * 获取某一列的值
     * @param string $field
     * @param boolean $refresh
     * @return mixed
     * @author sxd<503324901@qq.com>
     */
    public function getField($field = '', $refresh = 0)
    {
        $cache_key = $this->getTable() . '::field:' . serialize($field);
        $refresh && cache($cache_key, null);
        $selector = $this->db();
        if($this->isCache){
            $selector->cache($cache_key, $this->expire);
        }
        $data = $selector->column($field);
        return $data;
    }

}