<?php
/**
 * Created by  NekoSakura
 * blog: www.nekosakura.cn
 * Date: 2017/9/6
 * Time: 下午 2:17:02
 */
// 文章列表组件
namespace frontend\widgets\banner;

use common\models\PostsModel;
use frontend\models\PostsForm;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Url;

class BannerWidget extends Widget
{
    public $item = [];

    public function init()
    {
        if(empty( $this->item)){
            $this->item = [
                [
                    'label'=>'demo1',
                    'image_url'=>'http://www.runoob.com/wp-content/uploads/2014/07/carousalpluginsimple_demo.jpg',
                    'url'=>['site/index'],
                    'html'=>'123',
                    'active'=>"active"
                ],
                [
                    'label'=>'demo2',
                    'image_url'=>'http://www.runoob.com/wp-content/uploads/2014/07/carousalpluginsimple_demo.jpg',
                    'url'=>['site/index'],
                    'html'=>'321',
                    'active'=>""
                ],
                [
                    'label'=>'demo3',
                    'image_url'=>'http://www.runoob.com/wp-content/uploads/2014/07/carousalpluginsimple_demo.jpg',
                    'url'=>['site/index'],
                    'html'=>'111',
                    'active'=>""
                ],
            ];
        }

    }

    public function run()
    {

        $data['items'] = $this->item;
        return $this->render( 'index' ,['data'=>$data]);
    }
}
