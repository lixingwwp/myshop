<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $provence
 * @property string $city
 * @property string $area
 * @property string $detail
 * @property string $phone
 * @property string $default
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $id;
    public function makeDefault(){
        $this->member_id = \Yii::$app->user->id;
        //如果有设置默认收货地址,则查找该用户所有的地址,所有的default值改为0
        if($this->default){
            $models = Address::find()->where(['member_id'=>$this->member_id])->all();
            foreach ($models as $model){
                $model->default = 0;
                $model->save(false);
            }
        }
    }

    public function getProvinces(){
        return $this->hasOne(Locations::className(),['id'=>'provence']);
    }
    public function getCitys(){
        return $this->hasOne(Locations::className(),['id'=>'city']);
    }
    public function getAreas(){
        return $this->hasOne(Locations::className(),['id'=>'area']);
    }

    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','provence','city','area','detail','phone'],'required'],
            ['default','boolean'],
            [['name', 'provence', 'city', 'area', 'detail'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['default'], 'string', 'max' => 1],
            ['phone','match','pattern'=>'/^1[34578]\d{9}$/','message'=>'电话号码格式不正确']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'provence' => '请选择省',
            'city' => '请选择市',
            'area' => '请选择地区',
            'detail' => '详细地址',
            'phone' => '手机号码',
            'default' => '默认',
        ];
    }
}
