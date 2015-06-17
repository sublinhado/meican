<?php

namespace app\modules\topology\assets;

use yii\web\AssetBundle;

class AggregatorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
    	'js/topology/aggregator.js',
    ];
}
