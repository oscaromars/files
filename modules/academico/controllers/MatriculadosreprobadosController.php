<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\MatriculadosReprobado;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Admitido;
use app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\ItemMetodoUnidad;
use app\modules\financiero\models\DetalleDescuentoItem;
use app\modules\academico\models\ModuloEstudio;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\Persona;
use app\models\Empresa;
use app\models\Provincia;
use app\models\Pais;
use app\models\Canton;
use app\models\MedioPublicitario;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\PersonaGestion;
use app\modules\admision\models\Oportunidad;
use app\modules\admision\models\MetodoIngreso;
use app\models\InscripcionAdmision;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\models\ExportFile;

academico::registerTranslations();
admision::registerTranslations();

class MatriculadosreprobadosController extends \app\components\CController {

    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $mod_carrera = new EstudioAcademico();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["estadomat"] = $data['estadomat'];
            $mod_matreprueba = MatriculadosReprobado::getMatriculadosreprobados($arrSearch);
            return $this->renderPartial('index-grid', [
                        "model" => $mod_matreprueba,
            ]);
        } else {
            $mod_aspirante = MatriculadosReprobado::getMatriculadosreprobados();
        }
        if (Yii::$app->request->isAjax) {//
            $data = Yii::$app->request->get();
            if (isset($data["op"]) && $data["op"] == '1') {
                
            }
        }
        $arrCarreras = ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Grid")]], $mod_carrera->consultarCarrera()), "id", "value");
        return $this->render('index', [
                    'model' => $mod_aspirante,
                    'arrCarreras' => $arrCarreras,
        ]);
    }

    public function actionSavereprobadostemp() {
        if (Yii::$app->request->isAjax) {
            $model = new MatriculadosReprobado();
            $data = Yii::$app->request->post();
            $con = \Yii::$app->db_captacion;
            try {
                $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
                $repro_temp_id = $data["DATA_1"][0]["twin_id"];
                $accion = isset($data['ACCION']) ? $data['ACCION'] : "";
                $timeSt = time();
                if ($data["upload_file"]) {
                    if (empty($_FILES)) {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                    $matr_repro_id = $data["matr_repro_id"];
                    $files = $_FILES[key($_FILES)];
                    $arrIm = explode(".", basename($files['name']));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "academico/" . $matr_repro_id . "/" . $data["name_file"] . "_per_" . $matr_repro_id . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
                if ($accion == "create" || $accion == "Create") {
                    $resul = $model->insertarReprobadoTemp($con, $data["DATA_1"]);
                } else if ($accion == "Update") {
                    $matr_repro_id = $data["DATA_1"][0]['twre_id'];
                    if ($data["PASO"] == 1){
                        $keys_act = [
                            'twre_nombre', 'twre_apellido', 'twre_dni', 'twre_numero'
                            , 'twre_correo', 'twre_pais', 'twre_celular'
                            , 'uaca_id', 'mod_id', 'car_id'
                        ];
                        $values_act = [
                            $data["DATA_1"][0]['pges_pri_nombre'], $data["DATA_1"][0]['pges_pri_apellido'], $data["DATA_1"][0]['tipo_dni'], $data["DATA_1"][0]['pges_cedula'],
                            $data["DATA_1"][0]['pges_correo'], $data["DATA_1"][0]['pais'], $data["DATA_1"][0]['pges_celular'],
                            $data["DATA_1"][0]['unidad_academica'], $data["DATA_1"][0]['modalidad'], $data["DATA_1"][0]['carrera']
                        ];
                    }else if ($data["PASO"] == 1 || $data["PASO"] == 2) {
                        $path_title = $data["DATA_1"][0]['ruta_doc_titulo'];
                        if (strpos($path_title, "fakepath") !== false) {
                            $path_title_true = explode("\\", $path_title)[2];
                        } else {
                            $path_title_true = $path_title;
                        }
                        //ruta_doc_dni
                        $path_dni = $data["DATA_1"][0]['ruta_doc_dni'];
                        if (strpos($path_dni, "fakepath") !== false) {
                            $path_dni_true = explode("\\", $path_dni)[2];
                        } else {
                            $path_dni_true = $path_dni;
                        }
                        //ruta_doc_certvota
                        $path_certvota = $data["DATA_1"][0]['ruta_doc_certvota'];
                        if (strpos($path_certvota, "fakepath") !== false) {
                            $path_certvota_true = explode("\\", $path_certvota)[2];
                        } else {
                            $path_certvota_true = $path_certvota;
                        }
                        //ruta_doc_certificado
                        $path_certificado = $data["DATA_1"][0]['ruta_doc_certificado'];
                        if (strpos($path_certificado, "fakepath") !== false) {
                            $path_certificado_true = explode("\\", $path_certificado)[2];
                        } else {
                            $path_certificado_true = $path_certificado;
                        }
                        if (isset($path_title_true) && $path_title_true != "") {
                            $arrIm = explode(".", basename($path_title_true));
                            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                            $titulo_archivoOld = Yii::$app->params["documentFolder"] . "academico/" . $matr_repro_id . "/doc_titulo_per_" . $matr_repro_id . "." . $typeFile;
                            \app\models\Utilities::putMessageLogFile('address: ' . $titulo_archivoOld);
                            $titulo_archivo = MatriculadosReprobado::addLabelTimeDocumentos($matr_repro_id, $titulo_archivoOld, $timeSt);
                            $data["DATA_1"][0]["ruta_doc_titulo"] = $titulo_archivo;
                            if ($titulo_archivo === false)
                                throw new Exception('Error doc Titulo no renombrado.');
                        }
                        if (isset($path_dni_true) && $path_dni_true != "") {
                            $arrIm = explode(".", basename($path_dni_true));
                            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                            $dni_archivoOld = Yii::$app->params["documentFolder"] . "academico/" . $matr_repro_id . "/doc_dni_per_" . $matr_repro_id . "." . $typeFile;
                            $dni_archivo = MatriculadosReprobado::addLabelTimeDocumentos($matr_repro_id, $dni_archivoOld, $timeSt);
                            $data["DATA_1"][0]["ruta_doc_dni"] = $dni_archivo;
                            if ($dni_archivo === false)
                                throw new Exception('Error doc Dni no renombrado.');
                        }
                        if (isset($path_certvota_true) && $path_certvota_true != "") {
                            $arrIm = explode(".", basename($path_certvota_true));
                            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                            $certvota_archivoOld = Yii::$app->params["documentFolder"] . "academico/" . $matr_repro_id . "/doc_certvota_per_" . $matr_repro_id . "." . $typeFile;
                            $certvota_archivo = MatriculadosReprobado::addLabelTimeDocumentos($matr_repro_id, $certvota_archivoOld, $timeSt);
                            $data["DATA_1"][0]["ruta_doc_certvota"] = $certvota_archivo;
                            if ($certvota_archivo === false)
                                throw new Exception('Error doc certificado vot. no renombrado.');
                        }
                        if (isset($path_certificado_true) && $path_certificado_true != "") {
                            $arrIm = explode(".", basename($path_certificado_true));
                            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                            $certificado_archivoOld = Yii::$app->params["documentFolder"] . "academico/" . $matr_repro_id . "/doc_certvota_per_" . $matr_repro_id . "." . $typeFile;
                            $certificado_archivo = MatriculadosReprobado::addLabelTimeDocumentos($matr_repro_id, $certificado_archivoOld, $timeSt);
                            $data["DATA_1"][0]["ruta_doc_certificado"] = $certificado_archivo;
                            if ($certvota_archivo === false)
                                throw new Exception('Error doc certificado vot. no renombrado.');
                        }
                        $keys_act = [
                            'twre_nombre', 'twre_apellido', 'twre_dni', 'twre_numero'
                            , 'twre_correo', 'twre_pais', 'twre_celular'
                            , 'uaca_id', 'mod_id', 'car_id'
                            , 'twre_metodo_ingreso', 'ruta_doc_titulo', 'ruta_doc_dni'
                            , 'ruta_doc_certvota', 'ruta_doc_foto', 'ruta_doc_certificado'
                            , 'ruta_doc_hojavida', 'twre_mensaje1', 'twre_mensaje2'
                            , 'twre_fecha_solicitud', 'sdes_id', 'ite_id', 'twre_precio_item'
                            , 'twre_precio_descuento', 'twre_observacion_sol'
                        ];
                        $values_act = [
                            $data["DATA_1"][0]['pges_pri_nombre'], $data["DATA_1"][0]['pges_pri_apellido'], $data["DATA_1"][0]['tipo_dni'], $data["DATA_1"][0]['pges_cedula'],
                            $data["DATA_1"][0]['pges_correo'], $data["DATA_1"][0]['pais'], $data["DATA_1"][0]['pges_celular'],
                            $data["DATA_1"][0]['unidad_academica'], $data["DATA_1"][0]['modalidad'], $data["DATA_1"][0]['carrera'],
                            $data["DATA_1"][0]['ming_id'], $data["DATA_1"][0]["ruta_doc_titulo"], $data["DATA_1"][0]["ruta_doc_dni"],
                            $data["DATA_1"][0]["ruta_doc_certvota"], $data["DATA_1"][0]['ruta_doc_foto'], $data["DATA_1"][0]["ruta_doc_certvota"],
                            $data["DATA_1"][0]['ruta_doc_hojavida'], $data["DATA_1"][0]['twre_mensaje1'], $data["DATA_1"][0]['twre_mensaje2']                            
                        ];
                    } #end step 1 and step 2
                    else if($data["PASO"] == 3){
                            $keys_act = [
                                'twre_nombre', 'twre_apellido', 'twre_dni', 'twre_numero'
                                , 'twre_correo', 'twre_pais', 'twre_celular'
                                , 'uaca_id', 'mod_id', 'car_id', 'twre_metodo_ingreso'
                                , 'twre_mensaje1', 'twre_mensaje2','twre_beca'
                                , 'twre_fecha_solicitud', 'sdes_id', 'ite_id', 'twre_precio_item'
                                , 'twre_precio_descuento', 'twre_observacion_sol'
                            ];
                            $values_act = [
                                $data["DATA_1"][0]['pges_pri_nombre'], $data["DATA_1"][0]['pges_pri_apellido'], $data["DATA_1"][0]['tipo_dni'], $data["DATA_1"][0]['pges_cedula'],
                                $data["DATA_1"][0]['pges_correo'], $data["DATA_1"][0]['pais'], $data["DATA_1"][0]['pges_celular'],
                                $data["DATA_1"][0]['unidad_academica'], $data["DATA_1"][0]['modalidad'], $data["DATA_1"][0]['carrera'],
                                $data["DATA_1"][0]['ming_id'],$data["DATA_1"][0]['twre_mensaje1'], 
                                $data["DATA_1"][0]['twre_mensaje2'], $data["DATA_1"][0]['beca'],
                                $data["DATA_1"][0]['fecha_solicitud'], $data["DATA_1"][0]['sdes_id'], $data["DATA_1"][0]['ite_id'],
                                $data["DATA_1"][0]['precio_item'], $data["DATA_1"][0]['precio_item_desc'], $data["DATA_1"][0]['observacionw'],
                            ];
                    }
                    $resul = $model->actualizarReprobadoTemp($con, $data["DATA_1"][0]['twre_id'], $values_act, $keys_act, 'temporal_wizard_reprobados');
                    if($data["PASO"]==3){
                        $resul=$model->insertaOriginal($resul["twre_id"]);                        
                    }
                }
                if ($resul['status']) {
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "The information have been saved"),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message, $resul);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => $ex->getMessage(),
                    "title" => Yii::t('jslang', 'Error'),
                );
                \app\models\Utilities::putMessageLogFile('error:  ' . $ex->getMessage());
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }

    public function actionNewreprobado() {
        $mod_admitido = new MatriculadosReprobado();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $modcanal = new Oportunidad();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $dato = Yii::$app->request->get();
        if (isset($dato["search"])) {
            $arradmitido = $mod_admitido->getMatriculados($dato["search"]);
            return $this->renderPartial('newreprobado-grid', [
                        "model" => $arradmitido,
            ]);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $datas = Yii::$app->request->get();
            if ($datas['PBgetFilter']) {
                $uaca_id = $datas['unidad'];
                $moda_id = $datas['modalidad'];
                $car_id = $datas['carrera'];
                $periodo = $datas['periodo'];
                $arrperio = $mod_periodo->consultarPeriodoanterior($periodo);
                $arr_materia = $mod_admitido->consultarMateriasPorUnidadModalidadCarrera($uaca_id, $moda_id, $car_id, $arrperio[0]["mes"], $arrperio[0]["anio"]);
                return $this->renderPartial('materia-grid', [
                            'model' => $arr_materia,
                ]);
            } else {
                $arr_materia = $mod_admitido->consultarMateriasPorUnidadModalidadCarrera(0, 0, 0, '', '');
            }
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getmodamat"])) {
                $modalidadmat = $mod_modalidad->consultarModalidad($data["unimat_id"], 1);
                $message = array("modalidadmat" => $modalidadmat);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcarreramat"])) {
                $carreramat = $modcanal->consultarCarreraModalidad($data["unimati"], $data["modamat_id"]);
                $message = array("carreramat" => $carreramat);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
        }
        //$arrperiodo = $mod_periodo->consultarPeriodo();
        $arradmitido = $mod_admitido->getMatriculados(0);
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        $arr_materia = $mod_admitido->consultarMateriasPorUnidadModalidadCarrera(0, 0, 0, '', '');
        return $this->render('newreprobado', [
                    'admitido' => $arradmitido,
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrerra1), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_ninteres), "id", "name"),
                    /*'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arrperiodo), "id", "name"),*/
                    'arr_estado_aprobacion' => ArrayHelper::map([["id" => "0", "name" => "Aprobado"]], "id", "name"),
                    'arr_materia' => $arr_materia,
        ]);
    }

    public function actionView() {
        return $this->render('view', [
        ]);
    }

    public function actionEdit() {
        return $this->render('edit', [
        ]);
    }

    public function actionNew() {
        $per_id = Yii::$app->session->get("PB_perid");
        $mod_persona = Persona::findIdentity($per_id);
        $mod_modalidad = new Modalidad();
        $mod_solins = new SolicitudInscripcion();
        $empresa_mod = new Empresa();
        $mod_pergestion = new PersonaGestion();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $modestudio = new ModuloEstudio();
        $mod_metodo = new MetodoIngreso();
        $mod_inscripcion = new InscripcionAdmision();
        $modItemMetNivel = new ItemMetodoUnidad();
        $modDescuento = new DetalleDescuentoItem();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getuacademias"])) {
                $data_u_acad = $mod_unidad->consultarUnidadAcademicasEmpresa($data["empresa_id"]);
                $message = array("unidad_academica" => $data_u_acad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais
                $mod_areapais = new Pais();
                $area = $mod_areapais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], $data["empresa_id"]);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                if ($data["empresa_id"] == 1) {
                    $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                } else {
                    $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"], $data["empresa_id"]); // tomar id de impresa
                }
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmetodo"])) {
                $metodos = $mod_metodo->consultarMetodoUnidadAca_2($data['nint_id']);
                $message = array("metodos" => $metodos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getdescuento"])) {                
                $resItems = $modItemMetNivel->consultarXitemMetniv($data["unidada"], $data["moda_id"], $data["metodo"], $data["empresa_id"], $data["carrera_id"]);                            
                $descuentos = $modDescuento->consultarDesctohistoriaxitem($resItems["ite_id"], $data["fecha"]);
                $message = array("descuento" => $descuentos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getitem"])) {
                if ($data["empresa_id"] != 1) {
                    $metodo = 0;
                } else {
                    $metodo = $data["metodo"];
                }
                $resItem = $modItemMetNivel->consultarXitemPrecio($data["unidada"], $data["moda_id"], $metodo, $data["carrera_id"], $data["empresa_id"]);
                $message = array("items" => $resItem);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getprecio"])) {      
                \app\models\Utilities::putMessageLogFile('item:'.$data["ite_id"]);      
                \app\models\Utilities::putMessageLogFile('fecha:'.$data["fecha"]);      
                $resp_precio = $mod_solins->ObtenerPreciohistoricoXitem($data["ite_id"], $data["fecha"]);                  
                $message = array("precio" => $resp_precio["precio"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getpreciodescuento"])) {                                 
                $resp_precio = $mod_solins->ObtenerPreciohistoricoXitem($data["ite_id"], $data["fecha"]);                                  
                if ($data["descuento_id"] > 0) {     
                    \app\models\Utilities::putMessageLogFile('id descuento:'.$data["descuento_id"]);  
                    $respDescuento = $modDescuento->consultarHistoricodctoXitem($data["descuento_id"], $data["fecha"]); 
                    if ($resp_precio["precio"] == 0) {                                    
                        $precioDescuento = 0;   
                    } else {                                     
                        if ($respDescuento["hdit_tipo_beneficio"] == 'P') {
                            $descuento = ($resp_precio["precio"] * $respDescuento["hdit_porcentaje"]) / 100;
                        } else {
                            $descuento = $respDescuento["hdit_valor"];
                        }
                        $precioDescuento = $resp_precio["precio"] - $descuento;
                    }
                } else {
                    $precioDescuento = 0;
                }
                \app\models\Utilities::putMessageLogFile('precio descuento:' . $precioDescuento);
                $message = array("preciodescuento" => $precioDescuento);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_pais_dom = Pais::find()->select("pai_id AS id, pai_nombre AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
        $pais_id = 1; //Ecuador
        $empresa = $empresa_mod->getAllEmpresa();
        $arr_prov_dom = Provincia::provinciaXPais($pais_id);
        $arr_ciu_dom = Canton::cantonXProvincia($arr_prov_dom[0]["id"]);
        $arr_medio = MedioPublicitario::find()->select("mpub_id AS id, mpub_nombre AS value")->where(["mpub_estado_logico" => "1", "mpub_estado" => "1"])->asArray()->all();
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad(1, 1);
        $arr_conuteg = $mod_pergestion->consultarConociouteg();
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad(1, $arr_modalidad[0]["id"]);
        $arr_metodos = $mod_metodo->consultarMetodoUnidadAca_2($arr_ninteres[0]["id"]);
        $_SESSION['JSLANG']['Your information has not been saved. Please try again.'] = Yii::t('notificaciones', 'Your information has not been saved. Please try again.');
        $resp_item = $modItemMetNivel->consultarXitemPrecio($arr_ninteres[0]["id"], $arr_modalidad[0]["id"], $arr_metodos[0]["id"], $arr_carrerra1[0]["id"], $empresa[0]["id"]);
        $resp_precio = $mod_solins->ObtenerPrecioXitem($resp_item[0]["id"]);
        $arr_descuento = $modDescuento->consultarDesctoxitem($resp_item[0]["id"]);
        $respDescuento = $modDescuento->consultarValdctoItem($arr_descuento[0]["id"]);
        if ($respDescuento["ddit_tipo_beneficio"] == 'P') {
            $descuento = ($resp_precio["precio"] * $respDescuento["ddit_porcentaje"]) / 100;
        } else {
            $descuento = $respDescuento["ddit_valor"];
        }
        $precioDescuento = $resp_precio["precio"] - $descuento;
        return $this->render('new', [
                    "tipos_dni" => array("CED" => Yii::t("formulario", "DNI Document"), "PASS" => Yii::t("formulario", "Passport")),
                    "tipos_dni2" => array("CED" => Yii::t("formulario", "DNI Document1"), "PASS" => Yii::t("formulario", "Passport1")),
                    "txth_extranjero" => $mod_persona->per_nac_ecuatoriano,
                    "arr_pais_dom" => ArrayHelper::map($arr_pais_dom, "id", "value"),
                    "arr_prov_dom" => ArrayHelper::map($arr_prov_dom, "id", "value"),
                    "arr_ciu_dom" => ArrayHelper::map($arr_ciu_dom, "id", "value"),
                    "arr_ninteres" => ArrayHelper::map($arr_ninteres, "id", "name"),
                    "arr_descuento" => ArrayHelper::map($arr_descuento, "id", "name"),
                    "arr_medio" => ArrayHelper::map($arr_medio, "id", "value"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_conuteg" => ArrayHelper::map($arr_conuteg, "id", "name"),
                    "arr_carrerra1" => ArrayHelper::map($arr_carrerra1, "id", "name"),
                    "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
                    "arr_item" => ArrayHelper::map($resp_item, "id", "name"),
                    "arr_empresa" => ArrayHelper::map($empresa, "id", "value"),                   
        ]);
    }

    public function actionSave() {
        $periodo = 0;
        $mod_admitido = new MatriculadosReprobado();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $ides = $data["ids"];
            $valores = explode("_", $ides);
            $admitido = $valores[0];
            $sins_id = $valores[1];
            $uniacademica = $data["uniacademica"];
            $modalidad = $data["modalidad"];
            $carrera = $data['carreprog'];
            $asigna = $data['materia'];
            $usuario = @Yii::$app->user->identity->usu_id;
            $periodo = $data['periodo'];
            $estadomat = $data['estadomat'];
            $con = \Yii::$app->db_captacion;
            $transaction = $con->beginTransaction();
            $reprobar = '';
            $uniacademicamat = $data["uniacademicamat"];
            $modalidadmat = $data["modalidadmat"];
            $carreramat = $data['carreprogmat'];
            try {
                $mod_reprobado = new MatriculadosReprobado();
                $fecha_creacion = date(Yii::$app->params["dateTimeByDefault"]);
                $resp_matreprobado = $mod_reprobado->consultarReprobado($sins_id);
                if ($resp_matreprobado["encontrado"] == 0) {
                    $resp_ingreso = $mod_reprobado->insertarMatricureprobado($admitido, $periodo, $sins_id, $uniacademicamat, $modalidadmat, $carreramat, $usuario, $estadomat, $fecha_creacion);
                    $mre_id = Yii::$app->db_captacion->getLastInsertID('db_captacion.matriculados_reprobado');
                    if ($resp_ingreso) {
                        if (!empty($asigna)) {
                            for ($i = 0; $i < strlen($asigna); $i++) {
                                //Guardado Datos Materias reprobadas.                        
                                $asigantura = $asigna[$i];
                                $estado_materia = 2;
                                if ($asigantura != ' ') {
                                    $res_materia = $mod_reprobado->insertarMateriareprueba($mre_id, $asigantura, $estado_materia, $usuario, $fecha_creacion);
                                    if ($res_materia) {
                                        $exito = 1;
                                    } else {
                                        $exito = 0;
                                    }
                                    $reprobar = $reprobar . ' ' . 'asig.asi_id != ' . $asigna[$i] . ' and ';
                                }
                            }
                        }
                        //Guardado Datos Materias aprobadas.                         
                        $estado_materiare = 1;
                        $arrperio = $mod_periodo->consultarPeriodoanterior($periodo);
                        $arr_materia = $mod_admitido->consultarMateriarep($uniacademica, $modalidad, $carrera, $reprobar, $arrperio[0]["mes"], $arrperio[0]["anio"]);
                        for ($j = 0; $j < count($arr_materia); $j++) {
                            $res_reprobam = $mod_reprobado->insertarMateriareprueba($mre_id, $arr_materia[$j]["id"], $estado_materiare, $usuario, $fecha_creacion);
                            if ($res_reprobam) {
                                $exito = 1;
                            }
                        }
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        echo Utilities::ajaxResponse('NO_OK', 'Error', Yii::t("jslang", "Error"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("Error", "Ya se encuentra ingresada esta persona." . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    echo Utilities::ajaxResponse('NO_OK', 'Error', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                echo Utilities::ajaxResponse('NO_OK', 'Error', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionUpdate() {
        return $this->render('update', [
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            ' ',
            Yii::t("formulario", "Last Names"),
            ' ',
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "CellPhone"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),        
            academico::t("Academico", "Career/Program"),
            admision::t("Solicitudes", "Income Method"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Status")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["f_ini"] = $data['fecha_ini'];
            $arrSearch["f_fin"] = $data['fecha_fin'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["estadomat"] = $data['estadomat'];
        }
        $arrData = array();
        $mod_matreprueba = new MatriculadosReprobado();
        if (count($arrSearch) > 0) {
            $arrData = $mod_matreprueba->consultarMatriculareprueba($arrSearch, true);
        } else {
            $arrData = $mod_matreprueba->consultarMatriculareprueba(array(), true);
        }
        $nameReport = academico::t("Academico", "List Enrollment Method Income");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExportpdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List Enrollment Method Income");  // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            ' ',
            Yii::t("formulario", "Last Names"),
            ' ',
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "CellPhone"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),          
            academico::t("Academico", "Career/Program"),
            admision::t("Solicitudes", "Income Method"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Status")
        );
        $data = Yii::$app->request->get();
        $arrSearch = array();
        if (count($data) > 0) {
            $arrSearch["f_ini"] = $data['fecha_ini'];
            $arrSearch["f_fin"] = $data['fecha_fin'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["estadomat"] = $data['estadomat'];
        }
        $arrData = array();
        $mod_matreprueba = new MatriculadosReprobado();
        if (count($arrSearch) > 0) {
            $arrData = $mod_matreprueba->consultarMatriculareprueba($arrSearch, true);
        } else {
            $arrData = $mod_matreprueba->consultarMatriculareprueba(array(), true);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

}
