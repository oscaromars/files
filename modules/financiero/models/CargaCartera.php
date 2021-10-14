<?php

namespace app\modules\financiero\models;
use yii\data\ArrayDataProvider;
use Yii;
use app\models\UsuaGrolEper;
use app\models\Utilities;
use app\modules\academico\models\Estudiante;

/**
 * This is the model class for table "carga_cartera".
 *
 * @property int $ccar_id
 * @property string $ccar_punto
 * @property string $ccar_caja
 * @property int $est_id
 * @property string $ccar_tipo_documento
 * @property string $ccar_numero_documento
 * @property string $ccar_documento_identidad
 * @property string $ccar_forma_pago
 * @property string $ccar_num_cuota
 * @property string $ccar_fecha_factura
 * @property string $ccar_fecha_vencepago
 * @property int $ccar_dias_plazo
 * @property double $ccar_valor_cuota
 * @property double $ccar_valor_factura
 * @property string $ccar_fecha_pago
 * @property double $ccar_retencion_fuente
 * @property double $ccar_retencion_iva
 * @property string $ccar_numero_retencion
 * @property string $ccar_valor_iva
 * @property string $ccar_estado_cancela
 * @property int $ccar_codigo_cobrador
 * @property string $ccar_fecha_aprueba_rechaza
 * @property int $ccar_usu_aprueba_rechaza
 * @property int $ccar_usu_ingreso
 * @property int $ccar_usu_modifica
 * @property string $ccar_estado
 * @property double $ccar_abono
 * @property string $ccar_fecha_creacion
 * @property string $ccar_fecha_modificacion
 * @property string $ccar_estado_logico
 */
