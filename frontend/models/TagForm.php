<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 上午 8:15:08
 */
namespace frontend\models;
use yii\base\Model;

class TagForm extends Model{
    public $id;
    public $tags;
    public function rules()
    {
        return [
            ['tags','required'],
            ['tags','each','rule'=>['string']],
        ];
    }

    public function saveTags()
    {
        $ids =[];
        if(!empty($this->tags)){
            foreach ($this->tags as $tag) {
                $ids[]=$this->_saveTag($tag);
            }
        }
        return $ids;
    }

    public function _saveTag($tag){
        $model = new Tags();
        $res = $model
            ->find()
            ->where(['tag_name'=>$tag])
            ->one();
        // 新建标签
        if(!$res){
            $model->tag_name=$tag;
            $model->post_num = 1;
            if(!$model->save()){
                throw new \Exception("保存标签失败");
            }
            return $res->id;
        }else{
            //增加某个字段的值的数目
            $res->updateCounters(['post_num'=>1]);
        }
        return $res->id;
    }
}
