<?php
/**
 * Created by PhpStorm.
 * User: sxd <503324901@qq.com>
 * Function :
 * Date: 2017/9/12
 * Time: 下午4:43
 */

namespace app\common\model;

class Article extends Base{

    protected $type = array(
        'label_id' => 'integer'
    );

    /**
     * 文章的删除和恢复(假删除)
     * @param array $ids (被操作id的集合)
     * @param boolean $status (0为删除1为恢复)
     * @return array
     */
    public function updateArticleStatus($ids = [],$status = 0)
    {
        $labels = array();
        foreach( $ids as $value ){
            $res = $this->updateOne([ $this->pk => $value , 'status' => $status ]);
            if($res['label_id'])$labels[] = $res['label_id'];
        }
        return $labels;
    }

}