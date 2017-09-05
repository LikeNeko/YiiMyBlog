<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cats".
 *
 * @property integer $id
 * @property string $cat_name
 */
class CatsModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cats';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_name' => 'Cat Name',
        ];
    }

    public static function getAllCats()
    {
        $res = self::find()->asArray()->all();
        $arr = ['0' => "未分類"];
        if (!empty($res)) {

            foreach ($res as $k => $re) {
                $arr[$re['id']] = $re['cat_name'];
            }
        }
        return $arr;
    }
}
