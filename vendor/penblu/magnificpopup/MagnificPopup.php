<?php

namespace app\vendor\penblu\magnificpopup;

use app\vendor\penblu\magnificpopup\MagnificPopupAsset;

/**
 * This is just an example.
 */
class MagnificPopup extends \yii\base\Widget
{
    public function init() {
        parent::init();
        MagnificPopupAsset::register($this->getView());
    }
    
    public function run()
    {
        parent::run();
    }
}
