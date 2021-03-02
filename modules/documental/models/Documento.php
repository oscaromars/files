<?php

namespace app\modules\documental\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\modules\documental\models\Clase;
use app\modules\documental\models\Serie;
use app\modules\documental\models\SubSerie;
use yii\base\Exception;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "documento".
 *
 * @property integer $doc_id
 * @property integer $cla_id
 * @property integer $ser_id
 * @property integer $sub_id
 * @property string $doc_uni_departamento
 * @property string $doc_macroproceso
 * @property string $doc_proceso
 * @property string $doc_secuencia
 * @property string $doc_cod_documento
 * @property string $doc_fecha_produccion
 * @property string $doc_pro_documental
 * @property string $doc_desc_informacion
 * @property string $doc_tipo_informacion
 * @property string $doc_ubicacion
 * @property string $doc_clasificacion
 * @property string $doc_info_publica
 * @property string $doc_estado_documento
 * @property string $doc_observaciones
 * @property string $doc_plaz_gestion
 * @property string $doc_plaz_central
 * @property string $doc_plaz_intermedio
 * @property string $doc_base_legal
 * @property string $doc_disp_eliminacion
 * @property string $doc_disp_conservacion
 * @property string $doc_tec_muestreo
 * @property string $doc_tec_conservacion
 * @property string $doc_cons_gestion
 * @property string $doc_cons_central
 * @property string $doc_cod_lomo
 * @property string $doc_usuario_ingreso
 * @property string $doc_usuario_modifica
 * @property string $doc_fecha_creacion
 * @property string $doc_fecha_modificacion
 * @property string $doc_estado_logico
 *
 */
class Documento extends \yii\db\ActiveRecord {

