<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class ListAsset extends AssetBundle{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

//<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/list.css" type="text/css">
//<link rel="stylesheet" href="style/common.css" type="text/css">
//<link rel="stylesheet" href="style/bottomnav.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
//
//<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
//<script type="text/javascript" src="js/header.js"></script>
//<script type="text/javascript" src="js/list.js"></script>
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/list.css',
        'style/common.css',
        'style/bottomnav.css',
        'style/footer.css'

    ];
    public $js = [
        'js/header.js',
        'js/list.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}