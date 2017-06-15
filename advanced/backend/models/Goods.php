<?php

namespace backend\models;

use app\models\Brand;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $create_time
 */
class Goods extends \yii\db\ActiveRecord
{

    static public $is_on_sale = ['0'=>'下架','1'=>'在售'];
    static public $status = ['0'=>'回收站','1'=>'正常'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort','market_price', 'shop_price','name','logo' ], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['sn'], 'string', 'max' => 20],
            [['logo'], 'string', 'max' => 100],
            ['name','unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '商品编号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类id',
            'brand_id' => '品牌分类id',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
        ];
    }
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }

    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->create_time = time();
        }
        return parent::beforeSave($insert);
    }
}
