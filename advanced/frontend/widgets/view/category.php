<?php

 foreach ($categorys as $key=>$cate):?>
    <div class="cat <?=$key?'':'item1';?>">
        <h3><?=\yii\helpers\Html::a($cate->name,['shop/list','id'=>$cate->id])?><b></b></h3>
        <div class="cat_detail">
            <?php foreach ($cate->children as $kes=> $child):?>
                <dl class="<?=$kes?'':'dl_1st';?>">
                    <dt><?=\yii\helpers\Html::a($child->name,['shop/list','id'=>$child->id])?></dt>
                    <?php foreach($child->children as $md):?>
                        <dd>
                            <?=\yii\helpers\Html::a($md->name,['shop/list','id'=>$md->id])?>
                        </dd>
                    <?php endforeach;?>
                </dl>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>