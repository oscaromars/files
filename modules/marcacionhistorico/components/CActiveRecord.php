<?php

namespace app\modules\marcacion_historico\components;

use Yii;
use yii\base\Application;
use app\components\CConnection;
use yii\base\Component;
use yii\helpers\StringHelper;
use yii\helpers\Inflector;

class CActiveRecord extends \yii\db\ActiveRecord {
    public static function getDb()
    {
        $connection = new \app\components\CConnection(Yii::$app->controller->module->db_marcacion_historico);
        return $connection;
        //return Yii::createObject(Yii::$app->controller->module->db_marcacion_historico);
    }

    public static function tableName()
    {
        if(Yii::$app->controller->module->db_marcacion_historico["dbname"]){
            $dbname = StringHelper::basename(Yii::$app->controller->module->db_marcacion_historico["dbname"]);
            return '{{%' . $dbname . '.' . StringHelper::basename(get_called_class()) . '}}';
        }
        return '{{%' . Inflector::camel2id(StringHelper::basename(get_called_class()), '_') . '}}';
    }

}
