<?php

namespace app\modules\academico\models;
use yii\base\Exception;

use Yii;

/**
 * This is the model class for table "datos_facturacion_registro".
 *
 * @property integer $dfr_id
 * @property string $dfr_ron_id
 * @property string $dfr_rama_id
 * @property string $dfr_per_id
 * @property string $dfr_cedula
 * @property string $dfr_nombre
 * @property string $dfr_apellidos
 * @property string $dfr_direccion
 * @property string $dfr_telefono
 * @property string $dfr_correo
 * @property string $dfr_usuario_ingreso
 * @property string $dfr_estado_logico
 * @property string $dfr_fecha_creacion
 */
class DatosFacturacionRegistro extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'datos_facturacion_registro';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dfr_id', 'dfr_ron_id', 'dfr_rama_id', 'dfr_per_id'], 'required'],
            [['dfr_fecha_creacion'], 'safe'],
            [['dfr_nombre', 'dfr_apellidos'], 'string', 'max' => 60],
            [['dfr_direccion'], 'string', 'max' => 100],
            [['dfr_cedula', 'dfr_telefono'], 'string', 'max' => 20],
            [['dfr_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dfr_id' => 'DFR ID',
            'dfr_ron_id' => 'Ron ID',
            'dfr_rama_id' => 'Rama ID',
            'dfr_per_id' => 'Per ID',
            'dfr_cedula' => 'Cedula',
            'dfr_nombre' => 'Nombre',
            'dfr_apellidos' => 'Apellidos',
            'dfr_direccion' => 'Direccion',
            'dfr_telefono' => 'Telefono',
            'dfr_correo' => 'Correo',
            'dfr_usuario_ingreso' => 'Usuario Ingreso',
            'dfr_estado_logico' => 'Estado Logico',
            'dfr_fecha_creacion' => 'Fecha Creacion',
        ];
    }

    /**
     * Function Insertar Datos de Facturacion.
     * @author  Diego Betancourt <analistadesarrollo08@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function insertDatosFacturacion($ron_id,$rama_id,$perid,$cedula,$nombre,$apellidos,$direccion,$telefono,$correo, $usuario) {
        try{
            $con = \Yii::$app->db_academico;
            $transaction=$con->beginTransaction();
            $estado = 1;
            
            $date = date(Yii::$app->params['dateTimeByDefault']);

            $sql = "INSERT INTO " . $con->dbname . ".datos_facturacion_registro 
            (dfr_ron_id, dfr_rama_id, dfr_per_id, dfr_cedula, dfr_nombre, dfr_apellidos, dfr_direccion, dfr_telefono, dfr_correo, dfr_usuario_ingreso, dfr_estado_logico, dfr_fecha_creacion)
                    value($ron_id, $rama_id, $perid, '$cedula', '$nombre', '$apellidos', '$direccion', '$correo', '$telefono', $usuario, $estado, '$date');";

            $comando = $con->createCommand($sql);
            $comando->execute();

                \app\models\Utilities::putMessageLogFile('insertDatosFacturacion: ' . $comando->getRawSql());

                if ($transaction !== null){
                    $transaction->commit();
                }
            //return $resultData;
            return true;

        } catch (Exception $ex) {
            if ($transaction !== null)
                \app\models\Utilities::putMessageLogFile('insertDatosFacturacion: error ' . $ex->getMessage());
                $transaction->rollback();
            return FALSE;
        }
    }

    public static function getDatosFacturacionPersona($per_id)
    {
        $con = \Yii::$app->db_asgard;
        $sql = "SELECT uge.grol_id as rol, p.per_cedula as cedula,
                concat(p.per_pri_nombre,' ',p.per_seg_nombre) as nombre, 
                concat(p.per_pri_apellido,' ',p.per_seg_apellido) as apellidos,
                concat(p.per_domicilio_sector,' ',per_domicilio_cpri,' ',per_domicilio_csec,' ',per_domicilio_num) as direccion,
                ifnull(p.per_celular,p.per_domicilio_telefono) as telefono,
                p.per_correo as correo
                from " . $con->dbname . ".usua_grol_eper uge
                inner join " . $con->dbname . ".persona p on p.per_id = uge.eper_id and p.per_id = $per_id limit 0,1;";
                
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();

        return $resultData;
    }
    public static function getDatosFacturacion($per_id)
    {
        $con = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $sql = "SELECT uge.grol_id as rol, dfr.dfr_cedula as cedula,
                dfr.dfr_nombre as nombre, 
                dfr.dfr_apellidos as apellidos,
                dfr.dfr_direccion as direccion,
                dfr.dfr_telefono as telefono,
                dfr.dfr_correo as correo 
                from " . $con2->dbname . ".datos_facturacion_registro dfr
                inner join " . $con->dbname . ".usua_grol_eper uge on uge.eper_id = dfr.dfr_per_id 
                and dfr.dfr_per_id = $per_id order by dfr.dfr_id desc limit 0,1;";
                
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();

        return $resultData;
    }

}