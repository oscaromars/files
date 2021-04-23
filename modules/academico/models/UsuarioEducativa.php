<?php

namespace app\modules\academico\models;
use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;
use app\modules\academico\models\Estudiante;

/**
 * This is the model class for table "usuario_educativa".
 *
 * @property int $uedu_id
 * @property int $per_id
 * @property int $est_id
 * @property string $uedu_usuario
 * @property string $uedu_nombres
 * @property string $uedu_apellidos
 * @property string $uedu_cedula
 * @property string $uedu_matricula
 * @property string $uedu_correo
 * @property int $uedu_usuario_ingreso
 * @property int $uedu_usuario_modifica
 * @property string $uedu_estado
 * @property string $uedu_fecha_creacion
 * @property string $uedu_fecha_modificacion
 * @property string $uedu_estado_logico
 */
class UsuarioEducativa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario_educativa';
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
            [['per_id', 'est_id', 'uedu_usuario_ingreso', 'uedu_usuario_modifica'], 'integer'],
            [['uedu_usuario', 'uedu_usuario_ingreso', 'uedu_estado', 'uedu_estado_logico'], 'required'],
            [['uedu_fecha_creacion', 'uedu_fecha_modificacion'], 'safe'],
            [['uedu_usuario', 'uedu_nombres', 'uedu_apellidos'], 'string', 'max' => 100],
            [['uedu_cedula'], 'string', 'max' => 15],
            [['uedu_matricula'], 'string', 'max' => 20],
            [['uedu_correo'], 'string', 'max' => 250],
            [['uedu_estado', 'uedu_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'uedu_id' => 'Uedu ID',
            'per_id' => 'Per ID',
            'est_id' => 'Est ID',
            'uedu_usuario' => 'Uedu Usuario',
            'uedu_nombres' => 'Uedu Nombres',
            'uedu_apellidos' => 'Uedu Apellidos',
            'uedu_cedula' => 'Uedu Cedula',
            'uedu_matricula' => 'Uedu Matricula',
            'uedu_correo' => 'Uedu Correo',
            'uedu_usuario_ingreso' => 'Uedu Usuario Ingreso',
            'uedu_usuario_modifica' => 'Uedu Usuario Modifica',
            'uedu_estado' => 'Uedu Estado',
            'uedu_fecha_creacion' => 'Uedu Fecha Creacion',
            'uedu_fecha_modificacion' => 'Uedu Fecha Modificacion',
            'uedu_estado_logico' => 'Uedu Estado Logico',
        ];
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
    }

    /**
     * Function carga archivo excel a base de datos de cartera
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivoeducativa($fname) {
        //\app\models\Utilities::putMessageLogFile('Files modelo ...: ' . $fname);
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "educativa/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $mod_estudiante = new Estudiante();
        $mod_educativa = new UsuarioEducativa();
        /* print("pasa cargar"); */
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }   
        if (strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx") {
            //Creacion de PHPExcel object
            try {
                $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $dataArr = array();
                $validacion = false;
                $row_global = 0;

                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10 
                    $highestColumn = $worksheet->getHighestDataColumn(); // e.g 'F'                    
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $row_global = $row_global + 1;
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $dataArr[$row_global][$col] = $cell->getValue();
                        }
                    }                   
                }
                $fila = 0;
                foreach ($dataArr as $val) {
                    //\app\models\Utilities::putMessageLogFile('cedula ...: ' .$val[2]);
                    if (!is_null($val[1]) || $val[1]) {
                        $val[1] = strval($val[1]);
                        $val[2] = strval($val[2]);
                        $val[3] = strval($val[3]);
                        $val[4] = strval($val[4]);
                        $val[5] = strval($val[5]);
                        $val[6] = strval($val[6]);
                        if (!empty($val[5])) {
                        $est_id = $mod_estudiante->consultarEstidxdni($val[5]);
                        }else{
                            $est_id['est_id'] = null;
                            $est_id['per_id'] = null;
                        }
                        //\app\models\Utilities::putMessageLogFile('est_id consulta ...: ' .$est_id['est_id']);
                        //\app\models\Utilities::putMessageLogFile('per_id consulta ...: ' . $est_id['per_id']);
                        $fila++;         
                        //if (!empty($est_id['est_id'])) {
                        $existe = $mod_educativa->consultarexisteusuario($val[1], $val[4], $val[5], $val[6]);
                            if ($existe['existe_usuario'] == 0) {
                        $save_documento = $this->saveDocumentoDB($val, $est_id['est_id'], $est_id['per_id']);
                        if (!$save_documento) {                   
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = "Error al guardar el registro de la Fila => N°$fila usuario => $val[1].";
                            $arroout["data"] = null;
                            $arroout["validate"] = $val;
                            \app\models\Utilities::putMessageLogFile('error fila ' . $fila);
                            return $arroout;
                        }
                      }else{
                        $ingresadoant .= $val[1] . ", ";
                    }
                     /*}
                    else{
                        $noestudiantes .= $val[1] . ", ";
                    }*/
                  }
                }
                //\app\models\Utilities::putMessageLogFile('anterio ...: ' . $ingresadoant);
                //\app\models\Utilities::putMessageLogFile('no estudiante ...: ' . $noestudiantes);
                if ($trans !== null)
                    $trans->commit();
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                $arroout["validate"] = $fila;
                $arroout["repetido"] = substr($ingresadoant, 0, -2);
                $arroout["noalumno"] = substr($noestudiantes, 0, -2);
                //\app\models\Utilities::putMessageLogFile('no estudiante array...: ' . $arroout["noalumno"]);
                return $arroout;
            } catch (\Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                $arroout["status"] = FALSE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                return $arroout;
            }
        }
    }
    /**
     * Function Consultar si ya se ha cargado la informacion anteriormente en usuarios educativa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarexisteusuario($uedu_usuario, $uedu_correo, $uedu_cedula, $uedu_matricula) {
        $con = \Yii::$app->db_academico;     
        $estado = 1;
        // OJO VALIDAR LA MATRICULA NO SE TOME EN CUENTA SI VIENE VACIO
        if (!empty($uedu_correo)) {
            $campos .= " uedu_correo = :uedu_correo OR ";            
        }
        
        if (!empty($uedu_cedula)) {
            $campos .= " uedu_cedula = :uedu_cedula OR ";            
        }

        if (!empty($uedu_matricula)) {
            $campos .= " uedu_matricula = :uedu_matricula OR ";            
        }

        $sql = "SELECT 	
                        count(*) as existe_usuario                       
                        
                FROM " . $con->dbname . ".usuario_educativa                 
                WHERE 
                $campos
                uedu_usuario = :uedu_usuario AND
                uedu_estado = :estado AND
                uedu_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uedu_usuario", $uedu_usuario, \PDO::PARAM_STR);
        if (!empty($uedu_correo)) {
            $comando->bindParam(":uedu_correo", $uedu_correo, \PDO::PARAM_STR);
        }
        if (!empty($uedu_cedula)) {
                $comando->bindParam(":uedu_cedula", $uedu_cedula, \PDO::PARAM_STR);
        }    
        if (!empty($uedu_matricula)) {
        $comando->bindParam(":uedu_matricula", $uedu_matricula, \PDO::PARAM_STR);
        }     
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function salva data archivo excel a base de datos de usuarios educativa
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */

    public function saveDocumentoDB($val, $idestudiante, $idpersona) {
       // \app\models\Utilities::putMessageLogFile('entro guardar ...: ');
       // \app\models\Utilities::putMessageLogFile('est_id guarda ...: ' .$est_id['est_id']);
        //\app\models\Utilities::putMessageLogFile('per_id guarda ...: ' . $est_id['per_id']);
                       
        $usu_id = Yii::$app->session->get('PB_iduser');
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $mod_educativauser = new UsuarioEducativa();        
        if (!empty($idpersona)) {
        $mod_educativauser->per_id = $idpersona;
        }
        if (!empty($idestudiante)) {
        $mod_educativauser->est_id = $idestudiante;
        }
        $mod_educativauser->uedu_usuario = $val[1]; 
        $mod_educativauser->uedu_nombres = $val[3]; 
        $mod_educativauser->uedu_apellidos = $val[2]; 
        $mod_educativauser->uedu_cedula = strval($val[5]);
        $mod_educativauser->uedu_matricula = $val[6];
        $mod_educativauser->uedu_correo = $val[4];     
        $mod_educativauser->uedu_usuario_ingreso = $usu_id;
        $mod_educativauser->uedu_estado = "1";
        $mod_educativauser->uedu_fecha_creacion = $fecha_transaccion;
        $mod_educativauser->uedu_estado_logico = "1";
       /* \app\models\Utilities::putMessageLogFile('est_id: ' .$idestudiante);
        \app\models\Utilities::putMessageLogFile('1: ' .$val[1]);
        \app\models\Utilities::putMessageLogFile('2: ' .$val[2]);
        \app\models\Utilities::putMessageLogFile('3: ' .$val[3]);
        \app\models\Utilities::putMessageLogFile('4: ' .$val[4]);    
        \app\models\Utilities::putMessageLogFile('5: ' .$val[5]);    
        \app\models\Utilities::putMessageLogFile('6: ' .$val[6]);       
        \app\models\Utilities::putMessageLogFile('fecha: ' .$fecha_transaccion);
        \app\models\Utilities::putMessageLogFile('usu_id: ' .$usu_id);*/
        return $mod_educativauser->save();
    }
}