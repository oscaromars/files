<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "nivel_instruccion".
 *
 * @property int $nins_id
 * @property string $nins_nombre
 * @property string $nins_descripcion
 * @property string $nins_estado
 * @property string $nins_fecha_creacion
 * @property string $nins_fecha_modificacion
 * @property string $nins_estado_logico
 */
class NivelInstruccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nivel_instruccion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nins_estado', 'nins_estado_logico'], 'required'],
            [['nins_fecha_creacion', 'nins_fecha_modificacion'], 'safe'],
            [['nins_nombre'], 'string', 'max' => 250],
            [['nins_descripcion'], 'string', 'max' => 500],
            [['nins_estado', 'nins_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nins_id' => 'Nins ID',
            'nins_nombre' => 'Nins Nombre',
            'nins_descripcion' => 'Nins Descripcion',
            'nins_estado' => 'Nins Estado',
            'nins_fecha_creacion' => 'Nins Fecha Creacion',
            'nins_fecha_modificacion' => 'Nins Fecha Modificacion',
            'nins_estado_logico' => 'Nins Estado Logico',
        ];
    }
    
    /** Consulta de los niveles de instrucción de estudios. **
     * Function consultarNivelInstruccion
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @property     
     * @return  
     */
    public function consultarNivelInstruccion() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "    SELECT 
                        nins_id AS id,
                        nins_nombre AS value
                    FROM 
                         " . $con->dbname . ".nivel_instruccion                        
                    WHERE                         
                        nins_estado=:estado AND
                        nins_estado_logico=:estado 
                    ORDER BY nins_id ASC
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
