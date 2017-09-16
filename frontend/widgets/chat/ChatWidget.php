<?php
/**
 * Created by  NekoSakura
 * blog: www.nekosakura.cn
 * Date: 2017/9/6
 * Time: 下午 2:17:02
 */
// 文章列表组件
namespace frontend\widgets\chat;

use common\models\PostsModel;
use frontend\models\FeedForm;
use frontend\models\PostsForm;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Url;

class ChatWidget extends Widget
{

    public function run()
    {
        $feed = new FeedForm();
        $data = $feed->getList();
        return $this->render('index',['data'=>$data]);
    }
}
