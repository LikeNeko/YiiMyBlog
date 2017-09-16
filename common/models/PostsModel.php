<?php
/**
 * PostAR类
 */
namespace common\models;

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

    /**
     * @name:getRelate
     * @desc:链接Relate表
     * @return \yii\db\ActiveQuery
     */
    public function getRelate()
    {
        return $this->hasMany( RelationPostTags::className(), [ 'post_id' => "id" ] );
    }

    public function getExtend()
    {
        return $this->hasOne( PostExtends::className(), [ 'post_id' => 'id' ] );
    }
    public function getCat()
    {
        return $this->hasOne( CatsModel::className(), [ 'id' => 'cat_id' ] );
    }

    public function rules()
    {
        return [
            [ [ 'content' ], 'string' ],
            [ [ 'cat_id', 'user_id', 'is_valid', 'created_at', 'updated_at' ], 'integer' ],
            [ [ 'summary', 'label_img' ], 'string', 'max' => 255 ],
            [ 'user_name', 'safe' ],
            [ 'title', 'safe' ],
        ];
    }

    /**
     * @name:getPages
     * @desc:获取文章列表
     * @param $query
     * @param int $curPage
     * @param int $pageSize
     * @param null $search
     * @return array
     */
    public function getPages( $query, $curPage = 1, $pageSize = 10, $search = null )
    {
        if ( $search )
            $query = $query->andFilerWhere( $search );
        $data['count'] = $query->count();
        if ( !$data['count'] ) {
            return [
                'count'    => 0,
                'curPage'  => $curPage,
                'pageSize' => $pageSize,
                'start'    => 0,
                'end'=>0,
                'data' => []
            ];
        }
        // 超过实际页数 不取curPage为当前页
        $curPage = (ceil($data['count']/$pageSize)<$curPage)
            ? ceil($data['count']/$pageSize ) : $curPage;

        // 当前页
        $data['curPage'] = $curPage;
        // 每页显示条数
        $data['pageSize'] = $pageSize;
        // 起始页
        $data['start'] = ($curPage-1)*$pageSize+1;
        // 末页
        $data['end']= (ceil($data['count']/$pageSize)==$curPage)
            ? $data['count']:($curPage-1)*$pageSize+$pageSize;

        $data['data'] = $query
            ->offset(($curPage-1)*$pageSize)
            ->limit($pageSize)
            ->asArray()
            ->all();
        return $data;
    }
}