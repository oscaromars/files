<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "departamento".
 *
 * @property int $dep_id
 * @property string $dep_nombre
 * @property string $dep_estado
 * @property int $dep_usuario_ingreso
 * @property int $dep_usuario_modifica
 * @property string $dep_fecha_creacion
 * @property string $dep_fecha_modificacion
 * @property string $dep_estado_logico
 *
 * @property Area[] $areas
 */
class Departamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departamento';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_general');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dep_nombre', 'dep_estado', 'dep_estado_logico'], 'required'],
            [['dep_usuario_ingreso', 'dep_usuario_modifica'], 'integer'],
            [['dep_fecha_creacion', 'dep_fecha_modificacion'], 'safe'],
            [['dep_nombre'], 'string', 'max' => 200],
            [['dep_estado', 'dep_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dep_id' => 'Dep ID',
            'dep_nombre' => 'Dep Nombre',
            'dep_estado' => 'Dep Estado',
            'dep_usuario_ingreso' => 'Dep Usuario Ingreso',
            'dep_usuario_modifica' => 'Dep Usuario Modifica',
            'dep_fecha_creacion' => 'Dep Fecha Creacion',
            'dep_fecha_modificacion' => 'Dep Fecha Modificacion',
            'dep_estado_logico' => 'Dep Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['dep_id' => 'dep_id']);
    }
    
    /**
     * Function consulta los departamentos
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarDepartamento($empresa) {
        $con = \Yii::$app->db_general;
        $estado = 1;

        if ($empresa == 1) {
            $sql = "SELECT dep_id id, dep_nombre name
                    FROM db_general.departamento 
                    WHERE dep_estado = :estado and 
                          dep_estado_logico = :estado";
        }
        $comando = $con->createCommand($sql);        
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
