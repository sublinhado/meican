<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\monitoring\assets\traffic;

use yii\web\AssetBundle;

/**
 * @author Maurício Quatrin Guerreiro
 */
class Index extends AssetBundle
{
    public $sourcePath = '@meican/monitoring/assets/traffic/public';

    public $js = [
    	'traffic.js',
    ];

    public $depends = [
    	'meican\base\assets\Theme',
        'meican\topology\assets\map\LMap',
        'meican\topology\assets\graph\VGraph',
        'meican\base\assets\LSidebar',
        'yii\web\YiiAsset'
    ];
}