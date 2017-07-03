<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class GoodsAsset extends AssetBundle{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/goods.css" type="text/css">
//<link rel="stylesheet" href="style/common.css" type="text/css">
//<link rel="stylesheet" href="style/bottomnav.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
//
//<!--引入jqzoom css -->
//<link rel="stylesheet" href="style/jqzoom.css" type="text/css">
//
//<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//<script type="text/javascript" src="js/header.js"></script>
//<script type="text/javascript" src="js/goods.js"></script>
//<script type="text/javascript" src="js/jqzoom-core.js"></script>
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/goods.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css',
        'style/jqzoom.css'

    ];
    public $js = [
        'js/header.js',
        'js/goods.js',
        'js/jqzoom-core.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}