<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class CartAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/cart.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
//
//<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//<script type="text/javascript" src="js/cart1.js"></script>

//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/fillin.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
//
//<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//<script type="text/javascript" src="js/cart2.js"></script>

    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/fillin.css',
        'style/cart.css',
        'style/footer.css'

    ];
    public $js = [
        'js/cart1.js',
        'js/cart2.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}