<?php

namespace frontend\models;

use Faker\Provider\Payment;
use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $addre
 * @property string $tel
 * @property string $deliveryId
 * @property string $delivery_name
 * @property string $delivery_price
 * @property string $paymentId
 * @property string $payment_name
 * @property string $totals
 * @property string $status
 * @property string $trade_no
 * @property integer $create_time
 */

class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $addre;
    public $deliveryId;
    public $paymentId;
    public $totals;
    public static function tableName()
    {
        return 'order';
    }
    public static function getAdd($area){
        $addre = Locations::findOne(['id'=>$area]);
        return $addre->name;
    }


    public static function address(){
        $member_id = Yii::$app->user->id;
        $addre = Address::find()->where(['member_id'=>$member_id])->asArray()->all();
        $addre_radio = null;
        foreach ($addre as $add){
            $addr['address'] = $add['name'].'&nbsp;'.$add['phone'].'&nbsp'.self::getAdd($add['provence']).'&nbsp'.self::getAdd($add['city']).'&nbsp'.self::getAdd($add['area']).'&nbsp'.$add['detail'];
            $addr['id'] = $add['id'];
            $addre_radio[] = $addr;
        }
        return $addre_radio;

    }

    public static function delivery(){
        return [
            ['delivery_id'=>1,'delivery_name'=>'普通快递送货上门','delivery_price'=>10,'delivery_detail'=>'每张订单不满499.00元,运费15.00元, 订单4..'],                         ['delivery_id'=>2,'delivery_name'=>'特快专递','delivery_price'=>40,'delivery_detail'=>'每张订单不满499.00元,运费40.00元, 订单4...'],
            ['delivery_id'=>3,'delivery_name'=>'加急快递送货上门','delivery_price'=>40,'delivery_detail'=>'每张订单不满499.00元,运费40.00元, 订单4...'],                         ['delivery_id'=>4,'delivery_name'=>'平邮','delivery_price'=>10,'delivery_detail'=>'每张订单不满499.00元,运费15.00元, 订单4...'],
        ];
    }

    public static function payment(){
        return [
            ['payment_id'=>1,'payment_name'=>"货到付款",'payment_detail'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
            ['payment_id'=>2,'payment_name'=>"在线支付",'payment_detail'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
            ['payment_id'=>3,'payment_name'=>"上门自提",'payment_detail'=>'自提时付款，支持现金、POS刷卡、支票支付'],
            ['payment_id'=>4,'payment_name'=>"邮局汇款",'payment_detail'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
        ];
    }

    public static function status(){
        return ['0'=>'已取消','1'=>'待付款','2'=>'待发货','3'=>'待收货','4'=>'完成'];
    }
    public function loadData(array $data){
        $addre = $data['address'];
        $deliveryId = $data['delivery_id'];
        $paymentId = $data['payment_id'];

        $this->total = $data['total'];
        $this->payment_id = Yii::$app->user->id;
        $add = Address::findOne(['id'=>$addre]);
        $this->name = $add->name;
        $this->province = $add->provinces->name;
        $this->city = $add->citys->name;
        $this->area = $add->areas->name;
        $this->address = $add->detail;
        $this->tel = $add->phone;
        $this->delivery_id = $deliveryId;

        $delivery = Order::delivery();
        foreach ($delivery as $id => $deli){
            if($deli['delivery_id']==$deliveryId){
                $this->delivery_name = $deli['delivery_name'];
                $this->delivery_price = $deli['delivery_price'];
            }
        }
        $this->payment_id = $paymentId;
        $payment = Order::payment();
        foreach ($payment as $id => $pay){
            if($pay['payment_id'] == $paymentId){
                $this->payment_name = $pay['payment_name'];
            }
        }




    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//           [['member_id','name','province','city','area','address','tel','delivery_id','delivery_name','delivery_price','payment_id','payment_name','total'],'required',],
            [['member_id', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name', 'province', 'city', 'area'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 60],
            [['tel'], 'string', 'max' => 11],
            [['delivery_id'], 'string', 'max' => 8],
            [['delivery_name', 'payment_name'], 'string', 'max' => 50],
            [['payment_id'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 5],
            [['trade_no'], 'string', 'max' => 100],
        ];
    }

    public function beforeSave($insert)
    {
        if($insert){
            $member_id = Yii::$app->user->id;
            $this->member_id =$member_id;
            $this->create_time = time();
            $this->status = 1;
            $this->trade_no = uniqid('SC_');
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '手机号码',
            'delivery_id' => '配送方式',
            'delivery_name' => '配送名称',
            'delivery_price' => '配送价格',
            'payment_id' => '支付方式',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
