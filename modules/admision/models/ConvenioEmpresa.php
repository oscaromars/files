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

        /**
     * Function obtener detalles de convenios que afecten a items de matriculacion
     * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarConvenioxMatricula($nint_id,$mod_id,$convenio,$ite_id) {
        $con = \Yii::$app->db_captacion;
        $con2 = \Yii::$app->db_facturacion;
        $estado = 1;
        $estado_convenio="A";
            $sql = "SELECT cede.cede_id, cede.cemp_id, imun.ite_id, cede.uaca_id, cede.mod_id,
        cede.cede_tipo, cede.cede_porcentaje_descuento, cede.cede_porcentaje_factor,
        cemp.cemp_estado_empresa, cemp.cemp_fecha_inicio, cemp.cemp_fecha_fin
        FROM " . $con->dbname . ".convenio_empresa_detalle as cede
        INNER JOIN " . $con->dbname . ".convenio_empresa as cemp ON cemp.cemp_id = cede.cemp_id
        INNER JOIN " . $con2->dbname . ".item_matricula_unidad  as imun ON imun.uaca_id = cede.uaca_id AND  imun.mod_id = cede.mod_id
              WHERE TRUE
                AND cemp.cemp_estado = :estado AND cemp.cemp_estado_logico = :estado 
                AND cede.cede_estado = :estado AND cede.cede_estado_logico = :estado 
                AND imun.imun_estado = :estado AND imun.imun_estado_logico = :estado 
                AND cede.uaca_id = :nivel AND cede.mod_id = :modalidad
                AND cede.cede_tipo = 'MA' AND cede.cemp_id = :convenio
                AND imun.ite_id = :item AND cemp.cemp_estado_empresa = :estado_convenio
                AND cemp.cemp_fecha_inicio <= NOW()
                AND cemp.cemp_fecha_fin > NOW()";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":nivel", $nint_id, \PDO::PARAM_INT);  
        $comando->bindParam(":modalidad", $mod_id, \PDO::PARAM_INT);  
        $comando->bindParam(":convenio", $convenio, \PDO::PARAM_INT);
        $comando->bindParam(":item", $ite_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $comando->bindParam(":estado_convenio", $estado_convenio, \PDO::PARAM_STR); 
        $resultData = $comando->queryAll();
        return $resultData;
    }

     /** 
     * Function crear Convenio x Persona 
     * @author  Oscar Sanchez <analistadesarrollo05@uteg.edu.ec>
     * @property 
     * @return  
     */
    private function crearConvenioxPersona($convenio,$per_id,$usu_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 1;  $estado_convenio="A";
        $sql = "INSERT INTO " . $con->dbname . ".solicitud_convenio_persona
                    (per_id,
                     cemp_id,
                     scpe_fecha_inicio,
                     scpe_estado_convenio,
                     scpe_fecha_creacion,
                     scpe_usuario_creacion,
                     scpe_estado,
                     scpe_estado_logico)VALUES
                    ('" . $per_id . "',
                    '" . $convenio . "',
                    CURRENT_TIMESTAMP(),
                    '" . $estado_convenio . "',
                    CURRENT_TIMESTAMP(),
                    '" . $usu_id . "',
                    '" . $estado . "',
                    '" . $estado . "')";
                    
        $command = $con->createCommand($sql);
        $command->execute();
    }  
}
