<?php

    namespace common\models;

    use Yii;

    /**
     * This is the model class for table "post_extends".
     *
     * @property integer $id
     * @property integer $post_id
     * @property integer $browser
     * @property integer $collect
     * @property integer $praise
     * @property integer $comment
     */
    class PostExtends extends \yii\db\ActiveRecord
    {
        /**
         * @inheritdoc
         */
        public static function tableName()
        {
            return 'post_extends';
        }

        /**
         * @inheritdoc
         */
        public function rules()
        {
            return [
                [ [ 'post_id', 'browser', 'collect', 'praise', 'comment' ], 'integer' ],
            ];
        }

        /**
         * @inheritdoc
         */
        public function attributeLabels()
        {
            return [
                'id'      => 'ID',
                'post_id' => 'Post ID',
                'browser' => 'Browser',
                'collect' => 'Collect',
                'praise'  => 'Praise',
                'comment' => 'Comment',
            ];
        }

        /**
         * @name:upCounter
         * @desc:文章点击次数
         * @param $cond
         * @param $attribute
         * @param $num
         * @return bool
         */
        public function upCounter( $cond, $attribute, $num )
        {
            // 可以优化
            $counter = $this->findOne( $cond );
            if ( !$counter ) {
                $this->setAttributes( $cond );
                $this->save();
            } else {
                $countData[ $attribute ] = $num;
                $counter->updateCounters( $countData );
            }
            return true;
        }


    }
