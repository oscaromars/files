<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $are_id
 * @property int $dep_id
 * @property int $edi_id
 * @property string $are_cod
 * @property string $are_descripcion
 * @property string $are_estado
 * @property string $are_fecha_creacion
 * @property string $are_fecha_modificacion
 * @property string $are_estado_logico
 *
 * @property Edificio $edi
 * @property Departamento $dep
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area';
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
            [['dep_id', 'edi_id', 'are_descripcion', 'are_estado', 'are_estado_logico'], 'required'],
            [['dep_id', 'edi_id'], 'integer'],
            [['are_fecha_creacion', 'are_fecha_modificacion'], 'safe'],
            [['are_cod'], 'string', 'max' => 20],
            [['are_descripcion'], 'string', 'max' => 200],
            [['are_estado', 'are_estado_logico'], 'string', 'max' => 1],
            [['edi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Edificio::className(), 'targetAttribute' => ['edi_id' => 'edi_id']],
            [['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departamento::className(), 'targetAttribute' => ['dep_id' => 'dep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'are_id' => 'Are ID',
            'dep_id' => 'Dep ID',
            'edi_id' => 'Edi ID',
            'are_cod' => 'Are Cod',
            'are_descripcion' => 'Are Descripcion',
            'are_estado' => 'Are Estado',
            'are_fecha_creacion' => 'Are Fecha Creacion',
            'are_fecha_modificacion' => 'Are Fecha Modificacion',
            'are_estado_logico' => 'Are Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEdi()
    {
        return $this->hasOne(Edificio::className(), ['edi_id' => 'edi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDep()
    {
        return $this->hasOne(Departamento::className(), ['dep_id' => 'dep_id']);
    }
    
     /**
     * Function consulta las áreas
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarAreas($dep_id, $empresa) {
        $con = \Yii::$app->db_general;
        $estado = 1;

        if ($empresa == 1) {
            $sql = "SELECT are_id id, are_descripcion name
                    FROM " . $con->dbname . ".area
                    WHERE dep_id = :dep_id 
                          and are_estado = :estado 
                          and are_estado_logico = :estado";
        }
        $comando = $con->createCommand($sql);        
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":dep_id", $dep_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
