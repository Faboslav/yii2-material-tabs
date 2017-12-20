<?php

namespace faboslav\materialtabs;

class MaterialTabsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/faboslav/yii2-material-tabs/assets';

    public $css = [
        'css/material-tabs.css'
    ];

    public $js = [
        'js/material-tabs.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset'
    ];
}
