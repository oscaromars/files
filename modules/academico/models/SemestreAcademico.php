<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "semestre_academico".
 *
 * @property int $saca_id
 * @property string $saca_nombre
 * @property string $saca_descripcion
 * @property int $saca_anio
 * @property string $saca_fecha_registro
 * @property int $saca_usuario_ingreso
 * @property int $saca_usuario_modifica
 * @property string $saca_estado
 * @property string $saca_fecha_creacion
 * @property string $saca_fecha_modificacion
 * @property string $saca_estado_logico
 *
 * @property Distributivo[] $distributivos
 * @property PeriodoAcademico[] $periodoAcademicos
 * @property ResumenEvaluacionDocente[] $resumenEvaluacionDocentes
 * @property ResumenResultadoEvaluacion[] $resumenResultadoEvaluacions
 */
class SemestreAcademico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'semestre_academico';
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
            [['saca_nombre', 'saca_descripcion', 'saca_anio', 'saca_usuario_ingreso', 'saca_estado', 'saca_estado_logico'], 'required'],
            [['saca_anio', 'saca_usuario_ingreso', 'saca_usuario_modifica'], 'integer'],
            [['saca_fecha_registro', 'saca_fecha_creacion', 'saca_fecha_modificacion'], 'safe'],
            [['saca_nombre', 'saca_descripcion'], 'string', 'max' => 300],
            [['saca_estado', 'saca_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'saca_id' => 'Saca ID',
            'saca_nombre' => 'Saca Nombre',
            'saca_descripcion' => 'Saca Descripcion',
            'saca_anio' => 'Saca Anio',
            'saca_fecha_registro' => 'Saca Fecha Registro',
            'saca_usuario_ingreso' => 'Saca Usuario Ingreso',
            'saca_usuario_modifica' => 'Saca Usuario Modifica',
            'saca_estado' => 'Saca Estado',
            'saca_fecha_creacion' => 'Saca Fecha Creacion',
            'saca_fecha_modificacion' => 'Saca Fecha Modificacion',
            'saca_estado_logico' => 'Saca Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivos()
    {
        return $this->hasMany(Distributivo::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoAcademicos()
    {
        return $this->hasMany(PeriodoAcademico::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumenEvaluacionDocentes()
    {
        return $this->hasMany(ResumenEvaluacionDocente::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResumenResultadoEvaluacions()
    {
        return $this->hasMany(ResumenResultadoEvaluacion::className(), ['saca_id' => 'saca_id']);
    }
    
    /**
     * Function consulta descripción de semestres.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarSemestres() {
        $con = \Yii::$app->db_academico;
        $estado = 1;    
        $sql = " SELECT 
                        concat(saca_nombre,' ',saca_anio) as name,
                        saca_id as id
                 FROM 
                       " . $con->dbname . ".semestre_academico           
                 WHERE   
                        saca_estado_logico=:estado AND 
                        saca_estado=:estado
                 ORDER BY 1 asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }        
}
