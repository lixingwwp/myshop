<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menus`.
 */
class m170618_062322_create_menus_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menus', [
            'id' => $this->primaryKey(),
            'label' => $this->string()->comment('名称'),
            'url' => $this->string(255)->comment('路由地址'),
            'parent_id' => $this->integer()->comment('上级菜单'),
            'sort' => $this->integer()->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menus');
    }
}
