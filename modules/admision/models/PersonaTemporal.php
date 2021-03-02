<?php

namespace app\modules\admision\models;
use yii\data\ArrayDataProvider;

use Yii;

/**
 * This is the model class for table "persona_temporal".
 *
 * @property int $tper_id
 * @property string $tper_dni
 * @property string $tper_tipo_dni
 * @property string $tper_genero
 * @property string $tper_nombres
 * @property string $tper_apellidos
 * @property string $tper_correo
 * @property string $tper_direccion
 * @property string $tper_ciudad
 * @property string $tper_provincia
 * @property string $tper_pais
 * @property string $tper_telefono
 * @property string $tper_celular
 * @property string $tper_unidad_academica
 * @property string $tper_modalidad
 * @property string $tper_carrera
 * @property string $tper_programa
 * @property string $tper_curso
 * @property string $tper_canal_contacto
 * @property string $tper_estado
 * @property string $tper_observacion
 * @property string $tper_observacion2
 * @property string $tper_fecha_creacion
 * @property string $tper_fecha_modificacion
 * @property string $tper_estado_logico
 */
class PersonaTemporal extends \app\modules\admision\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_temporal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tper_dni', 'tper_tipo_dni', 'tper_genero', 'tper_nombres', 'tper_apellidos', 'tper_correo', 'tper_direccion', 'tper_ciudad', 'tper_provincia', 'tper_pais', 'tper_telefono', 'tper_celular', 'tper_unidad_academica', 'tper_modalidad', 'tper_carrera', 'tper_programa', 'tper_curso', 'tper_canal_contacto', 'tper_estado', 'tper_observacion', 'tper_observacion2', 'tper_estado_logico'], 'required'],
            [['tper_fecha_creacion', 'tper_fecha_modificacion'], 'safe'],
            [['tper_dni', 'tper_tipo_dni', 'tper_genero', 'tper_nombres', 'tper_apellidos', 'tper_correo', 'tper_direccion', 'tper_ciudad', 'tper_provincia', 'tper_pais', 'tper_telefono', 'tper_celular', 'tper_modalidad', 'tper_carrera', 'tper_canal_contacto', 'tper_estado', 'tper_observacion', 'tper_observacion2'], 'string', 'max' => 100],
            [['tper_unidad_academica', 'tper_programa', 'tper_curso'], 'string', 'max' => 1000],
            [['tper_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tper_id' => 'Tper ID',
            'tper_dni' => 'Tper Dni',
            'tper_tipo_dni' => 'Tper Tipo Dni',
            'tper_genero' => 'Tper Genero',
            'tper_nombres' => 'Tper Nombres',
            'tper_apellidos' => 'Tper Apellidos',
            'tper_correo' => 'Tper Correo',
            'tper_direccion' => 'Tper Direccion',
            'tper_ciudad' => 'Tper Ciudad',
            'tper_provincia' => 'Tper Provincia',
            'tper_pais' => 'Tper Pais',
            'tper_telefono' => 'Tper Telefono',
            'tper_celular' => 'Tper Celular',
            'tper_unidad_academica' => 'Tper Unidad Academica',
            'tper_modalidad' => 'Tper Modalidad',
            'tper_carrera' => 'Tper Carrera',
            'tper_programa' => 'Tper Programa',
            'tper_curso' => 'Tper Curso',
            'tper_canal_contacto' => 'Tper Canal Contacto',
            'tper_estado' => 'Tper Estado',
            'tper_observacion' => 'Tper Observacion',
            'tper_observacion2' => 'Tper Observacion2',
            'tper_fecha_creacion' => 'Tper Fecha Creacion',
            'tper_fecha_modificacion' => 'Tper Fecha Modificacion',
            'tper_estado_logico' => 'Tper Estado Logico',
        ];
    }
    /**
     * Function Consultar los clientes contratantes.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarClienteTemp($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(tper_nombres like :search OR ";
            $str_search .= "tper_apellidos like :search) and";            
        }
        
        $sql = "
                    SELECT  
                        tper_id as id,
                        ifnull(tper_dni,'') as identificacion,
                        ifnull(tper_tipo_dni,'') as tipo_identificacion,
                        ifnull(tper_genero,'') as genero,
                        concat(tper_nombres,' ',tper_apellidos) as nombreapellido,
                        tper_correo as correo,
                        ifnull(tper_direccion,'') as direccion,
                        ifnull(tper_ciudad,'') as ciudad,
                        ifnull(tper_provincia,'') as provincia,
                        ifnull(tper_pais,'') as pais,
                        ifnull(tper_telefono,'') as telefono,
                        ifnull(tper_celular,'') as celular,
                        tper_estado as estado_persona,
                        tper_unidad_academica as unidadacademica,
                        tper_modalidad as modalidad,
                        tper_observacion as observacion,
                        tper_estado_actualizado as actualizado
                FROM " . $con->dbname . ".persona_temporal
                WHERE ".
                   "$str_search           
                       tper_estado_logico = :estado;
                    ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }
/**
     * Function Consultar los clientes contratantes.
     * @author Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarClienteTempById($id) {
        $con = \Yii::$app->db_crm;
        $estado = 1;
        $sql = "
                    SELECT  
                        tper_id as id,
                        tper_dni as identificacion,
                        tper_tipo_dni as tipo_identificacion,
                        tper_genero as genero,
                        tper_nombres as nombre,
                        tper_apellidos as apellido,
                        tper_correo as correo,
                        tper_direccion as direccion,
                        tper_ciudad as ciudad,
                        tper_provincia as provincia,
                        tper_pais as pais,
                        tper_telefono as telefono,
                        tper_celular as celular,
                        tper_estado as estado_persona,
                        tper_estado as estado_logico,
                        tper_unidad_academica as unidadacademica,
                        tper_modalidad as modalidad,
                        tper_observacion as observacion
                FROM ".$con->dbname . ".persona_temporal
                WHERE
                        tper_id = :id
                        and tper_estado_logico = :estado;
                    ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":id", $id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData[0];
    }
/**
     * Function insertarPersonaTemporal
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    public function actualizarPersonaTemporal($data) {
        $con = \Yii::$app->db_crm;
        $trans = $con->getTransaction(); 
        $estado=1;
        if ($trans !== null) {
            $trans = null; 
        } else {
            $trans = $con->beginTransaction();
        }
        if (!empty($data['cont_lname'])) {
                $filtro .= 'tper_apellidos = :tper_apellidos, ';
        }
        if (!empty($data['cont_phone'])) {
                $filtro .= 'tper_telefono = :tper_telefono, ';
        }
        if (!empty($data['cont_smart_phone'])) {
                $filtro .= 'tper_celular = :tper_celular, ';
        }
        if (!empty($data['cont_status'])) {
                $filtro .= 'tper_estado = :tper_estado, ';
        }
        if (!empty($data['cont_observation'])) {
                $filtro .= 'tper_observacion = :tper_observacion, ';
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".persona_temporal		       
                      SET tper_correo = :tper_correo,
                        $filtro
                        tper_estado_actualizado='1',
                        tper_nombres = :tper_nombres
                      WHERE 
                        tper_id = :pges_id AND                        
                        tper_estado_logico = :estado");
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":pges_id", $data['id_pertemp'], \PDO::PARAM_INT);
            $comando->bindParam(":tper_correo", $data['cont_email'], \PDO::PARAM_STR);
            $comando->bindParam(":tper_nombres", $data['cont_name'], \PDO::PARAM_STR);
            if (!empty($data['cont_lname'])) {
                $comando->bindParam(":tper_apellidos", $data['cont_lname'], \PDO::PARAM_STR);
            }
            if (!empty($data['cont_phone'])) {
                $comando->bindParam(":tper_telefono", $data['cont_phone'], \PDO::PARAM_STR);
            }
            if (!empty($data['cont_smart_phone'])) {
                $comando->bindParam(":tper_celular", $data['cont_smart_phone'], \PDO::PARAM_STR);
            }
            if (!empty($data['cont_status'])) {
                $comando->bindParam(":tper_estado", $data['cont_status'], \PDO::PARAM_STR);
            }
            if (!empty($data['cont_observation'])) {
                $comando->bindParam(":tper_observacion", $data['cont_observation'], \PDO::PARAM_STR);
            }
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return True;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }    
}
