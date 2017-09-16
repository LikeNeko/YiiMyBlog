<?php
namespace frontend\models;

use common\models\PostsModel;
use common\models\RelationPostTags;
use yii\base\Model;
use yii\db\Query;
use yii\web\NotFoundHttpException;

class PostsForm extends Model
{
	/**
	 * 定义场景 创建和更新
	 */
	const SCENARIOS_CREATE = 'create';
	const SCENARIOS_UPDATE = 'update';
	/**
	 * 定义事件
	 */
	const EVENT_AFTER_CREATE = 'eventAfterCreate';
	const EVENT_AFTER_UPDATA = 'eventAfterUpdate';
	public $id;
	public $title;
	public $content;
	public $label_img;
	public $cat_id;
	public $tags;
	public $_lastError = '';

	public static function getList($cond, $curPage = 1, $pageSize = 5, $orderBy = [ 'id' => SORT_DESC ])
	{
		$post = new PostsModel();
		/**
		 * 创建查询
		 * 数组为需要获取的字段
		 */
		$select = [ 'id', 'title', 'summary', 'label_img', 'cat_id', 'user_id', 'user_name', 'is_valid', "created_at", 'updated_at' ];
		$query = $post
			->find()
			->select( $select )
			->where( $cond )
			->with( 'relate.tag', 'extend' )
			->orderBy( $orderBy );
		$res = $post->getPages( $query, $curPage, $pageSize );
		$res['data'] = self::_formatList( $res['data'] );

		return $res;
	}

	public static function _formatList($data)
	{
		foreach( $data as $list ) {
			$list['tags'] = [ ];
			if( isset( $list['relate'] ) && !empty( $list['relate'] ) ) {
				foreach( $list['relate'] as $lt ) {
					$list['tags'][] = $lt['tag']['tag_name'];
				}
			};
			unset( $list['relate'] );
		}

		return $data;
	}

	/**
	 * 设置应用场景
	 * @return array
	 */
	public function scenarios()
	{
		$scenarios = [
			self::SCENARIOS_CREATE => [ 'title', 'content', 'label_img', 'tags', 'cat_id' ],
			self::SCENARIOS_UPDATE => [ 'title', 'content', 'label_img', 'tags', 'cat_id' ],
		];

		return array_merge( parent::scenarios(), $scenarios );
	}

	public function rules()
	{
		return [
			[ [ 'tags', 'content', 'title' ], 'required' ],

			[ [ 'content', 'label_img' ], 'string' ],
			[ [ 'cat_id' ], 'integer' ],
			[ [ 'title', ], 'string' ],

		];
	}

	public function attributeLabels()
	{
		return [
			'id'        => "编号",
			'cat_id'    => "分类",
			'title'     => "标题",
			'content'   => "内容",
			'label_img' => "标签图",
			'tags'      => "标签",
		];
	}

	/**
	 * 文章创建
	 * @return bool
	 * @throws \yii\db\Exception
	 */
	public function create()
	{
		//事务处理
		$transaction = \Yii::$app->db->beginTransaction();
		try {
			$model = new PostsModel();
			$model->setAttributes( $this->attributes );
			$model->summary = $this->_getSummary();
			$model->user_id = \Yii::$app->user->identity->id;
			$model->user_name = \Yii::$app->user->identity->username;
			$model->created_at = time();
			$model->updated_at = time();
			$model->is_valid = PostsModel::IS_VALID;
			if( !$model->save() )
				throw new \Exception( "文章保存失败" );
			$this->id = $model->id;
			//不考虑对象的话完全可以直接用一个 $this->getAttributes()了
			$data = array_merge( $this->getAttributes(), $model->getAttributes() );
			// 调用事件 最终保存入数据库前触发
			$this->_eventAfterCreate( $data );
			$transaction->commit();

			return true;
		} catch ( \Exception $e ) {
			//出现错误的话
			$transaction->rollBack();
			$this->_lastError = $e->getMessage();
		}
	}

	/**
	 * 文章摘要
	 * @param int $s
	 * @param int $e
	 * @param string $char
	 * @return null|string
	 */
	private function _getSummary($s = 0, $e = 90, $char = 'utf8')
	{
		if( empty( $this->content ) )
			return null;

		return mb_substr( str_replace( '&nbsp;', '', strip_tags( $this->content ) ), $s, $e, $char );
	}

	public function _eventAfterCreate($data)
	{
		// 添加事件 分别对应，事件名，事件处理，参数
		/**
		 * 当每次应用走到这的时候都会往一个待处理的函数数组中去寻找与事件名对应的方法处理，
		 * 一个事件可以对应多个方法
		 **/
		$this->on( self::EVENT_AFTER_CREATE, [ $this, '_eventAddTag' ], $data );
//        $this->on(self::EVENT_AFTER_UPDATA,[$this,'_eventUpdataTag'],$data);
		// 触发事件
		/**
		 * 不一定非得在这触发，可以是应用的任何位置调用它都会
		 */
		$this->trigger( self::EVENT_AFTER_CREATE );
	}

	/**
	 * 添加标签
	 */
	public function _eventAddTag($event)
	{
		// 实例化表单模型
		$tag = new TagForm();
		$tag->tags = $event->data['tags'];
		$tagids = $tag->saveTags();

		// 删除原先的关联关系
		RelationPostTags::deleteAll( [ 'post_id' => $event->data['id'] ] );

		// 批量保存文章和标签的关联关系
		if( !empty( $tagids ) ) {
			foreach( $tagids as $k => $tagid ) {
				$row[ $k ]['post_id'] = $this->id;
				$row[ $k ]['tag_id'] = $tagid;
			}

			$res = ( new Query() )
				->createCommand()
				->batchInsert( RelationPostTags::tableName(), [ 'post_id', 'tag_id' ], $row )
				->execute();
			if( !$res ) {
				throw new \Exception( '关联关系保存失败' );

			}
		}
	}

	public function getViewById($id)
	{
		// 处理文章信息
		$res = PostsModel::find()
			->with( 'relate.tag', 'extend' )
			->where( [ 'id' => $id ] )
			->asArray()
			->one();
		if( !$res ) {
			throw new NotFoundHttpException( "文章不存在!" );
		}
		// 处理标签
		$res['tags'] = [ ];
		if( !empty( $res['relate'] ) ) {
			foreach( $res['relate'] as $list ) {
				$res['tags'][] = $list['tag']['tag_name'];
			}
		}
		unset( $res['relate'] );

		return $res;
	}
}