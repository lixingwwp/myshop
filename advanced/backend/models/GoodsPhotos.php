<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_photos".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $photo
 */
class GoodsPhotos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgFiles;
    public static function tableName()
    {
        return 'goods_photos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['photo'], 'string', 'max' => 100],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => 'Goods ID',
            'photo' => 'Photo',
        ];
    }
}
