<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "documento_aceptacion".
 *
 * @property int $dace_id
 * @property int $per_id
 * @property int $dadj_id
 * @property string $dace_archivo
 * @property string $dace_observacion
 * @property string $dace_fecha_maxima_aprobacion
 * @property string $dace_estado_aprobacion
 * @property int $dace_usuario_ingreso
 * @property int $dace_usuario_modifica
 * @property string $dace_estado
 * @property string $dace_fecha_creacion
 * @property string $dace_fecha_modificacion
 * @property string $dace_estado_logico
 */
class DocumentoAceptacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_aceptacion';
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
            [['per_id', 'dadj_id', 'dace_archivo', 'dace_estado_aprobacion', 'dace_estado', 'dace_estado_logico'], 'required'],
            [['per_id', 'dadj_id', 'dace_usuario_ingreso', 'dace_usuario_modifica'], 'integer'],
            [['dace_fecha_maxima_aprobacion', 'dace_fecha_creacion', 'dace_fecha_modificacion'], 'safe'],
            [['dace_archivo', 'dace_observacion'], 'string', 'max' => 500],
            [['dace_estado_aprobacion', 'dace_estado', 'dace_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dace_id' => 'Dace ID',
            'per_id' => 'Per ID',
            'dadj_id' => 'Dadj ID',
            'dace_archivo' => 'Dace Archivo',
            'dace_observacion' => 'Dace Observacion',
            'dace_fecha_maxima_aprobacion' => 'Dace Fecha Maxima Aprobacion',
            'dace_estado_aprobacion' => 'Dace Estado Aprobacion',
            'dace_usuario_ingreso' => 'Dace Usuario Ingreso',
            'dace_usuario_modifica' => 'Dace Usuario Modifica',
            'dace_estado' => 'Dace Estado',
            'dace_fecha_creacion' => 'Dace Fecha Creacion',
            'dace_fecha_modificacion' => 'Dace Fecha Modificacion',
            'dace_estado_logico' => 'Dace Estado Logico',
        ];
    }

    /**
     * Function consultaDocumentoAceptacionByPerId()
     * Consulta el estado del documento de aceptacion por el per id.
     * @author  Kleber Loayza <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  
    */
    public function consultaDocumentoAceptacionByPerId($con,$per_id){
        $con = \Yii::$app->db_academico;
        $estado = 1;
            $sql = "
                        select 
                            dace.dadj_id, dace.dace_observacion, dace.dace_estado_aprobacion, dace.dace_archivo
                        from
                            db_academico.documento_aceptacion as dace
                        where 
                            per_id=:per_id and
                            dace.dace_estado= :estado and
                            dace.dace_estado_logico= :estado;
                    ";        
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultRow = $comando->queryOne();
        return $resultRow;
    }

    public function insertar($con,$data)
    {                 
        $estado = '1';
        $estado_aprobacion='1';
        $sql = "INSERT INTO " . $con->dbname . ".documento_aceptacion
            (per_id,dadj_id,dace_archivo,dace_observacion,dace_usuario_ingreso,dace_estado_aprobacion,dace_estado,dace_estado_logico) VALUES
            (:per_id,:dadj_id,:dace_archivo,:dace_observacion,:dace_usuario_ingreso,:dace_estado_aprobacion,:dace_estado,:dace_estado)";
        $command = $con->createCommand($sql);        
        $command->bindParam(":per_id",  $data['per_id'], \PDO::PARAM_INT);
        $command->bindParam(":dadj_id",  $data['dadj_id'], \PDO::PARAM_INT);
        $command->bindParam(":dace_archivo",  $data['dace_archivo'], \PDO::PARAM_STR);
        $command->bindParam(":dace_observacion", $data['dace_observacion'], \PDO::PARAM_STR);
        $command->bindParam(":dace_usuario_ingreso", $data['dace_usuario_ingreso'], \PDO::PARAM_INT);       
        $command->bindParam(":dace_estado_aprobacion", $estado_aprobacion, \PDO::PARAM_INT);       
        $command->bindParam(":dace_estado", $estado, \PDO::PARAM_STR);      
        $command->execute();
        return $con->getLastInsertID();
    }
    
    
    
    public function consultarXperid($per_id)
    {
        $con = \Yii::$app->db_academico;
        $estado = 1;        
        $sql = "    SELECT dace_estado_aprobacion
                    FROM 
                         " . $con->dbname . ".documento_aceptacion
                    WHERE per_id = :per_id AND
                        dace_estado=:estado AND
                        dace_estado_logico=:estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);       
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT); 
        $resultData = $comando->queryOne();
        return $resultData;        
    }
    
    
    public function actualizar($con, $usuario, $per_id)
    {                 
        $estado = '1';
        $estado_inactiva = '0';
        $fecha_actual = date(Yii::$app->params["dateTimeByDefault"]);        
        $sql = "UPDATE " . $con->dbname . ".documento_aceptacion
                SET dace_usuario_modifica = :usuario,
                    dace_fecha_modificacion = :fecha_actual,
                    dace_estado = :estado_inactiva,
                    dace_estado_logico = :estado_inactiva
                WHERE per_id = :per_id
                      and dace_estado = :dace_estado
                      and dace_estado_logico = :dace_estado";  
        $command = $con->createCommand($sql);          
        $command->bindParam(":per_id",  $per_id, \PDO::PARAM_INT);
        $command->bindParam(":usuario",  $usuario, \PDO::PARAM_INT);
        $command->bindParam(":fecha_actual",  $fecha_actual, \PDO::PARAM_STR);
        $command->bindParam(":estado_inactiva", $estado_inactiva, \PDO::PARAM_STR);        
        $command->bindParam(":dace_estado", $estado, \PDO::PARAM_STR); 
        $response = $command->execute();
        return $response;
    }
}
