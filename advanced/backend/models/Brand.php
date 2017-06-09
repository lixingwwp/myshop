<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
//    public $imgFile;
    static public $status=['0'=>'隐藏','1'=>'正常','-1'=>'删除'];
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status'],'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name', 'logo'], 'string', 'max' => 255],
//            ['imgFile','file','extensions'=>['jpg','gif','png']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '介绍',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
            'imgFile'=>'LOGO'
        ];
    }
}
