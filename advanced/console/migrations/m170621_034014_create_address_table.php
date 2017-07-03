<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_034014_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name' =>$this->string()->comment('收货人地址'),
            'provence' =>$this->string()->comment('请选择省'),
            'city' =>$this->string()->comment('请选择市'),
            'area' =>$this->string()->comment('请选择地区'),
            'detail' =>$this->string(255)->comment('详细地址'),
            'phone' =>$this->string(11)->comment('手机号码'),
            'default' =>$this->string(1)->comment('默认'),
        ]);
    }


    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
