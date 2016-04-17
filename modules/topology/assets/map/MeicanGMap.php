<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican2#license
 */

namespace meican\topology\assets\map;

use yii\web\AssetBundle;

/**
 * @author Maurício Quatrin Guerreiro @mqgmaster
 */
class MeicanGMap extends AssetBundle
{
    public $sourcePath = '@meican/topology/assets/map/public';

    public $js = [
        'meican-gmap.js',
    ];
    public $depends = [
        'meican\base\assets\google\Maps',
    ];
}