<?php

namespace app\modules\marketing\models;

use Yii;

/**
 * This is the model class for table "interes".
 *
 * @property int $int_id
 * @property string $int_descripcion
 * @property string $int_nombre
 * @property string $int_estado
 * @property string $int_fecha_creacion
 * @property string $int_fecha_modificacion
 * @property string $int_estado_logico
 *
 * @property PersonaExternaIntereses[] $personaExternaIntereses
 */
class Interes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interes';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_mailing');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['int_descripcion', 'int_nombre', 'int_estado', 'int_estado_logico'], 'required'],
            [['int_fecha_creacion', 'int_fecha_modificacion'], 'safe'],
            [['int_descripcion', 'int_nombre'], 'string', 'max' => 100],
            [['int_estado', 'int_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'int_id' => 'Int ID',
            'int_descripcion' => 'Int Descripcion',
            'int_nombre' => 'Int Nombre',
            'int_estado' => 'Int Estado',
            'int_fecha_creacion' => 'Int Fecha Creacion',
            'int_fecha_modificacion' => 'Int Fecha Modificacion',
            'int_estado_logico' => 'Int Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaExternaIntereses()
    {
        return $this->hasMany(PersonaExternaIntereses::className(), ['int_id' => 'int_id']);
    }
    
    
    public function consultarInteres()
    {
        $con = \Yii::$app->db_mailing;
        $estado = 1;        
        $sql = "    SELECT 
                        int_id AS id,
                        int_nombre AS value
                    FROM 
                         " . $con->dbname . ".interes                      
                    WHERE 
                        int_estado=:estado AND
                        int_estado_logico=:estado 
                    ORDER BY int_id ASC";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;        
    }
}
