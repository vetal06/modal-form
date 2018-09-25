<?php
namespace vsk\modalForm\assets;

class ModalFormAssets extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/vsk/modal-form/src/assets';
    public $js = [
        'js/modalForm.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $publishOptions = [
        'forceCopy' => YII_ENV == 'dev',
    ];
}