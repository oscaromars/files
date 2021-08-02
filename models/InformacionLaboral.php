<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacion_laboral".
 *
 * @property int $ilab_id
 * @property int $per_id
 * @property string $ilab_empresa
 * @property string $ilab_cargo
 * @property string $ilab_telefono_emp
 * @property int $ilab_prov_emp
 * @property int $ilab_ciu_emp
 * @property string $ilab_parroquia
 * @property string $ilab_direccion_emp
 * @property string $ilab_añoingreso_emp
 * @property string $ilab_correo_emp
 * @property string $ilab_cat_ocupacional
 * @property int $ilab_usuario_ingreso
 * @property int $ilab_usuario_modifica
 * @property string $ilab_estado
 * @property string $ilab_fecha_creacion
 * @property string $ilab_fecha_modificacion
 * @property string $ilab_estado_logico
 */
class InformacionLaboral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informacion_laboral';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inscripcion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['per_id', 'ilab_empresa', 'ilab_prov_emp', 'ilab_usuario_ingreso', 'ilab_estado', 'ilab_estado_logico'], 'required'],
            [['per_id', 'ilab_prov_emp', 'ilab_ciu_emp', 'ilab_usuario_ingreso', 'ilab_usuario_modifica'], 'integer'],
            [['ilab_fecha_creacion', 'ilab_fecha_modificacion'], 'safe'],
            [['ilab_empresa', 'ilab_cargo', 'ilab_parroquia', 'ilab_direccion_emp', 'ilab_añoingreso_emp', 'ilab_correo_emp', 'ilab_cat_ocupacional'], 'string', 'max' => 200],
            [['ilab_telefono_emp'], 'string', 'max' => 10],
            [['ilab_estado', 'ilab_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ilab_id' => 'Ilab ID',
            'per_id' => 'Per ID',
            'ilab_empresa' => 'Ilab Empresa',
            'ilab_cargo' => 'Ilab Cargo',
            'ilab_telefono_emp' => 'Ilab Telefono Emp',
            'ilab_prov_emp' => 'Ilab Prov Emp',
            'ilab_ciu_emp' => 'Ilab Ciu Emp',
            'ilab_parroquia' => 'Ilab Parroquia',
            'ilab_direccion_emp' => 'Ilab Direccion Emp',
            'ilab_añoingreso_emp' => 'Ilab Añoingreso Emp',
            'ilab_correo_emp' => 'Ilab Correo Emp',
            'ilab_cat_ocupacional' => 'Ilab Cat Ocupacional',
            'ilab_usuario_ingreso' => 'Ilab Usuario Ingreso',
            'ilab_usuario_modifica' => 'Ilab Usuario Modifica',
            'ilab_estado' => 'Ilab Estado',
            'ilab_fecha_creacion' => 'Ilab Fecha Creacion',
            'ilab_fecha_modificacion' => 'Ilab Fecha Modificacion',
            'ilab_estado_logico' => 'Ilab Estado Logico',
        ];
    }

    /**
     * Function consultaEstudianteinstruccion
     * @author  Lisbeth Gonzalez <analista.desarrollo@uteg.edu.ec>
     * @property integer $userid       
     * @return  
     */
    public function consultarInfoLaboral($per_id) {
        $con = \Yii::$app->db_inscripcion;
        $estado = 1;

        $sql = "
                SELECT   
                         count(*) as existe_infolaboral
                FROM " . $con->dbname . ".informacion_laboral 
                WHERE per_id = :per_id AND 
                     ilab_estado = :estado AND
                     ilab_estado_logico = :estado";
                     
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function insertarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional) {
        $con = \Yii::$app->db_inscripcion;

        $sql = "INSERT INTO " . $con->dbname . ".informacion_laboral
            (per_id, ilab_empresa, ilab_cargo, ilab_telefono_emp, ilab_prov_emp, ilab_ciu_emp, ilab_parroquia, ilab_direccion_emp, ilab_añoingreso_emp, ilab_correo_emp, ilab_cat_ocupacional, ilab_estado, ilab_fecha_modificacion, ilab_estado_logico) VALUES
            ($per_id, '$empresa', '$cargo', '$telefono_emp', $prov_emp, $ciu_emp, '$parroquia', '$direccion_emp', '$añoingreso_emp', '$correo_emp', '$cat_ocupacional', 1, CURRENT_TIMESTAMP(), 1)";

        
        $command = $con->createCommand($sql);
        $command->execute();
        return $con->getLastInsertID($con->dbname . '.informacion_laboral');
        
    }

    public function modificarInfoLaboral($per_id, $empresa, $cargo, $telefono_emp, $prov_emp, $ciu_emp, $parroquia, $direccion_emp, $añoingreso_emp, $correo_emp, $cat_ocupacional) {
        $con = \Yii::$app->db_inscripcion;
        $ilab_fecha_modificacion = date("Y-m-d H:i:s");
        $estado='1';
        
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".informacion_laboral             
                      SET 
                        per_id =:per_id, 
                        ilab_empresa =:ilab_empresa, 
                        ilab_cargo =:ilab_cargo, 
                        ilab_telefono_emp =:ilab_telefono_emp, 
                        ilab_prov_emp =:ilab_prov_emp, 
                        ilab_ciu_emp =:ilab_ciu_emp, 
                        ilab_parroquia =:ilab_parroquia, 
                        ilab_direccion_emp =:ilab_direccion_emp, 
                        ilab_añoingreso_emp =:ilab_añoingreso_emp, 
                        ilab_correo_emp =:ilab_correo_emp, 
                        ilab_cat_ocupacional =:ilab_cat_ocupacional,
                        ilab_fecha_modificacion =:ilab_fecha_modificacion,
                      WHERE 
                        per_id =:per_id AND
                        ilab_estado =:estado AND
                        ilab_estado_logico =:estado");
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":ilab_empresa", $empresa, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_cargo", $cargo, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_telefono_emp", $telefono_emp, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_prov_emp", $prov_emp, \PDO::PARAM_INT);
            $comando->bindParam(":ilab_ciu_emp", $ciu_emp, \PDO::PARAM_INT);
            $comando->bindParam(":ilab_parroquia", $parroquia, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_direccion_emp", $direccion_emp, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_añoingreso_emp", $añoingreso_emp, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_correo_emp", $correo_emp, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_cat_ocupacional", $cat_ocupacional, \PDO::PARAM_STR);
            $comando->bindParam(":ilab_fecha_modificacion", $ilab_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $response = $comando->execute();
            
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }  
}
