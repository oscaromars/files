<?php

namespace app\vendor\penblu\blockui;

use app\vendor\penblu\blockui\BlockuiAsset;

/**
 * This is just an example.
 */
class Blockui extends \yii\base\Widget
{
    public function init() {
        parent::init();
        BlockuiAsset::register($this->getView());
    }
    
    public function run()
    {
        parent::run();
    }
}
