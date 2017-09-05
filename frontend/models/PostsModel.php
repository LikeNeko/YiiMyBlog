<?php
/**
 * PostAR类
 */
namespace frontend\models;

use yii\db\ActiveRecord;

class PostsModel extends ActiveRecord
{
    const IS_VALID = 1; // 是否发布 已发布
    const NO_VALID = 0; // 是否发布 未发布

    /**
     * 数据库表名
     * @return string
     */
    public static function tableName()
    {
        return 'posts';
    }

    public function rules()
    {
        return [
            [['content'], 'string'],
            [['cat_id', 'user_id', 'is_valid', 'created_at', 'updated_at'], 'integer'],
            [['summary', 'label_img'], 'string', 'max' => 255],
            ['user_name', 'safe'],
            ['title', 'safe'],
        ];
    }
}