<?php
/**
 * Created by  NekoSakura
 * blog: www.nekosakura.cn
 * Date: 2017/9/6
 * Time: 下午 2:17:02
 */
// 文章列表组件
namespace frontend\widgets\post;

use common\models\PostsModel;
use frontend\models\PostsForm;
use yii\base\Widget;
use yii\data\Pagination;
use yii\helpers\Url;

class PostWidget extends Widget
{
    // 标题
    public $title = '';
    // 条数
    public $limit = 6;
    // 是否分页
    public $more = true;
    // 显示更多
    public $page = false;

    public function run()
    {
        $curPage = \Yii::$app->request->get("page",1);
        // 查询条件
        $cond = ['=','is_valid',PostsModel::IS_VALID];
        $res = PostsForm::getList($cond,$curPage,$this->limit);
        $result['title'] = $this->title?:"最新文章";
        $result['more'] = Url::to(['post/index']);
        $result['body'] = $res['data']?:[];
        if($this->page){
            $pages = new Pagination([
                'totalCount'=>$res['count'],
                "pageSize"=>$res["pageSize"]
            ]);
            $result['page'] = $pages;
        }
        return $this->render( 'index' ,['data'=>$result]);
    }
}
