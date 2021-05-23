<?php

namespace app\modules\academico\models;

use Yii;
use app\models\Utilities;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;
use app\models\Persona;

/**
 * This is the model class for table "planificacion_estudiante".
 *
 * @property int $pes_id
 * @property int $pla_id
 * @property int $per_id
 * @property string|null $pes_jornada
 * @property string|null $pes_cod_carrera
 * @property string|null $pes_carrera
 * @property string|null $pes_dni
 * @property string|null $pes_nombres
 * @property string|null $pes_egresado
 * @property string|null $pes_tutoria_nombre
 * @property string|null $pes_tutoria_cod
 * @property string|null $pes_cod_malla
 * @property string|null $pes_mat_b1_h1_nombre
 * @property string|null $pes_mat_b1_h1_cod
 * @property string|null $pes_mod_b1_h1
 * @property string|null $pes_jor_b1_h1
 * @property string|null $pes_mat_b1_h2_nombre
 * @property string|null $pes_mat_b1_h2_cod
 * @property string|null $pes_mod_b1_h2
 * @property string|null $pes_jor_b1_h2
 * @property string|null $pes_mat_b1_h3_nombre
 * @property string|null $pes_mat_b1_h3_cod
 * @property string|null $pes_mod_b1_h3
 * @property string|null $pes_jor_b1_h3
 * @property string|null $pes_mat_b1_h4_nombre
 * @property string|null $pes_mat_b1_h4_cod
 * @property string|null $pes_mod_b1_h4
 * @property string|null $pes_jor_b1_h4
 * @property string|null $pes_mat_b1_h5_nombre
 * @property string|null $pes_mat_b1_h5_cod
 * @property string|null $pes_mod_b1_h5
 * @property string|null $pes_jor_b1_h5
 * @property string|null $pes_mat_b1_h6_nombre
 * @property string|null $pes_mat_b1_h6_cod
 * @property string|null $pes_mod_b1_h6
 * @property string|null $pes_jor_b1_h6
 * @property string|null $pes_mat_b2_h1_nombre
 * @property string|null $pes_mat_b2_h1_cod
 * @property string|null $pes_mod_b2_h1
 * @property string|null $pes_jor_b2_h1
 * @property string|null $pes_mat_b2_h2_nombre
 * @property string|null $pes_mat_b2_h2_cod
 * @property string|null $pes_mod_b2_h2
 * @property string|null $pes_jor_b2_h2
 * @property string|null $pes_mat_b2_h3_nombre
 * @property string|null $pes_mat_b2_h3_cod
 * @property string|null $pes_mod_b2_h3
 * @property string|null $pes_jor_b2_h3
 * @property string|null $pes_mat_b2_h4_nombre
 * @property string|null $pes_mat_b2_h4_cod
 * @property string|null $pes_mod_b2_h4
 * @property string|null $pes_jor_b2_h4
 * @property string|null $pes_mat_b2_h5_nombre
 * @property string|null $pes_mat_b2_h5_cod
 * @property string|null $pes_mod_b2_h5
 * @property string|null $pes_jor_b2_h5
 * @property string|null $pes_mat_b2_h6_nombre
 * @property string|null $pes_mat_b2_h6_cod
 * @property string|null $pes_mod_b2_h6
 * @property string|null $pes_jor_b2_h6
 * @property string $pes_estado
 * @property string $pes_fecha_creacion
 * @property int|null $pes_usuario_modifica
 * @property string|null $pes_fecha_modificacion
 * @property string $pes_estado_logico
 *
 * @property Planificacion $pla
 */
class PlanificacionEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'planificacion_estudiante';
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
            [['pla_id', 'per_id', 'pes_estado', 'pes_estado_logico'], 'required'],
            [['pla_id', 'per_id', 'pes_usuario_modifica'], 'integer'],
            [['pes_fecha_creacion', 'pes_fecha_modificacion'], 'safe'],
            [['pes_jornada'], 'string', 'max' => 3],
            [['pes_cod_carrera', 'pes_tutoria_cod', 'pes_mat_b1_h1_cod', 'pes_jor_b1_h1', 'pes_mat_b1_h2_cod', 'pes_jor_b1_h2', 'pes_mat_b1_h3_cod', 'pes_jor_b1_h3', 'pes_mat_b1_h4_cod', 'pes_jor_b1_h4', 'pes_mat_b1_h5_cod', 'pes_jor_b1_h5', 'pes_mat_b1_h6_cod', 'pes_jor_b1_h6', 'pes_mat_b2_h1_cod', 'pes_mat_b2_h2_cod', 'pes_jor_b2_h2', 'pes_mat_b2_h3_cod', 'pes_jor_b2_h3', 'pes_mat_b2_h4_cod', 'pes_jor_b2_h4', 'pes_mat_b2_h5_cod', 'pes_jor_b2_h5', 'pes_mat_b2_h6_cod', 'pes_jor_b2_h6'], 'string', 'max' => 20],
            [['pes_carrera', 'pes_tutoria_nombre', 'pes_mat_b1_h1_nombre', 'pes_mat_b1_h2_nombre', 'pes_mat_b1_h3_nombre', 'pes_mat_b1_h4_nombre', 'pes_mat_b1_h5_nombre', 'pes_mat_b1_h6_nombre', 'pes_mat_b2_h1_nombre', 'pes_mat_b2_h2_nombre', 'pes_mat_b2_h3_nombre', 'pes_mat_b2_h4_nombre', 'pes_mat_b2_h5_nombre', 'pes_mat_b2_h6_nombre'], 'string', 'max' => 100],
            [['pes_dni'], 'string', 'max' => 15],
            [['pes_nombres'], 'string', 'max' => 200],
            [['pes_egresado', 'pes_estado', 'pes_estado_logico'], 'string', 'max' => 1],
            [['pes_cod_malla'], 'string', 'max' => 50],
            [['pes_mod_b1_h1', 'pes_mod_b1_h2', 'pes_mod_b1_h3', 'pes_mod_b1_h4', 'pes_mod_b1_h5', 'pes_mod_b1_h6', 'pes_mod_b2_h1', 'pes_mod_b2_h2', 'pes_mod_b2_h3', 'pes_mod_b2_h4', 'pes_mod_b2_h5', 'pes_mod_b2_h6'], 'string', 'max' => 2],
            [['pes_jor_b2_h1'], 'string', 'max' => 30],
            [['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pes_id' => 'Pes ID',
            'pla_id' => 'Pla ID',
            'per_id' => 'Per ID',
            'pes_jornada' => 'Pes Jornada',
            'pes_cod_carrera' => 'Pes Cod Carrera',
            'pes_carrera' => 'Pes Carrera',
            'pes_dni' => 'Pes Dni',
            'pes_nombres' => 'Pes Nombres',
            'pes_egresado' => 'Pes Egresado',
            'pes_tutoria_nombre' => 'Pes Tutoria Nombre',
            'pes_tutoria_cod' => 'Pes Tutoria Cod',
            'pes_cod_malla' => 'Pes Cod Malla',
            'pes_mat_b1_h1_nombre' => 'Pes Mat B1 H1 Nombre',
            'pes_mat_b1_h1_cod' => 'Pes Mat B1 H1 Cod',
            'pes_mod_b1_h1' => 'Pes Mod B1 H1',
            'pes_jor_b1_h1' => 'Pes Jor B1 H1',
            'pes_mat_b1_h2_nombre' => 'Pes Mat B1 H2 Nombre',
            'pes_mat_b1_h2_cod' => 'Pes Mat B1 H2 Cod',
            'pes_mod_b1_h2' => 'Pes Mod B1 H2',
            'pes_jor_b1_h2' => 'Pes Jor B1 H2',
            'pes_mat_b1_h3_nombre' => 'Pes Mat B1 H3 Nombre',
            'pes_mat_b1_h3_cod' => 'Pes Mat B1 H3 Cod',
            'pes_mod_b1_h3' => 'Pes Mod B1 H3',
            'pes_jor_b1_h3' => 'Pes Jor B1 H3',
            'pes_mat_b1_h4_nombre' => 'Pes Mat B1 H4 Nombre',
            'pes_mat_b1_h4_cod' => 'Pes Mat B1 H4 Cod',
            'pes_mod_b1_h4' => 'Pes Mod B1 H4',
            'pes_jor_b1_h4' => 'Pes Jor B1 H4',
            'pes_mat_b1_h5_nombre' => 'Pes Mat B1 H5 Nombre',
            'pes_mat_b1_h5_cod' => 'Pes Mat B1 H5 Cod',
            'pes_mod_b1_h5' => 'Pes Mod B1 H5',
            'pes_jor_b1_h5' => 'Pes Jor B1 H5',
            'pes_mat_b1_h6_nombre' => 'Pes Mat B1 H6 Nombre',
            'pes_mat_b1_h6_cod' => 'Pes Mat B1 H6 Cod',
            'pes_mod_b1_h6' => 'Pes Mod B1 H6',
            'pes_jor_b1_h6' => 'Pes Jor B1 H6',
            'pes_mat_b2_h1_nombre' => 'Pes Mat B2 H1 Nombre',
            'pes_mat_b2_h1_cod' => 'Pes Mat B2 H1 Cod',
            'pes_mod_b2_h1' => 'Pes Mod B2 H1',
            'pes_jor_b2_h1' => 'Pes Jor B2 H1',
            'pes_mat_b2_h2_nombre' => 'Pes Mat B2 H2 Nombre',
            'pes_mat_b2_h2_cod' => 'Pes Mat B2 H2 Cod',
            'pes_mod_b2_h2' => 'Pes Mod B2 H2',
            'pes_jor_b2_h2' => 'Pes Jor B2 H2',
            'pes_mat_b2_h3_nombre' => 'Pes Mat B2 H3 Nombre',
            'pes_mat_b2_h3_cod' => 'Pes Mat B2 H3 Cod',
            'pes_mod_b2_h3' => 'Pes Mod B2 H3',
            'pes_jor_b2_h3' => 'Pes Jor B2 H3',
            'pes_mat_b2_h4_nombre' => 'Pes Mat B2 H4 Nombre',
            'pes_mat_b2_h4_cod' => 'Pes Mat B2 H4 Cod',
            'pes_mod_b2_h4' => 'Pes Mod B2 H4',
            'pes_jor_b2_h4' => 'Pes Jor B2 H4',
            'pes_mat_b2_h5_nombre' => 'Pes Mat B2 H5 Nombre',
            'pes_mat_b2_h5_cod' => 'Pes Mat B2 H5 Cod',
            'pes_mod_b2_h5' => 'Pes Mod B2 H5',
            'pes_jor_b2_h5' => 'Pes Jor B2 H5',
            'pes_mat_b2_h6_nombre' => 'Pes Mat B2 H6 Nombre',
            'pes_mat_b2_h6_cod' => 'Pes Mat B2 H6 Cod',
            'pes_mod_b2_h6' => 'Pes Mod B2 H6',
            'pes_jor_b2_h6' => 'Pes Jor B2 H6',
            'pes_estado' => 'Pes Estado',
            'pes_fecha_creacion' => 'Pes Fecha Creacion',
            'pes_usuario_modifica' => 'Pes Usuario Modifica',
            'pes_fecha_modificacion' => 'Pes Fecha Modificacion',
            'pes_estado_logico' => 'Pes Estado Logico',
        ];
    }

    /**
     * Gets query for [[Pla]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPla()
    {
        return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroOnlines() {
        return $this->hasMany(RegistroOnline::className(), ['pes_id' => 'pes_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllPlanificacionesGrid($search = NULL, $dataProvider = false) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%" . $search . "%";
        $str_search = "";
        if (isset($search)) {
            $str_search = "(do.doc_uni_departamento like :search OR ";
            $str_search .= "do.doc_proceso like :search) AND ";
        }
        $sql = "SELECT 
                    do.doc_id as id,
                    do.doc_uni_departamento as Departamento,
                    do.doc_proceso as Proceso,                    
                    do.doc_tipo_informacion as TipoInfo,
                    do.doc_observaciones as Observaciones,
                    do.doc_estado_documento as Estado, 
                FROM 
                    documento as do
                    inner join clase as cla on cla.cla_id = do.cla_id
                    inner join serie as ser on ser.ser_id = do.ser_id
                    inner join subserie as sub on sub.sub_id = do.sub_id
                WHERE 
                    $str_search
                    do.doc_estado_logico=1 and
                    cla.cla_estado_logico=1 and
                    ser.ser_estado_logico=1 and
                    sub.sub_estado_logico=1
                    ORDER BY do.doc_id;";
        $comando = Yii::$app->db_documental->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        foreach ($res as $key => $valor) {
            foreach ($valor as $key2 => $valor2) {
                if (strcasecmp($key2, "TipoInfo") == 0) {
                    $res[$key][$key2] = $this->getValueTipoInformacionByKey($valor2);
                }

                if (strcasecmp($key2, "Estado") == 0) {
                    $res[$key][$key2] = $this->getValueEstadoDocumentoByKey($valor2);
                }
            }
        }

        if ($dataProvider) {
            $dataProvider = new ArrayDataProvider([
                'key' => 'doc_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Departamento', 'Proceso', 'Codigo', 'TipoInfo', 'Observaciones', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }

    public function processFile($fname, $pla_id) {
        \app\models\Utilities::putMessageLogFile('entraaaa1: ');
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "planificacion/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        /* print("pasa cargar"); */
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        /* print_r($chk_ext); */
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
                    /* print('######');
                      print($worksheetTitle);
                      print(";");
                      print($highestRow);
                      print(";");
                      print($highestColumn);
                      print(";");
                      print($highestColumnIndex);
                      print('######'); */
                    //lectura del Archivo XLS filas y Columnas
                    for ($row = 2; $row <= $highestRow; ++$row) {
                        $row_global = $row_global + 1;
                        for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $dataArr[$row_global][$col] = $cell->getValue();
                        }
                    }
                    /* unset($dataArr[1], $dataArr[2]);                     */
                }

                /*  return $dataArr; */
                /* print($row_global . "\n");
                  print(sizeof($dataArr) . "\n");
                  print_r($dataArr); */

                $fila = 0;
                foreach ($dataArr as $val) {
                    /* print("#");
                      print(gettype($val[1]));
                      print("#"); */
                    if (!is_null($val[4]) || $val[4]) {
                        $val[4] = strval($val[4]);
                        $per_id_estudiante = Persona::ObtenerPersonabyCedulaPasaporteRuc($val[4], $val[4], $val[4]);
                        $fila++;
                        /* print(strlen($val[5]));
                          print($ser_cod_str); */
                        /* print(gettype($ser_cod_str)); */
                        /* print("AntesGuardar"); */

                        $save_documento = $this->saveDocumentoDB($val, $pla_id, $per_id_estudiante);
                        if (!$save_documento) {
                            /* print("IFErrorSave"); */
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = "Error al guardar el registro de la Fila => N°$fila Cedula => $val[4]. No se encontró al estudiante.";
                            $arroout["data"] = null;
                            $arroout["validate"] = $val;
                            \app\models\Utilities::putMessageLogFile('error fila ' . $fila);
                            return $arroout;
                        }
                    }
                }
                /* print("AfterFOR"); */
                if ($trans !== null)
                    $trans->commit();
                // print_r($dataArr);
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                $arroout["validate"] = $fila;
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

    public function saveDocumentoDB($val, $pla_id, $per_id_estudiante) {
        \app\models\Utilities::putMessageLogFile('entraaaa2: ');
        $model_planificacion_estudiante = new PlanificacionEstudiante();
        $model_planificacion_estudiante->pla_id = $pla_id;

        $model_planificacion_estudiante->per_id = $per_id_estudiante;
        //$model_planificacion_estudiante->pes_jornada = $val[1];
        $model_planificacion_estudiante->pes_cod_malla = $val[1];
        /*  $model_planificacion_estudiante->pes_cod_carrera = $val[2]; */
        //bloque 1
        $model_planificacion_estudiante->pes_carrera = $val[3];
        $model_planificacion_estudiante->pes_dni = strval($val[4]);
        $model_planificacion_estudiante->pes_nombres = $val[5];
        \app\models\Utilities::putMessageLogFile('dni: ' .$val[4]);
        \app\models\Utilities::putMessageLogFile('val[6]: ' .$val[6]);
        if (!empty($val[6])) {
            $materia_id1 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[6]);
            $model_planificacion_estudiante->pes_mat_b1_h1_cod = $materia_id1['made_codigo_asignatura'];
            \app\models\Utilities::putMessageLogFile('val[1]: ' .$val[1]);
            \app\models\Utilities::putMessageLogFile('made_codigo_asignatura: ' .$materia_id1['made_codigo_asignatura']);
        }
        //$model_planificacion_estudiante->pes_mat_b1_h1_cod = $val[6];
        $model_planificacion_estudiante->pes_mod_b1_h1 = $val[7];    
        $model_planificacion_estudiante->pes_jor_b1_h1 = $val[8];   
        if (!empty($val[9])) {
         $materia_id2 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[9]);
         $model_planificacion_estudiante->pes_mat_b1_h2_cod = $materia_id2['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b1_h2_cod = $val[9];
        $model_planificacion_estudiante->pes_mod_b1_h2 = $val[10];    
        $model_planificacion_estudiante->pes_jor_b1_h2 = $val[11];
        if (!empty($val[12])) {
            $materia_id3 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[12]);
            $model_planificacion_estudiante->pes_mat_b1_h3_cod = $materia_id3['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b1_h3_cod = $val[12];
        $model_planificacion_estudiante->pes_mod_b1_h3 = $val[13];
        $model_planificacion_estudiante->pes_jor_b1_h3 = $val[14];
        if (!empty($val[15])) {
            $materia_id4 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[15]);
            $model_planificacion_estudiante->pes_mat_b1_h4_cod = $materia_id4['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b1_h4_cod = $val[15];        
        $model_planificacion_estudiante->pes_mod_b1_h4 = $val[16]; 
        $model_planificacion_estudiante->pes_jor_b1_h4 = $val[17];
        if (!empty($val[18])) {
            $materia_id5 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[18]);
            $model_planificacion_estudiante->pes_mat_b1_h5_cod = $materia_id5['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b1_h5_cod = $val[18];        
        $model_planificacion_estudiante->pes_mod_b1_h5 = $val[19];
        $model_planificacion_estudiante->pes_jor_b1_h5 = $val[20];
        if (!empty($val[21])) {
            $materia_id6 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[21]);
            $model_planificacion_estudiante->pes_mat_b1_h6_cod = $materia_id6['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b1_h6_cod = $val[21];
        $model_planificacion_estudiante->pes_mod_b1_h6 = $val[22];
        $model_planificacion_estudiante->pes_jor_b1_h6 = $val[23];
        //bloque 2
        if (!empty($val[24])) {
            $materia_id7 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[24]);
            $model_planificacion_estudiante->pes_mat_b2_h1_cod = $materia_id7['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h1_cod = $val[24];
        $model_planificacion_estudiante->pes_mod_b2_h1 = $val[25];
        $model_planificacion_estudiante->pes_jor_b2_h1 = $val[26];
        if (!empty($val[27])) {
            $materia_id8 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[27]);
            $model_planificacion_estudiante->pes_mat_b2_h2_cod = $materia_id8['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h2_cod = $val[27];
        $model_planificacion_estudiante->pes_mod_b2_h2 = $val[28];
        $model_planificacion_estudiante->pes_jor_b2_h2 = $val[29];
        if (!empty($val[30])) {
            $materia_id9 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[30]);
            $model_planificacion_estudiante->pes_mat_b2_h3_cod = $materia_id9['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h3_cod = $val[30];
        $model_planificacion_estudiante->pes_mod_b2_h3 = $val[31];
        $model_planificacion_estudiante->pes_jor_b2_h3 = $val[32];
        if (!empty($val[33])) {
            $materia_id10 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[33]);
            $model_planificacion_estudiante->pes_mat_b2_h4_cod = $materia_id10['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h4_cod = $val[33];
        $model_planificacion_estudiante->pes_mod_b2_h4 = $val[34];
        $model_planificacion_estudiante->pes_jor_b2_h4 = $val[35];
        if (!empty($val[36])) {
            $materia_id11 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[36]);
            $model_planificacion_estudiante->pes_mat_b2_h5_cod = $materia_id11['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h5_cod = $val[36];
        $model_planificacion_estudiante->pes_mod_b2_h5 = $val[37];
        $model_planificacion_estudiante->pes_jor_b2_h5 = $val[38];
        if (!empty($val[39])) {
            $materia_id12 = $model_planificacion_estudiante->consultarCodigoAsignatura($val[1], $val[39]);
            $model_planificacion_estudiante->pes_mat_b2_h6_cod = $materia_id12['made_codigo_asignatura'];
        }
        //$model_planificacion_estudiante->pes_mat_b2_h6_cod = $val[39];
        $model_planificacion_estudiante->pes_mod_b2_h6 = $val[40];
        $model_planificacion_estudiante->pes_jor_b2_h6 = $val[41];
        $model_planificacion_estudiante->pes_estado = "1";
        $model_planificacion_estudiante->pes_estado_logico = "1";
        /* if($val[4] == "0925029605") {
          var_dump($model_planificacion_estudiante);
          }
          print("AntesReturn"); */
        /* print($model_planificacion_estudiante); */

        return $model_planificacion_estudiante->save();
    }

    public static function getCarreras() {
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT @row_number:=@row_number+1 as pes_id, pes_carrera " .
                "FROM " . Yii::$app->db_academico->dbname . ".planificacion_estudiante, (SELECT @row_number:=0) AS t " .
                "WHERE pes_estado=1 AND pes_estado_logico=1 " .
                "GROUP BY pes_carrera";

        $comando = $con_academico->createCommand($sql);
        $resultData = $comando->queryAll();

        return $resultData;
    }

    /**
     * Function consulta los periodos academcicos en tablas de planificacion. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPeriodoplanifica() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $condition = "";

        $sql = "SELECT distinct pla_periodo_academico as id,
                pla_periodo_academico as name
                  FROM " . $con->dbname . ".planificacion
                  WHERE pla_estado = :estado AND
                  pla_estado_logico = :estado;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultarEstudianteplanifica
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>  
     * @property  
     * @return  
     */
    public function consultarEstudianteplanifica($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(pers.per_pri_nombre like :estudiante OR ";
            $str_search .= "pers.per_seg_nombre like :estudiante OR ";
            $str_search .= "pers.per_pri_apellido like :estudiante OR ";
            $str_search .= "pers.per_seg_nombre like :estudiante OR ";
            $str_search .= "pers.per_cedula like :estudiante)  AND ";

            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= " plan.mod_id = :modalidad AND ";
            }

            if ($arrFiltro['carrera'] != 'Todas') {
                $str_search .= " plae.pes_carrera like :carrera AND ";
            }

            if ($arrFiltro['periodo'] != '0') {
                $str_search .= " plan.pla_periodo_academico = :periodo AND ";
            }
        }
        if ($onlyData == false) {
            $idplanifica = 'plae.pla_id, ';
            $idper = 'plae.per_id, ';
        }
        $sql = "SELECT 
                    $idplanifica
                    $idper
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico
                FROM " . $con->dbname . ".planificacion_estudiante plae
                INNER JOIN " . $con->dbname . ".planificacion plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                WHERE 
                    $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado AND
                    plan.pla_estado = :estado AND
                    plan.pla_estado_logico = :estado AND
                    pers.per_estado = :estado AND
                    pers.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["estudiante"] . "%";
            $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            
            if ($arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }

            if ($arrFiltro['carrera'] != 'Todas') {
                $search_carrera = "%" . $arrFiltro["carrera"] . "%";
                $comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
            }

            if ($arrFiltro['periodo'] != '0') {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":periodo", $periodo, \PDO::PARAM_STR);
            }
        }

        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    /**
     * Function elimina planificacion de estudiante.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarPlanificacionest($pla_id, $per_id, $pes_usuario_modifica, $pes_estado, $pes_fecha_modificacion) {

        $con = \Yii::$app->db_academico;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".planificacion_estudiante		       
                      SET pes_estado = :pes_estado,
                          pes_usuario_modifica = :pes_usuario_modifica,
                          pes_fecha_modificacion = :pes_fecha_modificacion                          
                      WHERE 
                        pla_id = :pla_id AND per_id = :per_id");
            $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":pes_usuario_modifica", $pes_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pes_fecha_modificacion", $pes_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":pes_estado", $pes_estado, \PDO::PARAM_STR);
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

    /**
     * Function consultarDetalleplanifica
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información de los paralelos por período.)
     */
    public function consultarDetalleplanifica($pla_id, $per_id, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        // Bloque 1
        for ($i = 1; $i < 7; $i++) {
            $sql .= "SELECT pes_id as Ids, pes_jor_b1_h" . $i . " as jor_materia, pes_mat_b1_h" . $i . "_cod as cod_asignatura, asig.asi_nombre as asignatura, CASE pes_jornada  
                            WHEN 'M' THEN 'Matutino'  
                            WHEN 'N' THEN 'Nocturno'  
                            WHEN 'S' THEN 'Semipresencial'
                            WHEN 'D' THEN 'Distancia'
		    END AS pes_jornada, 'Bloque 1', moda.mod_nombre as modalidad, 'Hora " . $i . "' 
                    FROM " . $con->dbname . ".planificacion_estudiante ples
                    INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b1_h" . $i . "
                    INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b1_h" . $i . "_cod
                    INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                    where pla_id = " . $pla_id . " and per_id = " . $per_id . "
                    UNION ";
        }
        // Bloque 2
        for ($j = 1; $j < 7; $j++) {
            $sql .= "SELECT pes_id as Ids, pes_jor_b2_h" . $j . " as jor_materia, pes_mat_b2_h" . $j . "_cod as cod_asignatura, asig.asi_nombre as asignatura, CASE pes_jornada  
                            WHEN 'M' THEN 'Matutino'  
                            WHEN 'N' THEN 'Nocturno'  
                            WHEN 'S' THEN 'Semipresencial'
                            WHEN 'D' THEN 'Distancia'
		    END AS pes_jornada, 'Bloque 2', moda.mod_nombre as modalidad, 'Hora " . $j . "' 
                    FROM " . $con->dbname . ".planificacion_estudiante ples
                    INNER JOIN " . $con->dbname . ".modalidad moda ON  moda.mod_id = ples.pes_mod_b2_h" . $j . "
                    INNER JOIN " . $con->dbname . ".malla_academica_detalle mad ON  mad.made_codigo_asignatura = pes_mat_b2_h" . $j . "_cod
                    INNER JOIN " . $con->dbname . ".asignatura asig ON  asig.asi_id = mad.asi_id
                    where pla_id = " . $pla_id . " and per_id = " . $per_id . " ";
            if ($j < 6) {
                $sql .= "UNION ";
            }
        }

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryall();
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
     * Function Consultar datos de cabecera para palnificacion.
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCabeceraplanifica($pla_id, $per_id) {
        $con = \Yii::$app->db_academico;
        
        $sql = "SELECT plan.mod_id, 
                       plan.pla_periodo_academico,
                       plae.pes_carrera
                FROM " . $con->dbname . ".planificacion plan
                INNER JOIN " . $con->dbname . ".planificacion_estudiante plae ON plae.pla_id = plan.pla_id
                WHERE plan.pla_id = :pla_id and plae.per_id = :per_id";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function elimina materia de la planifiacion.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarPlanmatest($pla_id, $per_id, $bloque, $hora, $pes_usuario_modifica, $pes_estado, $pes_fecha_modificacion) {

        $con = \Yii::$app->db_academico;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        if (!empty($bloque) && !empty($hora)) {
            $modificar = "pes_mat_b" . $bloque . "_h" . $hora . "_cod = null,
                      pes_mod_b" . $bloque . "_h" . $hora . " = null,
                      pes_mat_b" . $bloque . "_h" . $hora . "_nombre = null,";
        }
        $este = "UPDATE db_academico.planificacion_estudiante		       
                      SET
                          $modificar
                          pes_usuario_modifica = $pes_usuario_modifica,
                          pes_fecha_modificacion = $pes_fecha_modificacion                          
                      WHERE 
                        pes_estado= 1 AND pla_id = $pla_id AND per_id = $per_id";
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".planificacion_estudiante		       
                      SET
                          $modificar
                          pes_usuario_modifica = :pes_usuario_modifica,
                          pes_fecha_modificacion = :pes_fecha_modificacion                          
                      WHERE 
                        pes_estado= :pes_estado AND pla_id = :pla_id AND per_id = :per_id");
            $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
            $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
            $comando->bindParam(":pes_usuario_modifica", $pes_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":pes_fecha_modificacion", $pes_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":pes_estado", $pes_estado, \PDO::PARAM_STR);
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

    /**
     * Function insertarDataPlanificacionestudiante 
     * Guiarse de insertarPersona
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    /* INSERTAR DATOS */
    public function insertarDataPlanificacionestudiante($pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores) {
        $arroout = array();
        $con = \Yii::$app->db_academico;
        $trans = $con->beginTransaction();
        try {            
            $data = isset($data['DATA']) ? $data['DATAS'] : array();
            $this->insertarPlanificacionestudiante($con, $pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores);
            $trans->commit();
            $con->close();
            //RETORNA DATOS             
            $arroout["status"] = true;
           
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();           
            $arroout["status"] = false;
            return $arroout;
        }
    }

    /** 
     * Function insertarPlanificacionestudiante 
     * Guiarse de insertarPersona
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    private function insertarPlanificacionestudiante($con, $pla_id, $per_id, $pes_jornada, $pes_carrera, $pes_dni, $pes_nombres, $pes_cod_malla, $insertar, $valores) {
        $estado = 1;
        $sql = "INSERT INTO " . $con->dbname . ".planificacion_estudiante
                    (pla_id,
                     per_id,
                     pes_jornada,
                     pes_carrera,
                     pes_dni,
                     pes_nombres, 
                     pes_cod_malla, " .
                     $insertar . "
                     pes_estado,                   
                     pes_estado_logico)VALUES
                    (" . $pla_id . "," . $per_id . ",'" . $pes_jornada . "','" . $pes_carrera . "','" . $pes_dni . "','"
                . $pes_nombres . "'," . $pes_cod_malla . ", " . $valores . " '" . $estado . "','" . $estado . "')";
        $command = $con->createCommand($sql);
        $command->execute();
    }

    /**
     * Function Consultar modalidad y periodo en planificacion.
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarDatoscabplanifica($mod_id, $pla_periodo_academico) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT pla_id
                FROM " . $con->dbname . ".planificacion plan
                WHERE plan.mod_id = :mod_id AND
                      plan.pla_periodo_academico = :pla_periodo_academico AND
                      plan.pla_estado = :estado AND
                      plan.pla_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":pla_periodo_academico", $pla_periodo_academico, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Consultar id de carrera.
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultardataplan($pla_id, $per_id, $materia) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT plan.pes_carrera, 
                       esta.eaca_id,
                       plan.pes_jornada,
                       plan.pes_nombres,
                       ifnull((SELECT maca.maca_nombre FROM " . $con->dbname . ".malla_academica_detalle made  
		                       INNER JOIN " . $con->dbname . ".malla_academica maca ON maca.maca_id = made.maca_id
                               WHERE made_codigo_asignatura = :materia), ' ') as malla
                FROM " . $con->dbname . ".planificacion_estudiante plan
                INNER JOIN " . $con->dbname . ".estudio_academico esta ON esta.eaca_nombre = plan.pes_carrera
                WHERE plan.pla_id = :pla_id AND
                      plan.per_id = :per_id AND
                      plan.pes_estado = :estado AND
                      plan.pes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $comando->bindParam(":materia", $materia, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }


    /**
     * Function busca los etudiantes para autocompletar en palnificacion. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function busquedaEstudianteplanificacion() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT est.per_id as id, concat(/*est.per_id, ' - ',*/ pers.per_cedula, ' - ', 
                    ifnull(pers.per_pri_nombre, ' ') ,' ', 
                    ifnull(pers.per_pri_apellido,' ')) as name
                    FROM db_academico.estudiante est
                    JOIN db_asgard.persona pers ON pers.per_id = est.per_id
                WHERE pers.per_estado = :estado AND
                      pers.per_estado_logico = :estado AND
                      est.est_estado = :estado AND
                      est.est_estado_logico = :estado;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function Consultar modalidad y periodo en planificacion.
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAlumnoplan($pla_id, $per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT count(*) as planexiste
                FROM " . $con->dbname . ".planificacion_estudiante 
                WHERE pla_id = :pla_id AND
                      per_id = :per_id AND
                      pes_estado = :estado AND
                      pes_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function modificarDataPlanificacionestudiante 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $userid
     * @return  
     */
    /* MODIFICAR DATOS */
    public function modificarDataPlanificacionestudiante($pla_id, $per_id, $pes_usuario_modifica, $modificar) {
        $arroout = array();
        $con = \Yii::$app->db_academico;
        $trans = $con->beginTransaction();
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        try {
            $data = isset($data['DATA']) ? $data['DATAS'] : array();
            $this->modificarPlanificacionestudiante($con,$pla_id, $per_id, $pes_usuario_modifica,$fecha, $modificar);
            $trans->commit();
            $con->close();
            //RETORNA DATOS 
            $arroout["status"] = true;
            
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            $arroout["status"] = false;
            return $arroout;
        }
    }

    /**
     * Function Modificar planificacion x estudiante.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function modificarPlanificacionestudiante($con,$pla_id, $per_id, $pes_usuario_modifica,$fecha, $modificar) {
        $pes_usuario_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        $estado = 1;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }        
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".planificacion_estudiante      
                      SET pes_usuario_modifica = " . $pes_usuario_modifica . ", " . 
                      $modificar . "
                      pes_fecha_modificacion = '" . $fecha . "'                                         
                      WHERE
                        pla_id = " . $pla_id . " AND                        
                        per_id = " . $per_id . " AND
                        pes_estado = :estado AND
                        pes_estado_logico = :estado");
            
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.planificacion_estudiante');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

      /**
     * Function Consultar codigo asigantura para archivo de planificacion estudiante.
     * @author  Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarCodigoAsignatura($maca_codigo, $asi_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT made_codigo_asignatura 
                FROM " . $con->dbname . ".malla_academica_detalle macad 
                    INNER JOIN " . $con->dbname . ".malla_academica maca 
                    ON maca.maca_id = macad.maca_id 
                    AND maca_codigo = maca_codigo
                WHERE macad.asi_id = asi_id AND
                      maca.maca_estado = :estado AND
                      maca.maca_estado_logico = :estado AND
                      macad.made_estado = :estado AND
                      macad.made_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":maca_codigo", $maca_codigo, \PDO::PARAM_STR);
        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }


     public function consultarEstudianteplanificapesold($pla_id, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
       // if (isset($arrFiltro) && count($arrFiltro) > 0) {
           // $str_search .= "(pers.per_pri_nombre like :estudiante OR ";
           // $str_search .= "pers.per_seg_nombre like :estudiante OR ";
           // $str_search .= "pers.per_pri_apellido like :estudiante OR ";
           // $str_search .= "pers.per_seg_nombre like :estudiante OR ";
           // $str_search .= "pers.per_cedula like :estudiante)  AND ";

           // if ($arrFiltro['modalidad'] > 0) {
            //    $str_search .= " plan.mod_id = :modalidad AND ";
            //}

           // if ($arrFiltro['carrera'] != 'Todas') {
            //    $str_search .= " plae.pes_carrera like :carrera AND ";
            //}

            if ($pla_id != '0') {
                $str_search .= " plae.pla_id = :pla_id  AND ";
            }
       // }
       // if ($onlyData == false) {
        //    $idplanifica = 'plae.pla_id, ';
        //    $idper = 'plae.per_id, ';
        //}
        $sql = "SELECT 
                    pers.per_cedula,
                    plae.pes_nombres,
                    plae.pes_carrera,
                    plan.pla_periodo_academico,
                    plae.pes_mat_b1_h1_nombre,
                    plae.pes_mat_b1_h2_nombre,
                    plae.pes_mat_b1_h3_nombre,
                    plae.pes_mat_b1_h4_nombre,
                    plae.pes_mat_b1_h5_nombre,
                    plae.pes_mat_b1_h6_nombre
                FROM " . $con->dbname . ".planificacion_estudiantex plae
                LEFT JOIN " . $con->dbname . ".planificacionx plan ON plan.pla_id = plae.pla_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = plae.per_id
                WHERE 
                    -- $str_search
                    plae.pes_estado = :estado AND
                    plae.pes_estado_logico = :estado
                    -- AND
                    -- plan.pla_estado = :estado AND
                    -- plan.pla_estado_logico = :estado 
                    -- AND
                    -- pers.per_estado = :estado AND
                    -- pers.per_estado_logico = :estado
                    ";
                    
         



        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        // if (isset($arrFiltro) && count($arrFiltro) > 0) {
          //  $search_cond = "%" . $arrFiltro["estudiante"] . "%";
           // $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            
           // if ($arrFiltro['modalidad'] > 0) {
           //     $modalidad = $arrFiltro["modalidad"];
          //      $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
          //  }

           // if ($arrFiltro['carrera'] != 'Todas') {
           //     $search_carrera = "%" . $arrFiltro["carrera"] . "%";
           //     $comando->bindParam(":carrera", $search_carrera, \PDO::PARAM_STR);
           // }

            if ($pla_id != '0') {
              //  $periodo = $arrFiltro["pla_id "];
                $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);
            }
        // }

        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }
}

