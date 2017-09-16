<?php
namespace frontend\widgets\tag;
/**
 * 热门浏览组件
 */
use common\models\Tags;
use Yii;
use yii\bootstrap\Widget;
use yii\base\Object;
use yii\db\Query;

class TagWidget extends Widget
{
    public $title = '';
    
    public $limit = 20;
    
    public function run()
    {
        $res = Tags::find()
            ->orderBy(['post_num'=>SORT_DESC])
            ->limit($this->limit)
            ->all();
        
        $result['title'] = $this->title?:'标签云';
        $result['body'] = $res?:[];
        
        return $this->render('index',['data'=>$result]);
    }
  
    
}