    /**
     * Arreglos de variables
     */
    public $array_tipo_informacion = array(
        "0" => "Solo Digital",
        "1" => "Solo Físico",
        "2" => "Digital y Físico",
    );
    public $array_clasificacion_informacion = array(
        "0" => "Confidencial",
        "1" => "Pública",
    );
    public $array_tipo_informacion_publica = array(
        "0" => "Interna",
        "1" => "Externa",
        "2" => "No aplica",
    );
    public $array_estado_documento = array(
        "0" => "Deteriorado",
        "1" => "Páginas Amarillas",
        "2" => "Rotos",
        "3" => "Apolillados",
        "4" => "Aceptable",
        "5" => "Buen estado",
    );

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'documento';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_documental');
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['doc_estado_logico'], 'required'],
            [['doc_fecha_creacion', 'doc_fecha_modificacion'], 'safe'],
            [['doc_uni_departamento', 'doc_estado_documento', 'doc_plaz_gestion', 'doc_plaz_central', 'doc_plaz_intermedio', 'doc_base_legal',
            'doc_disp_eliminacion', 'doc_disp_conservacion', 'doc_tec_muestreo', 'doc_tec_conservacion', 'doc_cons_gestion', 'doc_cons_central', 'doc_cod_lomo'], 'string', 'max' => 50],
            [['doc_ubicacion', 'doc_observaciones', 'doc_pro_documental', 'doc_desc_informacion'], 'string', 'max' => 200],
            [['doc_tipo_informacion', 'doc_clasificacion', 'doc_info_publica'], 'string', 'max' => 100],
            [['doc_macroproceso', 'doc_proceso'], 'string', 'max' => 150],
            [['doc_secuencia', 'doc_cod_documento', 'doc_fecha_produccion'], 'string', 'max' => 20],
            [['doc_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'doc_id' => 'Documento ID',
            'cla_id' => 'Clase ID',
            'ser_id' => 'Serie ID',
            'sub_id' => 'Subserie ID',
            'doc_uni_departamento' => 'Documento Departamento',
            'doc_macroproceso' => 'Documento Macroproceso',
            'doc_proceso' => 'Documento Proceso',
            'doc_secuencia' => 'Documento Secuencia',
            'doc_cod_documento' => 'Documento Codigo',
            'doc_fecha_produccion' => 'Documento Fecha Produccion',
            'doc_pro_documental' => 'Documento Pro Documental',
            'doc_desc_informacion' => 'Documento Descripcion Informacion',
            'doc_tipo_informacion' => 'Documento Tipo Informacion',
            'doc_ubicacion' => 'Documento Ubicacion',
            'doc_clasificacion' => 'Documento Claseficacion',
            'doc_info_publica' => 'Documento Informacion Publica',
            'doc_estado_documento' => 'Doocumento Estado',
            'doc_observaciones' => 'Documento Observaciones',
            'doc_plaz_gestion' => 'Documento Plaz Gestion',
            'doc_plaz_central' => 'Documento Plaz Central',
            'doc_plaz_intermedio' => 'Documento Plaz Intermedio',
            'doc_base_legal' => 'Documento Base Legal',
            'doc_disp_eliminacion' => 'Documento Disp Eliminacion',
            'doc_disp_conservacion' => 'Documento Disp Conservacion',
            'doc_tec_muestreo' => 'Documento Tec Muestreo',
            'doc_tec_conservacion' => 'Documento Tec Conservacion',
            'doc_cons_gestion' => 'Documento Cons Gestion',
            'doc_cons_central' => 'Documento Cons Central',
            'doc_cod_lomo' => 'Documento Cod Lomo',
            'doc_usuario_ingreso' => 'Documento Usuario Ingreso',
            'doc_usuario_modifica' => 'Documento Usuario Modifica',
            'doc_fecha_creacion' => 'Documento Fecha Creacion',
            'doc_fecha_modificacion' => 'Documento Fecha Modificacion',
            'doc_estado_logico' => 'Documento Estado Logico'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAllDocumentosGrid($search = NULL, $dataProvider = false) {
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
                    CONCAT(cla.cla_cod,'-',ser.ser_cod,'-',sub.sub_cod,'-',doc_secuencia) as Codigo 
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

    public function getDataToExcel($search) {
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%" . $search . "%";
        $str_search = "";
        if (isset($search)) {
            $str_search = "(do.doc_uni_departamento like :search OR ";
            $str_search .= "do.doc_proceso like :search) AND ";
        }
        $sql = "SELECT    /* tomar todos en orden segun el excel */
                    do.doc_uni_departamento as Departamento,
                    do.doc_macroproceso as Macroproceso,
                    do.doc_proceso as Proceso,
                    cla.cla_nombre as Clase,
                    ser.ser_cod as Serie,
                    sub.sub_cod as Subserie,
                    do.doc_secuencia as Secuencia,
                    CONCAT(cla.cla_cod,'-',ser.ser_cod,'-',sub.sub_cod,'-',doc_secuencia) as CodigoArchivo,
                    do.doc_cod_documento as Codigo,
                    do.doc_fecha_produccion as FechaProduccion,
                    do.doc_pro_documental as ProDocumental,
                    do.doc_desc_informacion as DescripcionInformacion,
                    do.doc_tipo_informacion as TipoInfo,
                    do.doc_ubicacion as Ubicacion,
                    do.doc_clasificacion as Clasificacion,
                    do.doc_info_publica as InformacionPublica,
                    do.doc_estado_documento as EstadoDocumento,
                    do.doc_observaciones as Observaciones,
                    do.doc_plaz_gestion as PlazGestion,
                    do.doc_plaz_central as PlazCentral,
                    do.doc_plaz_intermedio as PlazIntermedio,
                    do.doc_base_legal as BaseLegal,
                    do.doc_disp_eliminacion as DispEliminacion,
                    do.doc_disp_conservacion as DispConservacion,
                    do.doc_tec_muestreo as TecMuestreo,
                    do.doc_tec_conservacion as TecConservacion,
                    do.doc_cons_gestion as ConsGestion,
                    do.doc_cons_central as ConsCentral
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

                if (strcasecmp($key2, "EstadoDocumento") == 0) {
                    $res[$key][$key2] = $this->getValueEstadoDocumentoByKey($valor2);
                }

                if (strcasecmp($key2, "InformacionPublica") == 0) {
                    $res[$key][$key2] = $this->getValueTipoInformacionPublicaByKey($valor2);
                }

                if (strcasecmp($key2, "Clasificacion") == 0) {
                    $res[$key][$key2] = $this->getValueClasificacionInformacionByKey($valor2);
                }
            }
        }
        /* if($dataProvider){
          $dataProvider = new ArrayDataProvider([
          'key' => 'doc_id',
          'allModels' => $res,
          'pagination' => [
          'pageSize' => Yii::$app->params["pageSize"],
          ],
          'sort' => [
          'attributes' => ['Departamento', 'Proceso', 'Codigo', 'TipoInfo','Observaciones','Estado'],
          ],
          ]);
          return $dataProvider;
          } */
        return $res;
    }

    public function processFile($fname) {
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "documento/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_documental;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
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
                $array_clase = array();
                $array_serie = array();
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
                    for ($row = 3; $row <= $highestRow; ++$row) {
                        $row_global = $row_global + 1;
                        for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($cell->getValue());
                                $val = $date->format('m-d-Y');
                            } else {
                                $val = $cell->getValue();
                            }
                            $dataArr[$row_global][$col] = $val;
                        }
                    }
                    /* unset($dataArr[1], $dataArr[2]);                     */
                }

                /* print($row_global . "\n");
                  print(sizeof($dataArr) . "\n");
                  print_r($dataArr); */

                $fila = 1;
                $bandera = '1';
                $mensaje = "";

                $cont = 0;

                foreach ($dataArr as $val) {
                    /* print("#");
                      print(gettype($val[1]));
                      print("#"); */
                    if (!is_null($val[1]) || $val[1] != '') {

                        $fila++;

                        $cla_id;
                        $ser_id;
                        $sub_id;

                        $length_codigo = 3;
                        \app\models\Utilities::putMessageLogFile('clasexcel ' . $val[5]);
                        $cla_id = Clase::obtenerIdClasebyNombre($val[5]);
                        \app\models\Utilities::putMessageLogFile('claseid ' . $cla_id);
                        if (!is_null($cla_id)) {
                            // array_push($array_clase, $cla_id);
                            $ser_cod_str = "";
                            if (is_string($val[6])) {
                                $ser_cod_str = str_pad($val[6], $length_codigo, "0", STR_PAD_LEFT);
                            } else {
                                $ser_cod_str = str_pad(strval($val[6]), $length_codigo, "0", STR_PAD_LEFT);
                            }
                            /* print(strlen($val[5]));
                              print($ser_cod_str); */
                            /* print(gettype($ser_cod_str)); */
                            \app\models\Utilities::putMessageLogFile('ser_cod_str ' . $ser_cod_str);
                            $ser_id = Serie::obtenerIdSerieByCodCla($ser_cod_str, $cla_id);
                            if (!is_null($ser_id)) {
                                $sub_cod_str = "";
                                if (is_string($val[7])) {
                                    $sub_cod_str = str_pad($val[7], $length_codigo, "0", STR_PAD_LEFT);
                                } else {
                                    $sub_cod_str = str_pad(strval($val[7]), $length_codigo, "0", STR_PAD_LEFT);
                                }
                                /* print($sub_cod_str . "\n"); */
                                // array_push($array_serie, $ser_id);   
                                \app\models\Utilities::putMessageLogFile('ser_id ' . $ser_id);
                                $sub_id = SubSerie::obtenerIdSubSerieByNombreSer($sub_cod_str, $ser_id);
                                \app\models\Utilities::putMessageLogFile('sub_id ' . $sub_id);
                                if (is_null($sub_id)) {
                                    \app\models\Utilities::putMessageLogFile('subserie ' . $fila);
                                    $bandera = '0';
                                    $mensaje = "SubSerie no encontrada.";
                                }
                            } else {
                                \app\models\Utilities::putMessageLogFile('serie ' . $fila);
                                $bandera = '0';
                                $mensaje = "Serie no encontrada.";
                            }
                        } else {
                            \app\models\Utilities::putMessageLogFile('clase ' . $fila);
                            $bandera = '0';
                            $mensaje = "Clase no encontrada.";
                        }
                        if ($bandera == '0') {
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = "Error en la Fila => N°$fila Unidad Departamento => $val[1] . $mensaje";
                            $arroout["data"] = null;
                            return $arroout;
                        } else {
                            $save_documento = $this->saveDocumentoDB($cla_id, $ser_id, $sub_id, $val);
                            if (!$save_documento) {
                                $arroout["status"] = FALSE;
                                $arroout["error"] = null;
                                $arroout["message"] = "Error al guardar el registro de la Fila => N°$fila Unidad Departamento => $val[1]";
                                $arroout["data"] = null;
                                $arroout["validate"] = $val;
                                \app\models\Utilities::putMessageLogFile('error fila ' . $fila);
                                return $arroout;
                            }
                        }
                    }
                }
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

    public function getKeyTipoInformacionByValue($value) {
        $key_same = NULL;
        foreach ($this->array_tipo_informacion as $key => $valor) {
            if (strcasecmp($value, $valor) == 0) {
                $key_same = $key;
            }
        }

        return $key_same;
    }

    public function getValueTipoInformacionByKey($key_param) {
        $value_same = NULL;
        foreach ($this->array_tipo_informacion as $key => $valor) {
            if (strcasecmp($key, $key_param) == 0) {
                $value_same = $valor;
            }
        }
        return $value_same;
    }

    public function getKeyClasificacionInformacionByValue($value) {
        $key_same = NULL;
        foreach ($this->array_clasificacion_informacion as $key => $valor) {
            if (strcasecmp($value, $valor) == 0) {
                $key_same = $key;
            }
        }
        return $key_same;
    }

    public function getValueClasificacionInformacionByKey($key_param) {
        $value_same = NULL;
        foreach ($this->array_clasificacion_informacion as $key => $valor) {
            if (strcasecmp($key, $key_param) == 0) {
                $value_same = $valor;
            }
        }
        return $value_same;
    }

    public function getKeyTipoInformacionPublicaByValue($value) {
        $key_same = NULL;
        foreach ($this->array_tipo_informacion_publica as $key => $valor) {
            if (strcasecmp($value, $valor) == 0) {
                $key_same = $key;
            }
        }
        return $key_same;
    }

    public function getValueTipoInformacionPublicaByKey($key_param) {
        $value_same = NULL;
        foreach ($this->array_tipo_informacion_publica as $key => $valor) {
            if (strcasecmp($key, $key_param) == 0) {
                $value_same = $valor;
            }
        }
        return $value_same;
    }

    public function getKeyEstadoDocumentoByValue($value) {
        $key_same = NULL;
        foreach ($this->array_estado_documento as $key => $valor) {
            if (strcasecmp($value, $valor) == 0) {
                $key_same = $key;
            }
        }
        return $key_same;
    }

    public function getValueEstadoDocumentoByKey($key_param) {
        $value_same = NULL;
        foreach ($this->array_estado_documento as $key => $valor) {
            if (strcasecmp($key, $key_param) == 0) {
                $value_same = $valor;
            }
        }
        return $value_same;
    }

    public function saveDocumentoDB($cla_id, $ser_id, $sub_id, $val) {
        // try {
        /* print_r($val); */
        /* print("$$");
          print($val[10]);
          print(gettype($val[10]));
          print("$$"); */
        $model_documento = new Documento();
        $model_documento->cla_id = $cla_id;
        $model_documento->ser_id = $ser_id;
        $model_documento->sub_id = $sub_id;
        $model_documento->doc_uni_departamento = $val[1];
        if (is_null($val[2])) {
            $model_documento->doc_macroproceso = '';
        } else {
            $model_documento->doc_macroproceso = $val[2];
        }
        if (is_null($val[3])) {
            $model_documento->doc_proceso = '';
        } else {
            $model_documento->doc_proceso = $val[3];
        }
        if (is_null($val[8])) {
            $model_documento->doc_secuencia = '';
        } else {
            $doc_sec_str = "";
            if (is_string($val[8])) {
                $doc_sec_str = str_pad($val[8], 3, "0", STR_PAD_LEFT);
            } else {
                $doc_sec_str = str_pad(strval($val[8]), 3, "0", STR_PAD_LEFT);
            }
            $model_documento->doc_secuencia = $doc_sec_str;
        }
        if (is_null($val[10])) {
                $model_documento->doc_cod_documento = '';
        } else {
            $model_documento->doc_cod_documento = $val[10];
        }
        if (is_null($val[11])) {
            // timestamp
            $model_documento->doc_fecha_produccion = '';
        } else {
            $model_documento->doc_fecha_produccion = $val[11];
        }
        if (is_null($val[12])) {
            $model_documento->doc_pro_documental = '';
        } else {
            $model_documento->doc_pro_documental = $val[12];
        }
        if (is_null($val[13])) {
            $model_documento->doc_desc_informacion = '';
        } else {
            $model_documento->doc_desc_informacion = $val[13];
        }
        if (is_null($val[14])) {
            $model_documento->doc_tipo_informacion = '';
        } else {
            $tipo_informacion = $this->getKeyTipoInformacionByValue($val[14]);
            if (!is_null($tipo_informacion)) {
                $model_documento->doc_tipo_informacion = strval($tipo_informacion);
            } else {
                $model_documento->doc_tipo_informacion = '';
            }
        }
        if (is_null($val[15])) {
            $model_documento->doc_ubicacion = '';
        } else {
            $model_documento->doc_ubicacion = $val[15];
        }
        if (is_null($val[16])) {
            $model_documento->doc_clasificacion = '';
        } else {
            $clasificacion_informacion = $this->getKeyClasificacionInformacionByValue($val[16]);
            if (!is_null($clasificacion_informacion)) {
                $model_documento->doc_clasificacion = strval($clasificacion_informacion);
            } else {
                $model_documento->doc_clasificacion = '';
            }
        }
        if (is_null($val[17])) {
            $model_documento->doc_info_publica = '';
        } else {
            $tipo_informacion_publica = $this->getKeyTipoInformacionPublicaByValue($val[17]);
            if (!is_null($tipo_informacion_publica)) {
                $model_documento->doc_info_publica = strval($tipo_informacion_publica);
            } else {
                $model_documento->doc_info_publica = '';
            }
        }
        if (is_null($val[18])) {
            $model_documento->doc_estado_documento = '';
        } else {
            $estado_documento = $this->getKeyEstadoDocumentoByValue($val[18]);
            if (!is_null($estado_documento)) {
                $model_documento->doc_estado_documento = strval($estado_documento);
            } else {
                $model_documento->doc_estado_documento = '';
            }
        }
        if (is_null($val[19])) {
            $model_documento->doc_observaciones = '';
        } else {
            $model_documento->doc_observaciones = $val[19];
        }
        if (is_null($val[20])) {
            // douuubleee
            $model_documento->doc_plaz_gestion = '';
        } else {
            $model_documento->doc_plaz_gestion = strval($val[20]);
        }
        if (is_null($val[21])) {
            $model_documento->doc_plaz_central = '';
        } else {
            $model_documento->doc_plaz_central = strval($val[21]);
        }
        if (is_null($val[22])) {
            $model_documento->doc_plaz_intermedio = '';
        } else {
            $model_documento->doc_plaz_intermedio = strval($val[22]);
        }
        if (is_null($val[23])) {
            $model_documento->doc_base_legal = '';
        } else {
            $model_documento->doc_base_legal = $val[23];
        }
        if (is_null($val[24])) {
            $model_documento->doc_disp_eliminacion = '';
        } else {
            $model_documento->doc_disp_eliminacion = $val[24];
        }
        if (is_null($val[25])) {
            $model_documento->doc_disp_conservacion = '';
        } else {
            $model_documento->doc_disp_conservacion = $val[25];
        }
        /*if (is_null($val[26])) {
            $model_documento->doc_tec_muestreo = '';
        } else {
            $model_documento->doc_tec_muestreo = $val[26];
        }
        if (is_null($val[27])) {
            $model_documento->doc_tec_conservacion = '';
        } else {
            $model_documento->doc_tec_conservacion = $val[27];
        }
        if (is_null($val[28])) {
            $model_documento->doc_cons_gestion = '';
        } else {
            $model_documento->doc_cons_gestion = $val[28];
        }
        if (is_null($val[29])) {
            $model_documento->doc_cons_central = '';
        } else {
            $model_documento->doc_cons_central = $val[29];
        }
        if (is_null($val[30])) {
            $model_documento->doc_cod_lomo = '';
        } else {
            $model_documento->doc_cod_lomo = $val[30];
        }*/
        
      /*  \app\models\Utilities::putMessageLogFile('1 ' . $val[1]);
          \app\models\Utilities::putMessageLogFile('2 ' . $val[2]);
            \app\models\Utilities::putMessageLogFile('3 ' . $val[3]);
              \app\models\Utilities::putMessageLogFile('4 ' . $val[4]);
                \app\models\Utilities::putMessageLogFile('5 ' . $val[5]);
                  \app\models\Utilities::putMessageLogFile('6 ' . $val[6]);
                    \app\models\Utilities::putMessageLogFile('7 ' . $val[7]);
                      \app\models\Utilities::putMessageLogFile('8 ' . $val[8]);
                        \app\models\Utilities::putMessageLogFile('9 ' . $val[9]);
                          \app\models\Utilities::putMessageLogFile('10 ' . $val[10]);
        \app\models\Utilities::putMessageLogFile('11 ' . $val[11]);
          \app\models\Utilities::putMessageLogFile('12 ' . $val[12]);
            \app\models\Utilities::putMessageLogFile('13 ' . $val[13]);
              \app\models\Utilities::putMessageLogFile('14 ' . $val[14]);
                \app\models\Utilities::putMessageLogFile('15 ' . $val[15]);
                  \app\models\Utilities::putMessageLogFile('16 ' . $val[16]);
                    \app\models\Utilities::putMessageLogFile('17 ' . $val[17]);
                      \app\models\Utilities::putMessageLogFile('18 ' . $val[18]);
                        \app\models\Utilities::putMessageLogFile('19 ' . $val[19]);
                          \app\models\Utilities::putMessageLogFile('20 ' . $val[20]);
                          
        \app\models\Utilities::putMessageLogFile('21 ' . $val[21]);
          \app\models\Utilities::putMessageLogFile('22 ' . $val[22]);
            \app\models\Utilities::putMessageLogFile('23 ' . $val[23]);
              \app\models\Utilities::putMessageLogFile('24 ' . $val[24]);
                \app\models\Utilities::putMessageLogFile('25 ' . $val[25]);     */            

         $model_documento->doc_estado_logico = '1';         
                      
        return $model_documento->save();
        /* return $model_documento->save(); */

        /* return TRUE; */
        // } catch (\Exception $ex) {
        //     return FALSE;
        // }
    }

}
