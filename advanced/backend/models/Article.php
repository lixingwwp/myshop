<?php

namespace backend\models;

use app\models\ArticleCategory;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $status
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    static public $status=['-1'=>'删除','0'=>'隐藏','1'=>'正常'];

    public function getDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }

    public function getArticleCategory(){
       return $this->hasOne(\frontend\models\ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','intro','sort','status','article_category_id'],'required'],
            [['intro'], 'string'],
            [['article_category_id', 'sort', 'status', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'article_category_id' => '分类',
            'sort' => '排序',
            'status' => '状态',
            'create_time' => '创建时间',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }
}
