<?php

namespace app\modules\piensaecuador\models;

use Yii;

/**
 * This is the model class for table "ocupacion".
 *
 * @property int $ocu_id
 * @property string $ocu_nombre
 * @property string $ocu_descripcion
 * @property string $ocu_estado
 * @property string $ocu_fecha_creacion
 * @property string $ocu_fecha_modificacion
 * @property string $ocu_estado_logico
 *
 * @property PersonaExterna[] $personaExternas
 */
class Ocupacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ocupacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ocu_nombre', 'ocu_descripcion', 'ocu_estado', 'ocu_estado_logico'], 'required'],
            [['ocu_fecha_creacion', 'ocu_fecha_modificacion'], 'safe'],
            [['ocu_nombre', 'ocu_descripcion'], 'string', 'max' => 200],
            [['ocu_estado', 'ocu_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ocu_id' => 'Ocu ID',
            'ocu_nombre' => 'Ocu Nombre',
            'ocu_descripcion' => 'Ocu Descripcion',
            'ocu_estado' => 'Ocu Estado',
            'ocu_fecha_creacion' => 'Ocu Fecha Creacion',
            'ocu_fecha_modificacion' => 'Ocu Fecha Modificacion',
            'ocu_estado_logico' => 'Ocu Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaExternas()
    {
        return $this->hasMany(PersonaExterna::className(), ['ocu_id' => 'ocu_id']);
    }

    public function consultarOcupaciones()
    {
        $con = \Yii::$app->db_externo;
        $estado = 1;        
        $sql = "    SELECT 
                        ocu_id AS id,
                        ocu_nombre AS value
                    FROM 
                         " . $con->dbname . ".ocupacion                      
                    WHERE 
                        ocu_estado=:estado AND
                        ocu_estado_logico=:estado 
                    ORDER BY ocu_id ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;        
    }
}
