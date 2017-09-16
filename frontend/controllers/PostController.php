<?php
    namespace frontend\controllers;

    use common\models\CatsModel;
    use common\models\PostExtends;
    use frontend\models\PostsForm;
    use yii\web\Controller;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;

    class PostController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => [ 'index', 'create', 'upload', 'ueditor' ],
                    'rules' => [
                        [
                            'actions' => [ 'index' ],
                            'allow'   => true,
                        ],
                        [
                            'actions' => [ 'create', 'upload', 'ueditor' ],
                            'allow'   => true,
                            'roles'   => [ '@' ],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        '*' => [ 'get', 'post' ],
                    ],
                ],
            ];
        }

        /**
         * 函數會自动加载到类里
         * @return array
         */
        public function actions()
        {
            return [
                'upload'  => [
                    'class'  => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                    'config' => [
                        'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                    ],
                ],
                'ueditor' => [
                    'class'  => 'common\widgets\ueditor\UeditorAction',
                    'config' => [
                        //上传图片配置
                        'imageUrlPrefix'  => "", /* 图片访问路径前缀 */
                        'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径*/
                    ],
                ],
                'tags'    => [
                    'class'  => 'common\widgets\tags\TagWidget',
                    'config' => [
                    ],
                ],
            ];
        }

        /**
         * 文章列表
         * @return string
         */
        public function actionIndex()
        {
            return $this->render( 'index' );
        }

        /**
         * @name:actionCreate
         * @desc:创建文章
         * @return string|\yii\web\Response
         */
        public function actionCreate()
        {
            // 实例化了一个表单Model类
            $model = new PostsForm();
            // 事业分类表的Ar类获取所有分类
            $cat = CatsModel::getAllCats();
            // 使用场景
            $model->setScenario( PostsForm::SCENARIOS_CREATE );

            if ( $model->load( \Yii::$app->request->post() ) && $model->validate() ) {

                if ( !$model->create() ) {
                    \Yii::$app->session->setFlash( 'warning', $model->_lastError );
                } else {
                    return $this->redirect( [ 'post/view', 'id' => $model->id ] );
                }
            }

            return $this->render( 'create', [ 'model' => $model, 'cat' => $cat ] );
        }

        /**
         * @name:actionView
         * @desc:展示文章
         * @param null $id
         * @return string
         * @throws \yii\web\NotFoundHttpException
         */
        public function actionView( $id = null )
        {
            $model = new PostsForm();
            $data = $model->getViewById( $id );
            // 文章统计
            $model = new PostExtends();
            $model->upCounter( [ 'post_id' => $id ], 'browser', 1 );

            return $this->render( "view", [ 'data' => $data ] );
        }

    }