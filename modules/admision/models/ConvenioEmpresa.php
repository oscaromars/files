<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "convenio_empresa".
 *
 * @property int $cemp_id
 * @property string $cemp_nombre
 * @property string $cemp_estado_empresa
 * @property string $cemp_estado
 * @property string $cemp_fecha_creacion
 * @property string $cemp_fecha_modificacion
 * @property string $cemp_estado_logico
 *
 * @property SolicitudInscripcion[] $solicitudInscripcions
 */
class ConvenioEmpresa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'convenio_empresa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cemp_nombre', 'cemp_estado_empresa', 'cemp_estado', 'cemp_estado_logico'], 'required'],
            [['cemp_fecha_creacion', 'cemp_fecha_modificacion'], 'safe'],
            [['cemp_nombre'], 'string', 'max' => 500],
            [['cemp_estado_empresa', 'cemp_estado', 'cemp_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cemp_id' => 'Cemp ID',
            'cemp_nombre' => 'Cemp Nombre',
            'cemp_estado_empresa' => 'Cemp Estado Empresa',
            'cemp_estado' => 'Cemp Estado',
            'cemp_fecha_creacion' => 'Cemp Fecha Creacion',
            'cemp_fecha_modificacion' => 'Cemp Fecha Modificacion',
            'cemp_estado_logico' => 'Cemp Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudInscripcions()
    {
        return $this->hasMany(SolicitudInscripcion::className(), ['cemp_id' => 'cemp_id']);
    }
    
    /**
     * Function obtener Modalidad segun nivel interes estudio
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarConvenioEmpresa() {
        $con = \Yii::$app->db_captacion;
        $estado = 1;
        $estado_convenio="A";
            $sql = "SELECT cemp_id as id,
                           cemp_nombre as name
                    FROM " . $con->dbname . ".convenio_empresa ce
                    WHERE cemp_estado_empresa = :estado_convenio
                        and cemp_estado_logico = :estado
                        and cemp_estado = :estado
                    ORDER BY name asc";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":estado_convenio", $estado_convenio, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
