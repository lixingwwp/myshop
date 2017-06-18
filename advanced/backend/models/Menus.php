<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $label
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Menus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    static public function menu(){
        $menus = self::find()->all();
        array_unshift( $menus,['label'=>'顶级分类','id'=>0]);
        return ArrayHelper::map($menus,'id','label');
    }
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
        [['label','parent_id'],'required'],
            [['parent_id', 'sort'], 'integer'],
            [['label', 'url'], 'string', 'max' => 255],
        ];
    }

    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => '名称',
            'url' => '路由地址',
            'parent_id' => '顶级菜单',
            'sort' => '排序',
        ];
    }
}
