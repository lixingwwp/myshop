<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_photos`.
 */
class m170613_070742_create_goods_photos_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_photos', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer(),
            'photo'=>$this->string(100)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_photos');
    }
}
