<?php

namespace frontend\models;
use backend\models\Article;


/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property string $status
 * @property string $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    static public $is_help=['0'=>'普通','1'=>'帮助'];
    static public $status=['0'=>'隐藏','1'=>'正常','-1'=>'删除'];


    public function getArticles(){
       return $this->hasMany(Article::className(),['article_category_id'=>'id']);
    }
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','required'],
            [['intro'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 5],
            [['is_help'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '类型',
        ];
    }
}
