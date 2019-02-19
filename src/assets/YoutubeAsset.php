<?php
/**
 * Created by PhpStorm.
 * User: myroot
 * Date: 08.02.19
 * Time: 16:50
 */

namespace saschati\youtube\assets;

use yii\web\View;
use yii\web\AssetBundle;

class YoutubeAsset extends AssetBundle
{
    public $js = [
        'https://www.youtube.com/iframe_api'
    ];

    public $jsOptions = [
        'position' => View::POS_HEAD
    ];
}