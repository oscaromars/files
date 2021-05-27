<?php
/**
 * @package   PbGridView
 * @author    Eduardo Cueva <ecueva@penblu.com>
 * @copyright Copyright &copy; PenBlu S.A, 2014 - 2018
 * @version   1.0.0
 */

namespace app\widgets\PbGridView;

use yii\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use Yii;

class PbCheckboxColumn extends CheckboxColumn {
    
    public function init()
    {
        parent::init();
    }
    
     /* Registers the needed JavaScript.
     * @since 2.0.8
     */
    public function registerClientScript()
    {
        $id = $this->grid->options['id'];
        $options = Json::encode([
            'name' => $this->name,
            'class' => $this->cssClass,
            'multiple' => $this->multiple,
            'checkAll' => $this->grid->showHeader ? $this->getHeaderCheckBoxName() : null,
        ]);
        $this->grid->getView()->registerJs("jQuery('#$id').PbGridView('setSelectionColumn', $options);");
    }
}