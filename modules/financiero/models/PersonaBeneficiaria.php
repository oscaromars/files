<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "persona_beneficiaria".
 *
 * @property int $pben_id
 * @property string $pben_nombre
 * @property string $pben_apellido
 * @property string $pben_cedula
 * @property string $pben_ruc
 * @property string $pben_pasaporte
 * @property string $pben_celular
 * @property string $pben_correo
 * @property string $pben_estado
 * @property string $pben_fecha_creacion
 * @property string $pben_fecha_modificacion
 * @property string $pben_estado_logico
 *
 * @property SolicitudBotonPago[] $solicitudBotonPagos
 */
class PersonaBeneficiaria extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_beneficiaria';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pben_cedula', 'pben_estado', 'pben_estado_logico'], 'required'],
            [['pben_fecha_creacion', 'pben_fecha_modificacion'], 'safe'],
            [['pben_nombre', 'pben_apellido', 'pben_correo'], 'string', 'max' => 250],
            [['pben_cedula', 'pben_ruc'], 'string', 'max' => 15],
            [['pben_pasaporte', 'pben_celular'], 'string', 'max' => 50],
            [['pben_estado', 'pben_estado_logico'], 'string', 'max' => 1],
        ];
    }
 
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pben_id' => 'Pben ID',
            'pben_nombre' => 'Pben Nombre',
            'pben_apellido' => 'Pben Apellido',
            'pben_cedula' => 'Pben Cedula',
            'pben_ruc' => 'Pben Ruc',
            'pben_pasaporte' => 'Pben Pasaporte',
            'pben_celular' => 'Pben Celular',
            'pben_correo' => 'Pben Correo',
            'pben_estado' => 'Pben Estado',
            'pben_fecha_creacion' => 'Pben Fecha Creacion',
            'pben_fecha_modificacion' => 'Pben Fecha Modificacion',
            'pben_estado_logico' => 'Pben Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudBotonPagos()
    {
        return $this->hasMany(SolicitudBotonPago::className(), ['pben_id' => 'pben_id']);
    }
    

    public function getIdPerBenByCed($con, $cedula){        
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql= "select ifnull(pben_id,0) id 
               from " . $con->dbname . ".persona_beneficiaria 
               where pben_cedula = :cedula and
               pben_estado = :estado and pben_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);      
        $resultData = $comando->queryOne();        
        return $resultData;      
    }
    
    public function insertPersonaBeneficia($con, $cedula,$nombre,$apellido,$correo,$celular) {  
        $estado = 1;
        $sql = "INSERT INTO " . $con->dbname . ".persona_beneficiaria
            (pben_nombre,pben_apellido,pben_cedula,pben_ruc,pben_pasaporte,pben_celular,pben_correo,pben_estado,pben_estado_logico) VALUES
            (:pben_nombre,:pben_apellido,:pben_cedula,:pben_ruc,:pben_pasaporte,:pben_celular,:pben_correo,:pben_estado,:pben_estado)";
        $command = $con->createCommand($sql);
        $command->bindParam(":pben_nombre", $nombre, \PDO::PARAM_STR);
        $command->bindParam(":pben_apellido", $apellido, \PDO::PARAM_STR);
        $command->bindParam(":pben_cedula", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_ruc", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_pasaporte", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_celular", $celular, \PDO::PARAM_STR);
        $command->bindParam(":pben_correo", $correo, \PDO::PARAM_STR);
        $command->bindParam(":pben_estado", $estado, \PDO::PARAM_STR);        
        $command->execute();
        return $con->getLastInsertID();        
    }
    
    public function actualizarPersonaBeneficia($con, $cedula,$nombre,$apellido,$correo,$celular) {  
        $estado = 1;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $sql = "UPDATE " . $con->dbname . ".persona_beneficiaria
                set pben_nombre = :pben_nombre,
                    pben_apellido = :pben_apellido,                    
                    pben_ruc = :pben_ruc,
                    pben_pasaporte = :pben_pasaporte,
                    pben_celular = :pben_celular,
                    pben_correo = :pben_correo,
                    pben_fecha_modificacion = :fecha
                WHERE pben_cedula = :pben_cedula
                and pben_estado = :pben_estado
                and pben_estado_logico = :pben_estado";
                
         \app\models\Utilities::putMessageLogFile('sql: ' . $sql);
        $command = $con->createCommand($sql);
        $command->bindParam(":pben_nombre", $nombre, \PDO::PARAM_STR);
        $command->bindParam(":pben_apellido", $apellido, \PDO::PARAM_STR);
        $command->bindParam(":pben_cedula", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_ruc", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_pasaporte", $cedula, \PDO::PARAM_STR);
        $command->bindParam(":pben_celular", $celular, \PDO::PARAM_STR);
        $command->bindParam(":pben_correo", $correo, \PDO::PARAM_STR);
        $command->bindParam(":pben_estado", $estado, \PDO::PARAM_STR);      
        $command->bindParam(":fecha", $fecha, \PDO::PARAM_STR);      
        $response = $command->execute();
        return $response;              
    }
}
