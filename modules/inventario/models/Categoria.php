<?php

namespace app\modules\inventario\models;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property int $cat_id
 * @property int $tbie_id
 * @property string $cat_descripcion
 * @property string $cat_estado
 * @property string $cat_fecha_creacion
 * @property string $cat_fecha_modificacion
 * @property string $cat_estado_logico
 *
 * @property ActivoFijo[] $activoFijos
 * @property TipoBien $tbie
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inventario');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tbie_id', 'cat_descripcion', 'cat_estado', 'cat_estado_logico'], 'required'],
            [['tbie_id'], 'integer'],
            [['cat_fecha_creacion', 'cat_fecha_modificacion'], 'safe'],
            [['cat_descripcion'], 'string', 'max' => 200],
            [['cat_estado', 'cat_estado_logico'], 'string', 'max' => 1],
            [['tbie_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoBien::className(), 'targetAttribute' => ['tbie_id' => 'tbie_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cat_id' => 'Cat ID',
            'tbie_id' => 'Tbie ID',
            'cat_descripcion' => 'Cat Descripcion',
            'cat_estado' => 'Cat Estado',
            'cat_fecha_creacion' => 'Cat Fecha Creacion',
            'cat_fecha_modificacion' => 'Cat Fecha Modificacion',
            'cat_estado_logico' => 'Cat Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivoFijos()
    {
        return $this->hasMany(ActivoFijo::className(), ['cat_id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTbie()
    {
        return $this->hasOne(TipoBien::className(), ['tbie_id' => 'tbie_id']);
    }
    
    /**
     * Function consulta las categorías
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarCategoria($tbie_id) {
        $con = \Yii::$app->db_inventario;
        $estado = 1;

        $sql = "SELECT cat_id id, cat_descripcion name
                FROM db_inventario.categoria 
                WHERE tbie_id = :tbie_id and 
                      cat_estado = :estado and 
                      cat_estado_logico = :estado";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":tbie_id", $tbie_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
