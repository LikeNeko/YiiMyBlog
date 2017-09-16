<?php
/**
 * Created by  NekoSakura
 * blog: www.nekosakura.cn
 * Date: 2017/9/8
 * Time: 下午 8:11:25
 */
namespace frontend\models;

use common\models\Feeds;
use yii\base\Model;

class FeedForm extends Model
{
	public $content;
	public $_lastErrot;

	public function rules()
	{
		return [
			[ 'content', 'required' ],
			[ 'content', 'string', 'max' => 255 ],
		];
	}

	public function attributeLabels()
	{
		return [
			'id'      => 'id',
			'content' => '内容',
		];
	}

	public function getList()
	{
		$model = new Feeds();
		$res = $model->find()
			->limit( 10 )
			->with( 'user' )
			->orderBy( [ 'id' => SORT_DESC ] )
			->asArray()
			->all();
		return $res ? : [ ];
	}
}