class CargaCartera extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carga_cartera';
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
    /*
    public function rules()
    {
        return [
            [['ccar_id', 'est_id', 'ccar_tipo_documento', 'ccar_numero_documento', 'ccar_forma_pago', 'ccar_num_cuota', 'ccar_estado_cancela', 'ccar_usu_ingreso', 'ccar_estado', 'ccar_estado_logico'], 'required'],
            [['ccar_id', 'est_id', 'ccar_dias_plazo', 'ccar_codigo_cobrador', 'ccar_usu_aprueba_rechaza', 'ccar_usu_ingreso', 'ccar_usu_modifica'], 'integer'],
            [['ccar_fecha_factura', 'ccar_fecha_vencepago', 'ccar_fecha_pago', 'ccar_fecha_aprueba_rechaza', 'ccar_fecha_creacion', 'ccar_fecha_modificacion'], 'safe'],
            [['ccar_valor_cuota', 'ccar_valor_factura', 'ccar_retencion_fuente', 'ccar_retencion_iva', 'ccar_abono'], 'number'],
            [['ccar_punto', 'ccar_caja'], 'string', 'max' => 5],
            [['ccar_tipo_documento', 'ccar_forma_pago', 'ccar_valor_iva', 'ccar_estado_cancela'], 'string', 'max' => 3],
            [['ccar_numero_documento'], 'string', 'max' => 30],
            [['ccar_documento_identidad', 'ccar_num_cuota'], 'string', 'max' => 10],
            [['ccar_numero_retencion'], 'string', 'max' => 100],
            [['ccar_estado', 'ccar_estado_logico'], 'string', 'max' => 1],
            [['ccar_id'], 'unique'],
        ];
    }
    */
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ccar_id' => 'Ccar ID',
            'ccar_punto' => 'Ccar Punto',
            'ccar_caja' => 'Ccar Caja',
            'est_id' => 'Est ID',
            'ccar_tipo_documento' => 'Ccar Tipo Documento',
            'ccar_numero_documento' => 'Ccar Numero Documento',
            'ccar_documento_identidad' => 'Ccar Documento Identidad',
            'ccar_forma_pago' => 'Ccar Forma Pago',
            'ccar_num_cuota' => 'Ccar Num Cuota',
            'ccar_fecha_factura' => 'Ccar Fecha Factura',
            'ccar_fecha_vencepago' => 'Ccar Fecha Vencepago',
            'ccar_dias_plazo' => 'Ccar Dias Plazo',
            'ccar_valor_cuota' => 'Ccar Valor Cuota',
            'ccar_valor_factura' => 'Ccar Valor Factura',
            'ccar_fecha_pago' => 'Ccar Fecha Pago',
            'ccar_retencion_fuente' => 'Ccar Retencion Fuente',
            'ccar_retencion_iva' => 'Ccar Retencion Iva',
            'ccar_numero_retencion' => 'Ccar Numero Retencion',
            'ccar_valor_iva' => 'Ccar Valor Iva',
            'ccar_estado_cancela' => 'Ccar Estado Cancela',
            'ccar_codigo_cobrador' => 'Ccar Codigo Cobrador',
            'ccar_fecha_aprueba_rechaza' => 'Ccar Fecha Aprueba Rechaza',
            'ccar_usu_aprueba_rechaza' => 'Ccar Usu Aprueba Rechaza',
            'ccar_usu_ingreso' => 'Ccar Usu Ingreso',
            'ccar_usu_modifica' => 'Ccar Usu Modifica',
            'ccar_estado' => 'Ccar Estado',
            'ccar_abono' => 'Ccar Abono',
            'ccar_fecha_creacion' => 'Ccar Fecha Creacion',
            'ccar_fecha_modificacion' => 'Ccar Fecha Modificacion',
            'ccar_estado_logico' => 'Ccar Estado Logico',
        ];
    }

    /**
     * Function carga archivo excel a base de datos de cartera
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function CargarArchivocartera($fname) {
        \app\models\Utilities::putMessageLogFile('Files ...: ' . $fname);
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "cartera/" . $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        $mod_estudiante = new Estudiante();
        $mod_cartera = new CargaCartera();
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

                    if (!is_null($val[2]) || $val[2]) {
                        $val[2] = strval($val[2]);
                        $est_id = $mod_estudiante->consultarEstidxdni($val[2]);
                        $fila++;
                        if (!empty($est_id['est_id'])) {
                        $existe = $mod_cartera->consultarexistecartera($val[2], $val[4], $val[6]);
                            if ($existe['existe_cartera'] == 0) {
                        $save_documento = $this->saveDocumentoDB($val, $est_id['est_id']);
                        if (!$save_documento) {
                            $arroout["status"] = FALSE;
                            $arroout["error"] = null;
                            $arroout["message"] = "Error al guardar el registro de la Fila => N°$fila Cedula => $val[2].";
                            $arroout["data"] = null;
                            $arroout["validate"] = $val;
                            \app\models\Utilities::putMessageLogFile('error fila ' . $fila);
                            return $arroout;
                        }
                      }else{
                        $ingresadoant .= $val[2] . ", ";
                    }
                    }
                    else{
                        $noestudiantes .= $val[2] . ", ";
                    }
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
     * Function salva data archivo excel a base de datos de cartera
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */

    public function saveDocumentoDB($val, $idestudiante) {
        $usu_id = Yii::$app->session->get('PB_iduser');
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $model_cargarch = new CargaCartera();
        $model_cargarch->est_id = $idestudiante;
        $model_cargarch->ccar_tipo_documento = $val[3];
        $model_cargarch->ccar_numero_documento = $val[4];

        $model_cargarch->ccar_documento_identidad = strval($val[2]);
        $model_cargarch->ccar_forma_pago = $val[5];

        $model_cargarch->ccar_num_cuota = $val[6];
        $model_cargarch->ccar_fecha_factura = $val[7];
        $model_cargarch->ccar_fecha_vencepago = $val[8];
        $model_cargarch->ccar_valor_cuota = $val[9];
        $model_cargarch->ccar_valor_factura = $val[10];
        $model_cargarch->ccar_estado_cancela = 'N';
        $model_cargarch->ccar_usu_ingreso = $usu_id;
        $model_cargarch->ccar_estado = "1";
        $model_cargarch->ccar_fecha_creacion = $fecha_transaccion;
        $model_cargarch->ccar_estado_logico = "1";
        \app\models\Utilities::putMessageLogFile('est_id: ' .$idestudiante);
        \app\models\Utilities::putMessageLogFile('1: ' .$val[1]);
        \app\models\Utilities::putMessageLogFile('2: ' .$val[2]);
        \app\models\Utilities::putMessageLogFile('3: ' .$val[3]);
        \app\models\Utilities::putMessageLogFile('4: ' .$val[4]);
        \app\models\Utilities::putMessageLogFile('5: ' .$val[5]);
        \app\models\Utilities::putMessageLogFile('6: ' .$val[6]);
        \app\models\Utilities::putMessageLogFile('7: ' .$val[7]);
        \app\models\Utilities::putMessageLogFile('8: ' .$val[8]);
        \app\models\Utilities::putMessageLogFile('9: ' .$val[9]);
        \app\models\Utilities::putMessageLogFile('10: ' .$val[10]);
        \app\models\Utilities::putMessageLogFile('fecha: ' .$fecha_transaccion);
        \app\models\Utilities::putMessageLogFile('usu_id: ' .$usu_id);
        return $model_cargarch->save();
    }

    /**
     * Function Consultar si ya se ha cargado la informacion anteriormente en cartera.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property
     * @return
     */
    public function consultarexistecartera($ccar_documento_identidad, $ccar_numero_documento, $ccar_num_cuota) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT
                        count(*) as existe_cartera

                FROM " . $con->dbname . ".carga_cartera
                WHERE
                ccar_documento_identidad = :ccar_documento_identidad AND
                ccar_numero_documento = :ccar_numero_documento AND
                ccar_num_cuota = :ccar_num_cuota AND
                ccar_estado = :estado AND
                ccar_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ccar_documento_identidad", $ccar_documento_identidad, \PDO::PARAM_STR);
        $comando->bindParam(":ccar_numero_documento", $ccar_numero_documento, \PDO::PARAM_STR);
        $comando->bindParam(":ccar_num_cuota", $ccar_num_cuota, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function getPagospendientexestcar
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public static function getPagospendientexestcar($cedula, $onlyData = false) {
        $con = \Yii::$app->db_facturacion;
        $usuario = @Yii::$app->user->identity->usu_id;   //Se obtiene el id del usuario.
        $estado = 1;
        $sql = "SELECT
                  (SELECT SUM(ccar.ccar_valor_cuota) FROM " . $con->dbname . ".carga_cartera ccar
                    WHERE ccar.ccar_documento_identidad= :cedula AND ccar.ccar_estado_cancela='N' AND ccar.ccar_tipo_documento='FE')  as total_deuda,
                    ccar.ccar_id,
                    ccar.ccar_tipo_documento,
                    ccar.ccar_numero_documento as NUM_NOF,
                    ccar.ccar_documento_identidad,
                    ccar.ccar_forma_pago,
                    ccar.ccar_num_cuota as NUM_DOC,
                    -- SUBSTR(ccar.ccar_num_cuota,-3) as cantidad,
                    CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                    ELSE SUBSTRING(ccar.ccar_num_cuota,-3)
                  END  as cantidad,
                    DATE_FORMAT(ccar.ccar_fecha_factura,'%Y-%m-%d') as F_SUS_D,
                    DATE_FORMAT(ccar.ccar_fecha_vencepago,'%Y-%m-%d') as F_VEN_D,
                    ccar.ccar_valor_cuota,
                 -- (ccar.VALOR_D-ccar.VALOR_C-ccar.VAL_DEV) SALDO,
                  CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                    ELSE ccar.ccar_num_cuota
                  END  as cuota
                  ,ifnull(ccar_abono, '') as abono
                  ,ifnull(ROUND((ccar.ccar_valor_cuota - ccar_abono),2), '') as saldo
                FROM " . $con->dbname . ".carga_cartera ccar
                WHERE ccar.ccar_documento_identidad= :cedula AND
                      ccar.ccar_estado_cancela='N' AND
                      ccar.ccar_tipo_documento='FE' AND
                      ccar.ccar_estado = :estado AND
                      ccar.ccar_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();
        $mod_grupo = new UsuaGrolEper();
        $nombregrupo = $mod_grupo->consultarGruponame($usuario);

        foreach ($resultData as $key => $value) {
            $value['grupo'] = $nombregrupo['gru_nombre'];
            $resultData[$key] =  $value;
        }

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
     * Function Consulta las cuotas seleccionada al cargar imagenes de facturas pendientes de pago.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarPagospendientesp($cedula, $factura, $cuota) {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT
                  (SELECT SUM(ccar.ccar_valor_cuota) FROM " . $con->dbname . ".carga_cartera ccar
                    WHERE ccar.ccar_documento_identidad= :cedula AND ccar.ccar_estado_cancela='N' AND ccar.ccar_tipo_documento='FE')  as total_deuda,
                    ccar.ccar_tipo_documento,
                    ccar.ccar_numero_documento as NUM_NOF,
                    ccar.ccar_documento_identidad,
                    ccar.ccar_forma_pago,
                    ccar.ccar_num_cuota as NUM_DOC,
                    -- SUBSTR(ccar.ccar_num_cuota,-3) as cantidad,
                    CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                    ELSE SUBSTRING(ccar.ccar_num_cuota,-3)
                    END  as cantidad,
                    DATE_FORMAT(ccar.ccar_fecha_factura,'%Y-%m-%d') as F_SUS_D,
                    DATE_FORMAT(ccar.ccar_fecha_vencepago,'%Y-%m-%d') as F_VEN_D,
                    ccar.ccar_valor_cuota,
                    CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN (ccar.ccar_valor_factura - ccar.ccar_valor_cuota)
                    ELSE (ccar.ccar_valor_factura - (SUBSTRING(ccar.ccar_num_cuota,1,3)) * ccar.ccar_valor_cuota)
                    END  as SALDO,
                    CASE
                        WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                        ELSE SUBSTRING(ccar.ccar_num_cuota,1,3)
                    END  as cuota
                    ,ccar_id
                    ,ccar_abono as abono
                FROM " . $con->dbname . ".carga_cartera ccar
                WHERE ccar.ccar_documento_identidad= :cedula AND ccar.ccar_num_cuota= :cuota AND ccar.ccar_numero_documento =:factura";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":factura", $factura, \PDO::PARAM_STR);
        $comando->bindParam(":cuota", $cuota, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }//function consultarPagospendientesp

    public function consultarPagospendientesPorFactura($cedula, $factura) {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT
                  (SELECT SUM(ccar.ccar_valor_cuota) FROM " . $con->dbname . ".carga_cartera ccar
                    WHERE ccar.ccar_documento_identidad= :cedula AND ccar.ccar_estado_cancela='N' AND ccar.ccar_tipo_documento='FE')  as total_deuda,
                    ccar.ccar_tipo_documento,
                    ccar.ccar_numero_documento as NUM_NOF,
                    ccar.ccar_documento_identidad,
                    ccar.ccar_forma_pago,
                    ccar.ccar_num_cuota as NUM_DOC,
                    -- SUBSTR(ccar.ccar_num_cuota,-3) as cantidad,
                    CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                    ELSE SUBSTRING(ccar.ccar_num_cuota,-3)
                    END  as cantidad,
                    DATE_FORMAT(ccar.ccar_fecha_factura,'%Y-%m-%d') as F_SUS_D,
                    DATE_FORMAT(ccar.ccar_fecha_vencepago,'%Y-%m-%d') as F_VEN_D,
                    ccar.ccar_valor_cuota,
                    CASE
                    WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN (ccar.ccar_valor_factura - ccar.ccar_valor_cuota)
                    ELSE (ccar.ccar_valor_factura - (SUBSTRING(ccar.ccar_num_cuota,1,3)) * ccar.ccar_valor_cuota)
                    END  as SALDO,
                    CASE
                        WHEN ccar.ccar_num_cuota = ccar.ccar_numero_documento THEN '01'
                        ELSE SUBSTRING(ccar.ccar_num_cuota,1,3)
                    END  as cuota
                    ,ccar_id
                FROM " . $con->dbname . ".carga_cartera ccar
                WHERE ccar.ccar_documento_identidad= :cedula
                  AND ccar.ccar_numero_documento =:factura
                  AND ccar.ccar_num_cuota = :cuota
                  AND ccar.ccar_estado_cancela != C";


        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":factura", $factura, \PDO::PARAM_STR);
        $comando->bindParam(":cuota", $cuota, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }//function consultarPagospendientesPorFactura

    /**
     * Function consultarAutorizadofechamayor
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarAutorizadofechamayor($est_id) {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT est_id, ccar_fecha_vencepago, 'Autorizado' as estado
                FROM db_facturacion.carga_cartera
                WHERE est_id = :est_id AND
                      ccar_fecha_vencepago >= NOW()
                ORDER BY ccar_fecha_vencepago asc
                LIMIT 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarAutorizadofechamenor
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarAutorizadofechamenor($est_id) {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT est_id, ccar_fecha_vencepago,
                CASE WHEN mi.ccar_estado_cancela = 'C'
                     THEN 'Autorizado'
                     ELSE 'No Autorizado' END AS estado
                FROM db_facturacion.carga_cartera mi
                WHERE mi.est_id = :est_id AND
                      mi.ccar_fecha_vencepago <= NOW()
                ORDER BY ccar_fecha_vencepago desc
                LIMIT 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarReportcartera
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public static function consultarReportcartera($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;

        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "(pers.per_pri_nombre like :estudiante OR ";
                $str_search .= "pers.per_pri_apellido like :estudiante OR ";
                $str_search .= "pers.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['f_inif'] != "" && $arrFiltro['f_finf'] != "") {
                $str_search .= " ccar.ccar_fecha_factura BETWEEN :fec_inif AND :fec_finf AND ";
            }
            if ($arrFiltro['f_iniv'] != "" && $arrFiltro['f_finv'] != "") {
                $str_search .= " ccar.ccar_fecha_vencepago BETWEEN :fec_iniv AND :fec_finv AND ";
            }
            if ($arrFiltro['estadopago'] != '0') {
                $str_search .= "ccar.ccar_estado_cancela = :estadopago AND ";
            }
        }
        $sql = "SELECT
                        ccar.ccar_documento_identidad,
                        concat(pers.per_pri_nombre , ' ', per_pri_apellido) as nombres,
                        ccar.ccar_numero_documento,
                        ccar.ccar_num_cuota,
                        date_format(ccar.ccar_fecha_factura, '%Y-%m-%d') as fecha_factura,
                        date_format(ccar.ccar_fecha_vencepago, '%Y-%m-%d') as fecha_vencimiento,
                        ccar.ccar_valor_cuota,
                        ccar.ccar_valor_factura,
                        CASE
                                    WHEN ccar.ccar_estado_cancela = 'C' THEN NULL
                                    WHEN ccar.ccar_estado_cancela = 'N' THEN ccar_abono
                                    END AS ccar_abono,
                        CASE ccar.ccar_estado_cancela
                                            WHEN 'C' THEN 'Cancelado'
                                            WHEN 'N' THEN 'Pendiente'
                                        END AS estado_cuota,
                        CASE
                                    WHEN ccar.ccar_forma_pago = 'EF' AND ccar.ccar_num_cuota = ccar.ccar_numero_documento AND ccar.ccar_estado_cancela = 'C' THEN (ccar.ccar_valor_factura - ccar.ccar_valor_cuota)
                                    WHEN ccar.ccar_forma_pago = 'EF' AND ccar.ccar_num_cuota = ccar.ccar_numero_documento AND ccar.ccar_estado_cancela = 'N' THEN ccar.ccar_valor_cuota - ccar.ccar_abono
                                    WHEN ccar.ccar_forma_pago = 'CR' AND ccar.ccar_estado_cancela = 'N' THEN ccar.ccar_valor_cuota - ccar.ccar_abono
                                    WHEN ccar.ccar_forma_pago = 'CR' AND ccar.ccar_estado_cancela = 'C' THEN ROUND((ccar.ccar_valor_factura - (SUBSTRING(ccar.ccar_num_cuota,1,3)) * ccar.ccar_valor_cuota),2)
                                    -- ELSE (ccar.ccar_valor_factura - (SUBSTRING(ccar.ccar_num_cuota,1,3)) * ccar.ccar_valor_cuota)
                                    END AS saldo
                FROM " . $con2->dbname . ".carga_cartera ccar
                INNER JOIN " . $con->dbname . ".estudiante est ON est.est_id = ccar.est_id
                INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = est.per_id
                WHERE $str_search ccar.ccar_estado=:estado AND ccar.ccar_estado_logico=:estado  ORDER BY ccar.ccar_fecha_factura DESC ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_inif = $arrFiltro["f_inif"] . " 00:00:00";
            $fecha_finf = $arrFiltro["f_finf"] . " 23:59:59";
            $fecha_iniv = $arrFiltro["f_iniv"] . " 00:00:00";
            $fecha_finv = $arrFiltro["f_finv"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $estadopago = $arrFiltro['estadopago'];
            if ($arrFiltro['f_inif'] != "" && $arrFiltro['f_finf'] != "") {
                $comando->bindParam(":fec_inif", $fecha_inif, \PDO::PARAM_STR);
                $comando->bindParam(":fec_finf", $fecha_finf, \PDO::PARAM_STR);
            }
            if ($arrFiltro['f_iniv'] != "" && $arrFiltro['f_finv'] != "") {
                $comando->bindParam(":fec_iniv", $fecha_iniv, \PDO::PARAM_STR);
                $comando->bindParam(":fec_finv", $fecha_finv, \PDO::PARAM_STR);
            }
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['estadopago'] != '0') {
                $comando->bindParam(":estadopago", $estadopago, \PDO::PARAM_STR);
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
                    'ccar__id',
                    'ccar_fecha_creacion',
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
     * Function consultarEstudiantesautorizados
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarEstudiantesautorizados() {
        $con = \Yii::$app->db_facturacion;
        $sql = " SELECT est_id, ccar_fecha_vencepago, 'Autorizado' as estado
                   FROM db_facturacion.carga_cartera 
                  WHERE est_id = :est_id AND 
                        ccar_fecha_vencepago >= NOW() 
               ORDER BY ccar_fecha_vencepago asc
                  LIMIT 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);       
        $resultData = $comando->queryOne();
        return $resultData;
    }//function consultarEstudiantesautorizados

    /**
     * Function consultarReportcartera
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public static function consultarCarteraEstudiantes($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_facturacion;

        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] > 0) {
                $str_search = "per.per_id = :per_id  AND ";
            }
        }
        if ($onlyData == false) {
            $est_id = "est.est_id,";
            $per_id = "per.per_id,";
        }
        $sql = "SELECT DISTINCT
        ccar.ccar_documento_identidad,
        $est_id
        $per_id
        CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) AS nombres,
        per.per_correo,
        IFNULL(est.est_matricula,' ') AS matricula
        FROM " . $con2->dbname . ".carga_cartera ccar
        INNER JOIN " . $con->dbname . ".estudiante est on est.est_id = ccar.est_id
        INNER JOIN " . $con1->dbname . ".persona per on per.per_id = est.per_id
        WHERE
        $str_search
        est.est_estado = :estado AND
        est.est_estado_logico = :estado AND
        ccar.ccar_estado_cancela = 'N'";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] > 0) {
                $comando->bindParam(":per_id", $arrFiltro["search"], \PDO::PARAM_INT);
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
                    'est_id',
                    'ccar_documento_identidad',
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
     * Function eliminar el curso estados en 0.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function eliminarCargaCartera($ccar_id, $ccar_usu_modifica, $ccar_fecha_modificacion) {
        $estado = 0;
        $con = \Yii::$app->db_facturacion;

        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".carga_cartera
                      SET ccar_estado = :estado,
                          ccar_usu_modifica = :ccar_usu_modifica,
                          ccar_fecha_modificacion = :ccar_fecha_modificacion,
                          ccar_estado_logico = :estado
                      WHERE
                      ccar_id = :ccar_id ");
            $comando->bindParam(":ccar_id", $ccar_id, \PDO::PARAM_INT);
            $comando->bindParam(":ccar_usu_modifica", $ccar_usu_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":ccar_fecha_modificacion", $ccar_fecha_modificacion, \PDO::PARAM_STR);
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
