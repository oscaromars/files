<?php

namespace app\modules\inventario\models;

use Yii;

/**
 * This is the model class for table "tipo_bien".
 *
 * @property int $tbie_id
 * @property string $tbie_descripcion
 * @property string $tbie_estado
 * @property string $tbie_fecha_creacion
 * @property string $tbie_fecha_modificacion
 * @property string $tbie_estado_logico
 *
 * @property Categoria[] $categorias
 */
class TipoBien extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_bien';
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
            [['tbie_descripcion', 'tbie_estado', 'tbie_estado_logico'], 'required'],
            [['tbie_fecha_creacion', 'tbie_fecha_modificacion'], 'safe'],
            [['tbie_descripcion'], 'string', 'max' => 200],
            [['tbie_estado', 'tbie_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tbie_id' => 'Tbie ID',
            'tbie_descripcion' => 'Tbie Descripcion',
            'tbie_estado' => 'Tbie Estado',
            'tbie_fecha_creacion' => 'Tbie Fecha Creacion',
            'tbie_fecha_modificacion' => 'Tbie Fecha Modificacion',
            'tbie_estado_logico' => 'Tbie Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategorias()
    {
        return $this->hasMany(Categoria::className(), ['tbie_id' => 'tbie_id']);
    }
    
    /**
     * Function consulta los tipos de bienes
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarTipoBien() {
        $con = \Yii::$app->db_inventario;
        $estado = 1;

        $sql = "SELECT tbie_id id, tbie_descripcion name
                FROM db_inventario.tipo_bien 
                WHERE tbie_estado = :estado and 
                      tbie_estado_logico = :estado";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
