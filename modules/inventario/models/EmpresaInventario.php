<?php

namespace app\modules\inventario\models;

use Yii;

/**
 * This is the model class for table "empresa_inventario".
 *
 * @property int $einv_id
 * @property string $einv_descripcion
 * @property string $einv_estado
 * @property string $einv_fecha_creacion
 * @property string $einv_fecha_modificacion
 * @property string $einv_estado_logico
 *
 * @property ActivoFijo[] $activoFijos
 */
class EmpresaInventario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresa_inventario';
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
            [['einv_descripcion', 'einv_estado', 'einv_estado_logico'], 'required'],
            [['einv_fecha_creacion', 'einv_fecha_modificacion'], 'safe'],
            [['einv_descripcion'], 'string', 'max' => 200],
            [['einv_estado', 'einv_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'einv_id' => 'Einv ID',
            'einv_descripcion' => 'Einv Descripcion',
            'einv_estado' => 'Einv Estado',
            'einv_fecha_creacion' => 'Einv Fecha Creacion',
            'einv_fecha_modificacion' => 'Einv Fecha Modificacion',
            'einv_estado_logico' => 'Einv Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActivoFijos()
    {
        return $this->hasMany(ActivoFijo::className(), ['einv_id' => 'einv_id']);
    }
    
    /**
     * Function consulta las empresas del inventario
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarEmpresaInv() {
        $con = \Yii::$app->db_inventario;
        $estado = 1;

        $sql = "SELECT 
                    einv_id as id, 
                    einv_descripcion as name                 
                FROM 
                   " . $con->dbname . ".empresa_inventario
                WHERE einv_estado = :estado AND
                      einv_estado_logico = :estado";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
