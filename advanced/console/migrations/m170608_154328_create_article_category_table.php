<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_154328_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
//            name varchar﴾50﴿ 名称
            'name'=>$this->string()->comment('名称'),
//            intro text 简介
            'intro'=>$this->text()->comment('简介'),
//            sort int﴾11﴿ 排序
            'sort'=>$this->integer()->comment('排序'),
//            status int﴾2﴿ 状态﴾‐1删除 0隐藏 1正常﴿
            'status'=>$this->string(5)->comment('状态'),
//            is_help int﴾1﴿ 类型
            'is_help'=>$this->string(10)->comment('类型'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
