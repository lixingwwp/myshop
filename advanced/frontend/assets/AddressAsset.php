<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AddressAsset extends AssetBundle
{
//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/home.css" type="text/css">
//<link rel="stylesheet" href="style/address.css" type="text/css">
//<link rel="stylesheet" href="style/bottomnav.css" type="text/css">
//<link rel="stylesheet" href="" type="text/css">
//
//<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//<script type="text/javascript" src="js/header.js"></script>
//<script type="text/javascript" src="js/home.js"></script>
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/home.css',
        'style/address.css',
        'style/bottomnav.css',
        'style/footer.css',
    ];
    public $js = [
        'js/header.js',
        'js/home.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
