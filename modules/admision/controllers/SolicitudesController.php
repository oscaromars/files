<?php

namespace app\modules\admision\controllers;

use Yii;
use app\models\Utilities;
use app\models\ExportFile;
use app\models\Persona;
use yii\helpers\Url;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\MetodoIngreso;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\DocumentoAceptacion;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\ModuloEstudio;
use app\modules\admision\models\ItemMetodoUnidad;
use app\modules\financiero\models\DetalleDescuentoItem;
use app\modules\academico\models\UnidadAcademica;
use app\modules\admision\models\SolicitudinsDocumento;
use app\modules\financiero\models\OrdenPago;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\DocumentoAdjuntar;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\financiero\models\Secuencias;
use app\modules\admision\models\ConvenioEmpresa;
use app\models\Usuario;
use yii\base\Security;
use app\models\Empresa;
use app\models\UsuaGrolEper;
use app\modules\academico\models\Estudiante;

academico::registerTranslations();
financiero::registerTranslations();

class SolicitudesController extends \app\components\CController {

    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $per_ids = base64_decode($_GET['ids']);
        $data = Yii::$app->request->get();
        $modSolicitud = new SolicitudInscripcion();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $modcanal = new Oportunidad();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["estadoSol"] = $data['estadoSol'];
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["carrera"] = $data['carrera'];
            $respSolicitud = $modSolicitud->consultarSolicitudes($arrSearch);
        } else {                    
            $respSolicitud = $modSolicitud->consultarSolicitudes(); 
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $resp_estados = $modSolicitud->Consultaestadosolicitud();
        $arrEstados = ArrayHelper::map(array_merge([["id" => "0", "value" => Yii::t("formulario", "Grid")]], $resp_estados), "id", "value");
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        $arr_carrerra1 = $modcanal->consultarCarreraModalidad($arr_ninteres[0]["id"], $arr_modalidad[0]["id"]);
        return $this->render('index', [
                    'model' => $respSolicitud,
                    'arrEstados' => $arrEstados,
                    'arr_ninteres' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_carrerra1' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_carrerra1), "id", "name"),
        ]);
    }

    /**
     * Function 
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   Ninguno. 
     * @return  Una vista que recibe las solicitudes del usuario logeado.
     */
    public function actionListarsolicitudxinteresado() {
        $per_idsession = @Yii::$app->session->get("PB_perid");
        $per_Ids = base64_decode($_GET['perid']);
        $inte_id = base64_decode(empty($_GET['id']) ? 0 : $_GET['id']);
        $mod_carrera = new EstudioAcademico();
        $interesado_model = new Interesado();
        $persona_model = new Persona();
        $SolIns_model = new SolicitudInscripcion();
        if ($inte_id > 0) {
            $per_id = $interesado_model->getPersonaxIdInteresado($inte_id);
        } else {
            $per_id = $per_idsession;
            $inte_id = $interesado_model->consultaInteresadoByPerId($per_id);
        }
        $personaData = $persona_model->consultaPersonaId($per_id);
        $model = $SolIns_model->getSolicitudesXInteresado($inte_id);
        return $this->render('listarSolicitudxinteresado', [
                    'model' => $model,
                    'personalData' => $personaData,
        ]);
    }

    public function actionView() {
        $sins_id = base64_decode($_GET['ids']);
        $int_id = base64_decode($_GET['int']);
        $per_id = base64_decode($_GET['perid']);
        $emp_id = base64_decode($_GET['empid']);

        $mod_solins = new SolicitudInscripcion();
        $personaData = $mod_solins->consultarInteresadoPorSol_id($sins_id);
        $nacionalidad = $personaData["per_nac_ecuatoriano"];
        $fechas = $mod_solins->consultarFechadmitido($int_id, $sins_id);

        $resp_arch1 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 1);
        $resp_arch2 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 2);
        $resp_arch3 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 3);
        $resp_arch4 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 4);
        $resp_arch5 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 5);
        $resp_arch6 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 6);
        $resp_arch7 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 7);
        $resp_arch8 = $mod_solins->Obtenerdocumentosxsolicitud($sins_id, 8);

        $observa = $mod_solins->Obtenerobservadocumentos($sins_id);

        $mod_ordenpago = new OrdenPago();
        $resp_ordenpago = $mod_ordenpago->consultarImagenpago($sins_id);
        $img_pago = $resp_ordenpago["imagen_pago"];

        if (($nacionalidad == '1') or empty($nacionalidad)) {
            $tiponacext = 'N';
        } else {
            $tiponacext = 'E';
        }
        $resp_condtitulo = $mod_solins->consultarSolnoaprobada(1, $tiponacext);
        $resp_conddni = $mod_solins->consultarSolnoaprobada(2, $tiponacext);
        $resp_rechazo = $mod_solins->consultaSolicitudRechazada($sins_id, 'A');
        $resp_condcertv = $mod_solins->consultarSolnoaprobada(3, $tiponacext);
        $resp_condfoto = $mod_solins->consultarSolnoaprobada(4, $tiponacext);
        $resp_condcurriculum = $mod_solins->consultarSolnoaprobada(7, $tiponacext);
        $resp_condcon = $mod_solins->consultarSolnoaprobada(8, $tiponacext);

        return $this->render('view', [
                    "revision" => array("2" => Yii::t("formulario", "APPROVED"), "4" => Yii::t("formulario", "Not approved")),
                    "personaData" => $personaData,
                    "arch1" => $resp_arch1['sdoc_archivo'],
                    "arch2" => $resp_arch2['sdoc_archivo'],
                    "arch3" => $resp_arch3['sdoc_archivo'],
                    "arch4" => $resp_arch4['sdoc_archivo'],
                    "arch5" => $resp_arch5['sdoc_archivo'],
                    "arch6" => $resp_arch6['sdoc_archivo'],
                    "arch7" => $resp_arch7['sdoc_archivo'],
                    "arch8" => $resp_arch8['sdoc_archivo'],
                    "txth_extranjero" => $nacionalidad,
                    "sins_id" => $sins_id,
                    "int_id" => $int_id,
                    "per_id" => $per_id,
                    "arr_condtitulo" => $resp_condtitulo,
                    "arr_conddni" => $resp_conddni,
                    "resp_rechazo" => $resp_rechazo,
                    "img_pago" => $img_pago,
                    "emp_id" => $emp_id,
                    "arr_fecha" => $fechas,
                    "arr_certv" => $resp_condcertv,
                    "arr_observa" => $observa,
                    "arr_condcon" => $resp_condcon,
                    "arr_condfoto" => $resp_condfoto,
                    "arr_condcurriculum" =>$resp_condcurriculum,
        ]);
    }

    public function actionEdit() {
        
    }

    public function actionNew() {
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $mod_metodo = new MetodoIngreso();
        $empresa_mod = new Empresa();
        $per_id = base64_decode($_GET['per_id']);
        Yii::$app->session->set('persona_solicita', base64_encode($_GET['ids']));
        $mod_carrera = new EstudioAcademico();
        $mod_unidad = new UnidadAcademica();
        $persona_model = new Persona();
        $mod_modalidad = new Modalidad();
        $modcanal = new Oportunidad();
        $modestudio = new ModuloEstudio();
        $modItemMetNivel = new ItemMetodoUnidad();
        $modDescuento = new DetalleDescuentoItem();
        $modUnidad = new UnidadAcademica();
        $dataPersona = $persona_model->consultaPersonaId($per_id);
        $modInteresado = new Interesado();
        $inte_id = $modInteresado->consultarIdinteresado($per_id);
        $empresa = $empresa_mod->getAllEmpresa();
        $mod_solins = new SolicitudInscripcion();
        $mod_conempresa = new ConvenioEmpresa();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getuacademias"])) {
                //$data_u_acad = $mod_unidad->consultarUnidadAcademicasEmpresa($data["empresa_id"]);
                $data_u_acad->consultarUnidadAcademicas();
                $message = array("unidad_academica" => $data_u_acad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmodalidad"])) {
                if ($data["nint_id"] == 1 or $data["nint_id"] == 2) {
                    $modalidad = $mod_modalidad->consultarModalidad($data["nint_id"], $data["empresa_id"]);
                } else {
                    $modalidad = $modestudio->consultarModalidadModestudio();
                }
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getmetodo"])) {
                $metodos = $mod_metodo->consultarMetodoIngNivelInt($data['nint_id']);
                $message = array("metodos" => $metodos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                if ($data["unidada"] == 1 or $data["unidada"] == 2) {
                    $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                } else {
                    $carrera = $modestudio->consultarCursoModalidad($data["unidada"], $data["moda_id"]);
                }
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getdescuento"])) {
                if (($data["unidada"] == 1) or ($data["unidada"] == 2)) {
                    $resItems = $modItemMetNivel->consultarXitemMetniv($data["unidada"], $data["moda_id"], $data["metodo"], $data["empresa_id"], $data["carrera_id"]);
                    $descuentos = $modDescuento->consultarDesctoxitem($resItems["ite_id"]);
                } else {                    
                    //\app\models\Utilities::putMessageLogFile('item:'. $data["ite_id"]);
                    $descuentos = $modDescuento->consultarDescuentoXitemUnidad($data["unidada"], $data["moda_id"], $data["ite_id"]);                    
                }
                $message = array("descuento" => $descuentos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                
            }
            if (isset($data["getitem"])) {
                if ($data["empresa_id"] != 1) {
                    $metodo = 0;
                } else {
                    if ($data["unidada"] != 1) {
                        $metodo = $data["metodo"];
                    } else {
                        $metodo = 0;
                    }
                }
                $resItem = $modItemMetNivel->consultarXitemPrecio($data["unidada"], $data["moda_id"], $metodo, $data["carrera_id"], $data["empresa_id"]);
                $message = array("items" => $resItem);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getprecio"])) {
                $resp_precio = $mod_solins->ObtenerPrecioXitem($data["ite_id"]);
                $message = array("precio" => $resp_precio["precio"]);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getpreciodescuento"])) {
                $resp_precio = $mod_solins->ObtenerPrecioXitem($data["ite_id"]);
                if ($data["descuento_id"] > 0) {
                    $respDescuento = $modDescuento->consultarValdctoItem($data["descuento_id"]);
                    if ($resp_precio["precio"] == 0) {
                        $precioDescuento = 0;
                    } else {
                        if ($respDescuento["ddit_tipo_beneficio"] == 'P') {
                            $descuento = ($resp_precio["precio"] * $respDescuento["ddit_porcentaje"]) / 100;
                        } else {
                            $descuento = $respDescuento["ddit_valor"];
                        }
                        $precioDescuento = $resp_precio["precio"] - $descuento;
                    }
                } else {
                    $precioDescuento = 0;
                }
                $message = array("preciodescuento" => $precioDescuento);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["gethabilita"])) {                
                if ($data["ite_id"] == 155 or $data["ite_id"] == 156 or $data["ite_id"] == 157 or $data["ite_id"] == 10) {
                    $habilita = '1';
                } else {
                    $habilita = '0';
                };
                $message = array("habilita" => $habilita);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidadac = $mod_unidad->consultarUnidadAcademicas();
        $arr_modalidad = $mod_modalidad->consultarModalidad(1, 1);
        $arr_metodos = $mod_metodo->consultarMetodoIngNivelInt($arr_unidadac[0]["id"]);
        $arr_carrera = $modcanal->consultarCarreraModalidad(1, 1);
        //Descuentos y precios.
        $resp_item = $modItemMetNivel->consultarXitemPrecio(1, 1, 1, 2, 1);
        $arr_descuento = $modDescuento->consultarDesctoxitem($resp_item["ite_id"]);
        $arr_convempresa = $mod_conempresa->consultarConvenioEmpresa();
        return $this->render('new', [
                    "arr_unidad" => ArrayHelper::map($arr_unidadac, "id", "name"),
                    "arr_metodos" => ArrayHelper::map($arr_metodos, "id", "name"),
                    "arr_persona" => $dataPersona,
                    "arr_carrera" => ArrayHelper::map($arr_carrera, "id", "name"),
                    "arr_modalidad" => ArrayHelper::map($arr_modalidad, "id", "name"),
                    "arr_descuento" => ArrayHelper::map($arr_descuento, "id", "name"),
                    "arr_item" => ArrayHelper::map(array_merge(["id" => "0", "name" => "Seleccionar"], $resp_item), "id", "name"), //ArrayHelper::map($resp_item, "id", "name"),
                    "int_id" => $inte_id,
                    "per_id" => $per_id,
                    "arr_empresa" => ArrayHelper::map($empresa, "id", "value"),
                    "arr_convenio_empresa" => ArrayHelper::map($arr_convempresa, "id", "name"),
        ]);
    }

    public function actionSave() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $envi_correo = 0;
        $es_nacional = " ";
        $num_secuencia = "0";
        $valida = " ";
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = base64_decode($data["persona_id"]);
        }
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_facturacion;
        $transaction = $con->beginTransaction();
        $transaction1 = $con1->beginTransaction();
        try {
            $titulo_archivo = $data["arc_doc_titulo"];
            $dni_archivo = $data["arc_doc_dni"];
            $certvota_archivo = $data["arc_doc_certvota"];
            $foto_archivo = $data["arc_doc_foto"];
            $es_extranjero = $data["arc_extranjero"];
            $es_nacional = $data["arc_nacional"];
            $beca = $data["beca"];
            $descuento = $data["descuento_id"];
            $marca_desc = $data["marcadescuento"];

            $dataNombres = $data["nombres_fac"];
            $dataApellidos = $data["apellidos_fac"];
            $dataTipDNI = $data["tipo_DNI"];
            $dataDNI = $data["dni_fac"];
            $dataDireccion = $data["dir_fac"];
            $dataTelefono = $data["tel_fac"];
            $convenio = $data["cemp_id"];
            $correo_datafact = $data["correo_fac"];
            /* if ($dataConvenio == '0') {
              $convenio = null;
              } else {
              $convenio = $dataConvenio;
              } */
            if ($marca_desc == '1' && $marca_desc == '0') {
                $valida = 1;
            }
            if ($es_extranjero == "0" || $es_nacional == "0") {
                $certvota_archivo = 1;
            }
            $mod_interesado = new Interesado();
            $id_int = $mod_interesado->getInteresadoxIdPersona($per_id);
            if (!isset($id_int)) {
                throw new Exception('Error id interesado no creado.');
            }
            $nint_id = $data["ninteres"];
            $ming_id = $data["metodoing"];
            $mod_id = $data["modalidad"];
            $car_id = $data["carrera"];
            $emp_id = $data["emp_id"];
            $ite_id = $data["ite_id"];
            $precioGrado = $data["precio"];
            if ($emp_id > 1) {
                $mest_id = $car_id;
                $carrera_id = "";
            } else {
                $carrera_id = $car_id;
                $mest_id = "";
            }
            if ($nint_id < '1' and $ming_id < '1' and $mod_id < '1' and $car_id < '1' and $valida = 1) {
                throw new Exception('Debe seleccionar opciones de las listas.');
            }
            $sins_fechasol = date(Yii::$app->params["dateTimeByDefault"]);
            if ($emp_id > 1) {
                $ming_id = null; //Curso.
                $rsin_id = 1;
                $subirDocumentos = 0;
            } else {
                $rsin_id = 1; //Solicitud pendiente        
                $pre_observacion = null;
                $fec_preobservacion = null;
            }
            $interesado_id = $id_int;
            $subirDocumentos = $data["subirDocumentos"];
            $mod_solins = new SolicitudInscripcion();
            $errorprecio = 1;
            //Obtener el precio de la solicitud.
            if ($beca == "1") {
                $precio = 0;
            } else {
                //$resp_precio = $mod_solins->ObtenerPrecio($ming_id, $nint_id, $mod_id, $car_id);  //hasta el 9 de diciembre/2018.
                $resp_precio = $mod_solins->ObtenerPrecioXitem($ite_id);
                if ($resp_precio) {
                    if ($nint_id < 3) { //GViteri: para grado y posgrado los items que corresponden a inscripción, está abierto la caja de texto hasta un valor tope.                       
                        if ($nint_id == 1) {
                            $ming_id = null;
                        }
                        if ($ite_id == 155 or $ite_id == 156 or $ite_id == 157 or $ite_id == 10) {
                            $resp_precios_maximos = $mod_solins->ValidarPrecioXitem($ite_id);
                            if ($resp_precios_maximos) {
                                if ($precioGrado > $resp_precios_maximos["precio_mat"] or $precioGrado < $resp_precios_maximos["precio_ins"]) {
                                    $mensaje = 'El precio digitado debe estar entre ' . $resp_precios_maximos["precio_ins"] . ' y ' . $resp_precios_maximos["precio_mat"];
                                    $errorprecio = 0;
                                }
                            }
                            $precio = $precioGrado;
                        } else {
                            $precio = $resp_precio['precio'];
                        }
                    } else {
                        $precio = $resp_precio['precio'];
                    }
                } else {
                    $mensaje = 'No existe registrado ningún precio para la unidad, modalidad y método de ingreso seleccionada.';
                    $errorprecio = 0;
                }
            }
            $observacion = ucwords(mb_strtolower($data["observacion"]));
            if ($errorprecio != 0) {
                //Validar que no exista el registro en solicitudes.                    
                $resp_valida = $mod_solins->Validarsolicitud($interesado_id, $nint_id, $ming_id, $car_id);
                if (empty($resp_valida['existe'])) {
                    $num_secuencia = Secuencias::nuevaSecuencia($con1, $emp_id, 1, 1, 'SOL');
                    $mod_solins->num_solicitud = $num_secuencia;
                    $mod_solins->int_id = $interesado_id;
                    $mod_solins->uaca_id = $nint_id;
                    $mod_solins->mod_id = $mod_id;
                    $mod_solins->ming_id = $ming_id;
                    $mod_solins->eaca_id = $carrera_id;
                    $mod_solins->mest_id = $mest_id;
                    $mod_solins->rsin_id = $rsin_id;
                    $mod_solins->emp_id = $emp_id;
                    $mod_solins->sins_fecha_solicitud = $sins_fechasol;
                    $mod_solins->sins_fecha_preaprobacion = $fec_preobservacion;
                    $mod_solins->sins_fecha_aprobacion = null;
                    $mod_solins->sins_fecha_reprobacion = null;
                    $mod_solins->sins_preobservacion = $pre_observacion;
                    $mod_solins->sins_observacion = "";
                    $mod_solins->sins_estado = "1";
                    $mod_solins->sins_estado_logico = "1";
                    $mod_solins->sins_usuario_ingreso = $usu_id;
                    $mod_solins->sins_observacion_creasolicitud = $observacion;
                    if ($beca == "1") {
                        $mod_solins->sins_beca = "1";
                    }
                    if ($convenio > "0") {
                        $mod_solins->cemp_id = $convenio;
                    }
                    if ($subirDocumentos == 0) {
                        $mod_solins->save();
                        $id_sins = $mod_solins->sins_id;
                        if (!$mod_solins->crearDatosFacturaSolicitud($id_sins, ucwords(strtolower($dataNombres)), ucwords(strtolower($dataApellidos)), $dataTipDNI, $dataDNI, ucwords(strtolower($dataDireccion)), $dataTelefono, $correo_datafact)) {
                            throw new Exception('Problemas al registrar Datos a Facturar.');
                        }
                    }
                } else {
                    //Solicitud ya se encuentra creada.
                    throw new Exception('Ya se encuentra creada una solicitud con los mismos datos.');
                }
                $mod_ordenpago = new OrdenPago();
                //Se verifica si seleccionó descuento.
                $val_descuento = 0;
                if (!empty($descuento)) {
                    $modDescuento = new DetalleDescuentoItem();
                    $respDescuento = $modDescuento->consultarValdctoItem($descuento);
                    if ($respDescuento) {
                        if ($precio == 0) {
                            $val_descuento = 0;
                        } else {
                            if ($respDescuento["ddit_tipo_beneficio"] == 'P') {
                                $val_descuento = ($precio * ($respDescuento["ddit_porcentaje"])) / 100;
                            } else {
                                $val_descuento = $respDescuento["ddit_valor"];
                            }
                            //Insertar solicitud descuento
                            if ($val_descuento > 0) {
                                $resp_SolicDcto = $mod_ordenpago->insertarSolicDscto($id_sins, $descuento, $precio, $respDescuento["ddit_porcentaje"], $respDescuento["ddit_valor"]);
                            }
                        }
                    }
                }
                //Generar la orden de pago con valor correspondiente. Buscar precio para orden de pago.                                     
                if ($precio == 0) {
                    $estadopago = 'S';
                } else {
                    $estadopago = 'P';
                }
                $val_total = $precio - $val_descuento;
                $resp_opago = $mod_ordenpago->insertarOrdenpago($id_sins, null, $val_total, 0, $val_total, $estadopago, $usu_id);
                if ($resp_opago) {
                    //insertar desglose del pago                                    
                    $fecha_ini = date(Yii::$app->params["dateByDefault"]);
                    $resp_dpago = $mod_ordenpago->insertarDesglosepago($resp_opago, $ite_id, $val_total, 0, $val_total, $fecha_ini, null, $estadopago, $usu_id);
                    if ($resp_dpago) {
                        $exito = 1;
                    }
                }
            }
            if ($exito) {
                $transaction->commit();
                $transaction1->commit();

                //Envío de correo con formas de pago.                    
                $informacion_interesado = $mod_ordenpago->datosBotonpago($resp_opago, $emp_id);
                $link = Url::base(true) . "/formbotonpago/btnpago?ord_pago=" . base64_encode($resp_opago);
                $link_paypal = Url::base(true) . "/pago/pypal?ord_pago=" . base64_encode($resp_opago);
                $link1 = Url::base(true);
                $pri_nombre = $informacion_interesado['nombres'];
                $pri_apellido = $informacion_interesado['apellidos'];
                $correo = $informacion_interesado['email'];
                $nombres = $pri_nombre . " " . $pri_apellido;
                $curso = $informacion_interesado['curso'];
                $preciocurso = $resp_precio['precio'];
                $identificacion = $informacion_interesado['identificacion'];
                $telefono = $informacion_interesado['telefono'];
                if ($nint_id == 3) {
                    $metodo = 'el curso ' . $curso;
                } else {
                    $metodo = $resp_precio['nombre_metodo_ingreso'];
                }
                $carrera = $informacion_interesado["carrera"];
                $tipoDNI = ((SolicitudInscripcion::$arr_DNI[$dataTipDNI]) ? SolicitudInscripcion::$arr_DNI[$dataTipDNI] : SolicitudInscripcion::$arr_DNI["3"]);
                /* Obtención de datos de la factura */
                $respDatoFactura = $mod_solins->consultarDatosfacturaxIdsol($id_sins);

                $resp_sol = $mod_solins->Obtenerdatosolicitud($id_sins);
                //Se obtiene el curso para luego registrarlo.
                if ($resp_sol) {
                    $mod_persona = new Persona();
                    $resp_persona = $mod_persona->consultaPersonaId($per_id);
                    $correo = $resp_persona["usu_user"];
                    $apellidos = $resp_persona["per_pri_apellido"];
                    $nombres = $resp_persona["per_pri_nombre"];
                    $link = "http://www.uteg.edu.ec";
                    $modalidad = ($resp_sol["nombre_modalidad"]);
                    $unidad_academica = ($resp_sol["nombre_nivel_interes"]);

                    if ($resp_sol["nivel_interes"] == 1) {  //Grado
                        switch ($resp_sol["mod_id"]) {
                            case 1:
                                $file1 = Url::base(true) . "/files/Bienvenida UTEG ONLINE.pdf";
                                $rutaFile = array($file1);
                                break;
                            case 2:
                                $file1 = Url::base(true) . "/files/BienvenidaPresencial.pdf";
                                $rutaFile = array($file1);
                                break;
                            case 3:
                                $file1 = Url::base(true) . "/files/BienvenidaSemiPresencial.pdf";
                                $rutaFile = array($file1);
                                break;
                        }
                    } else {
                        if ($resp_sol["nivel_interes"] == 2) {   //Posgrado
                            $file1 = Url::base(true) . "/files/BienvenidaPosgrado.pdf";
                            $rutaFile = array($file1);
                        }
                    }
                    //SE COMENTA NO ENVIE CORREO EN SOLICITUD DE INSCRIPCION
                    /* if ($resp_sol["nivel_interes"] == 1) {
                      $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                      $asunto = Yii::t("interesado", "UTEG - Registration Online");
                      $body = Utilities::getMailMessage("Applicantrecord", array("[[nombre]]" => $nombres, "[[apellido]]" => $apellidos, "[[modalidad]]" => $modalidad, "[[link]]" => $link), Yii::$app->language);
                      $bodycolecturia = Utilities::getMailMessage("Approvedapplicationcollected", array("[[nombres_completos]]" => $nombres . " " . $apellidos, "[[modalidad]]" => $modalidad, "[[unidad]]" => $unidad_academica, "[[nombre]]" => $respDatoFactura["sdfa_nombres"], "[[apellido]]" => $respDatoFactura["sdfa_apellidos"], "[[identificacion]]" => $respDatoFactura["sdfa_dni"], "[[tipoDNI]]" => $respDatoFactura["sdfa_tipo_dni"], "[[direccion]]" => $respDatoFactura["sdfa_direccion"], "[[telefono]]" => $respDatoFactura["sdfa_telefono"]), Yii::$app->language);
                      $bodyadmision = Utilities::getMailMessage("Paidadmissions", array("[[nombre]]" => $nombres, "[[apellido]]" => $apellidos, "[[correo]]" => $correo, "[[identificacion]]" => $identificacion, "[[tipoDNI]]" => $tipoDNI, "[[modalidad]]" => $modalidad, "[[unidad]]" => $unidad_academica, "[[telefono]]" => $telefono), Yii::$app->language);
                      // if (!empty($rutaFile)) {
                      //     Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $apellidos . " " . $nombres], $asunto, $body, $rutaFile);
                      // } else {
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $apellidos . " " . $nombres], $asunto, $body);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["colecturia"] => "Colecturia"], $asunto, $bodycolecturia);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $bodyadmision);
                      } else {
                      $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                      $asunto = Yii::t("interesado", "UTEG - Registration Online");
                      $body = Utilities::getMailMessage("Paidinterested", array("[[nombre]]" => $nombres, "[[metodo]]" => $metodo, "[[precio]]" => $val_total, "[[link]]" => $link, "[[link1]]" => $link1, "[[link_pypal]]" => $link_paypal), Yii::$app->language);
                      $bodyadmision = Utilities::getMailMessage("Paidadmissions", array("[[nombre]]" => $pri_nombre, "[[apellido]]" => $pri_apellido, "[[correo]]" => $correo, "[[identificacion]]" => $identificacion, "[[tipoDNI]]" => $tipoDNI, "[[curso]]" => $curso, "[[telefono]]" => $telefono), Yii::$app->language);
                      $bodycolecturia = Utilities::getMailMessage("Approvedapplicationcollected", array("[[nombres_completos]]" => $nombres, "[[metodo]]" => $metodo, "[[nombre]]" => $respDatoFactura["sdfa_nombres"], "[[apellido]]" => $respDatoFactura["sdfa_apellidos"], "[[identificacion]]" => $respDatoFactura["sdfa_dni"], "[[tipoDNI]]" => $respDatoFactura["sdfa_tipo_dni"], "[[direccion]]" => $respDatoFactura["sdfa_direccion"], "[[telefono]]" => $respDatoFactura["sdfa_telefono"]), Yii::$app->language);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $pri_apellido . " " . $pri_nombre], $asunto, $body);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $bodyadmision);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $bodyadmision);
                      Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["colecturia"] => "Colecturia"], $asunto, $bodycolecturia);
                      } */
                }

                //$num_secuencia;secuencia que se debe retornar
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. Por favor verifique su correo."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            } else {
                $transaction->rollback();
                $transaction1->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
        } catch (Exception $ex) {
            $transaction->rollback();
            $transaction1->rollback();
            $message = array(
                "wtmessage" => $ex->getMessage(),
                "title" => Yii::t('jslang', 'Error.' . $mensaje),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
        }
    }

    public function actionUpdate() {
        
    }

    public function actionSubirdocumentos() {
        $sol_id = base64_decode($_GET['id_sol']);
        $sol_model = new SolicitudInscripcion();
        $datosSolicitud = $sol_model->consultarInteresadoPorSol_id($sol_id);
        return $this->render('subirDocumentos', [
                    "cliente" => $datosSolicitud['per_apellidos'] . ' ' . $datosSolicitud['per_nombres'],
                    "solicitud" => $datosSolicitud['sins_id'],
                    "txth_extranjero" => $datosSolicitud['per_nac_ecuatoriano'],
                    "per_id" => $datosSolicitud['per_id'],
                    "sins_id" => $datosSolicitud['sins_id'],
                    "int_id" => $datosSolicitud['int_id'],
                    "beca" => $datosSolicitud['sins_beca'],
                    "cemp_id" => $datosSolicitud['cemp_id'],
                    "num_solicitud" => $datosSolicitud['num_solicitud'],
                    "datos" => $datosSolicitud,
        ]);
    }

    public function actionActualizardocumentos() {
        $sol_id = base64_decode($_GET['id_sol']);
        $sol_model = new SolicitudInscripcion();
        $datosSolicitud = $sol_model->consultarInteresadoPorSol_id($sol_id);
        return $this->render('subirDocumentos', [
                    "cliente" => $datosSolicitud['per_apellidos'] . ' ' . $datosSolicitud['per_nombres'],
                    "solicitud" => $datosSolicitud['sins_id'],
                    "txth_extranjero" => $datosSolicitud['per_nac_ecuatoriano'],
                    "per_id" => $datosSolicitud['per_id'],
                    "sins_id" => $datosSolicitud['sins_id'],
                    "int_id" => $datosSolicitud['int_id'],
                    "beca" => $datosSolicitud['sins_beca'],
                    "cemp_id" => $datosSolicitud['cemp_id'],
                    "num_solicitud" => $datosSolicitud['num_solicitud'],
                    "datos" => $datosSolicitud,
        ]);
    }

    public function actionDescargafactura() {
        $nombreZip = "facturas_" . time();
        $content_type = Utilities::mimeContentType($nombreZip . ".zip");
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombreZip . ".zip");
        header('Cache-Control: max-age=0');

        // se deben zippear 2 files el xml y el pdf
        $arr_files = array(
            array("ruta" => Yii::$app->basePath . "/uploads/ficha/silueta_default.png",
                "name" => basename(Yii::$app->basePath . "/uploads/ficha/silueta_default.png")),
            array("ruta" => Yii::$app->basePath . "/uploads/ficha/Silueta-opc-4.png",
                "name" => basename(Yii::$app->basePath . "/uploads/ficha/Silueta-opc-4.png")),
        );
        $tmpDir = Utilities::zipFiles($nombreZip, $arr_files);
        $file = file_get_contents($tmpDir);
        Utilities::removeTemporalFile($tmpDir);
        echo $file;
        exit();
    }

    public function actionSavedocumentos() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($_SESSION['persona_solicita'] != '') {// tomar el de parametro)
                $per_id = base64_decode($_SESSION['persona_solicita']);
            } else {
                unset($_SESSION['persona_ingresa']);
                $per_id = Yii::$app->session->get("PB_perid");
            }
            //$per_id = base64_decode($data['persona_id']);
            $sins_id = base64_decode($data["sins_id"]);
            $interesado_id = base64_decode($data["interesado_id"]);
            $es_extranjero = base64_decode($data["arc_extranjero"]);
            $cemp_id = $data["cemp_id"];
            $beca = base64_decode($data["beca"]);
            $uaca_id = $data["uaca_id"];
            $observacion = ucwords(mb_strtolower($data["oserva"]));
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $titulo_archivo = "";
                if (isset($data["arc_doc_titulo"]) && $data["arc_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                }
                $dni_archivo = "";
                if (isset($data["arc_doc_dni"]) && $data["arc_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                }
                $certvota_archivo = "";
                if (isset($data["arc_doc_certvota"]) && $data["arc_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
                }
                $foto_archivo = "";
                if (isset($data["arc_doc_foto"]) && $data["arc_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                }
                $beca_archivo = "";
                if (isset($data[""]) && $data[""] != "") {
                    $arrIm = explode(".", basename($data[""]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                }
                if (isset($data["arc_doc_beca"]) && $data["arc_doc_beca"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_beca"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $beca_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_beca_per_" . $per_id . "." . $typeFile;
                }
                /* $certmate_archivo = "";
                  if (isset($data["arc_doc_certmat"]) && $data["arc_doc_certmat"] != "") {
                  $arrIm = explode(".", basename($data["arc_doc_certmat"]));
                  $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                  $certmate_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
                  } */


                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $carta_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                }
                $curriculum_archivo = "";
                if (isset($data["arc_doc_curri"]) && $data["arc_doc_curri"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_curri"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $curriculum_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_hojavida_per_" . $per_id . "." . $typeFile;
                }
            }
        }
        $con = \Yii::$app->db_captacion;
        $transaction = $con->beginTransaction();
        //$timeSt = time();
        $timeSt = date(Yii::$app->params["dateByDefault"]);
        try {
            if (isset($data["arc_doc_titulo"]) && $data["arc_doc_titulo"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_titulo"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $titulo_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                $titulo_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $titulo_archivo, $timeSt);
                if ($titulo_archivo === FALSE)
                    throw new Exception('Error doc Titulo no renombrado.');
            }
            if (isset($data["arc_doc_dni"]) && $data["arc_doc_dni"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_dni"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dni_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                $dni_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $dni_archivo, $timeSt);
                if ($dni_archivo === FALSE)
                    throw new Exception('Error doc Dni no renombrado.');
            }
            if (isset($data["arc_doc_certvota"]) && $data["arc_doc_certvota"] != "") {
              $arrIm = explode(".", basename($data["arc_doc_certvota"]));
              $typeFile = strtolower($arrIm[count($arrIm) - 1]);
              $certvota_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
              $certvota_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $certvota_archivo, $timeSt);
              if ($certvota_archivo === FALSE)
              throw new Exception('Error doc certificado vot. no renombrado.');
            } 
            if (isset($data["arc_doc_foto"]) && $data["arc_doc_foto"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_foto"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $foto_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                $foto_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $foto_archivo, $timeSt);
                if ($foto_archivo === FALSE)
                    throw new Exception('Error doc Foto no renombrado.');
            }
            if (isset($data["arc_doc_beca"]) && $data["arc_doc_beca"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_beca"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $beca_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_beca_per_" . $per_id . "." . $typeFile;
                $beca_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $beca_archivo, $timeSt);
                if ($beca_archivo === FALSE)
                    throw new Exception('Error doc Beca no renombrado.');
            }
            /* if (isset($data["arc_doc_certmat"]) && $data["arc_doc_certmat"] != "") {
              $arrIm = explode(".", basename($data["arc_doc_certmat"]));
              $typeFile = strtolower($arrIm[count($arrIm) - 1]);
              $certmate_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
              $certmate_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $certmate_archivo, $timeSt);
              if ($certmate_archivo === FALSE)
              throw new Exception('Error doc certificado materia no renombrado.');
              } */
            if ($cemp_id > 0) {
                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $convenio_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                    $convenio_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $convenio_archivo, $timeSt);
                    if ($convenio_archivo === FALSE)
                        throw new Exception('Error doc carta convenio no renombrado.');
                }
            }
            if (isset($data["arc_doc_curri"]) && $data["arc_doc_curri"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_curri"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $curriculum_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_hojavida_per_" . $per_id . "." . $typeFile;
                $curriculum_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $curriculum_archivo, $timeSt);
                if ($curriculum_archivo === FALSE)
                    throw new Exception('Error doc curriculum no renombrado.');
            }
            if (!empty($convenio_archivo) && $cemp_id > 0) {
                $mod_solinsxdoc8 = new SolicitudinsDocumento();
                $mod_solinsxdoc8->sins_id = $sins_id;
                $mod_solinsxdoc8->int_id = $interesado_id;
                $mod_solinsxdoc8->dadj_id = 8;
                $mod_solinsxdoc8->sdoc_archivo = $convenio_archivo;
                $mod_solinsxdoc8->sdoc_observacion = $observacion;
                $mod_solinsxdoc8->sdoc_estado = "1";
                $mod_solinsxdoc8->sdoc_estado_logico = "1";
                if (!$mod_solinsxdoc8->save()) {
                    throw new Exception('Error documento aceptacion no creado.');
                }
            }
            if ((($uaca_id == 1) && /* !empty($titulo_archivo) && */!empty($dni_archivo) && !empty($foto_archivo)) or ( !empty($curriculum_archivo) /* && !empty($titulo_archivo) */ && !empty($dni_archivo) && !empty($foto_archivo) && ($uaca_id == "2"))) {
                $mod_solinsxdoc1 = new SolicitudinsDocumento();
                //1-Título, 2-DNI,3-Cert votación, 4-Foto, 5-Doc-Beca  
                if (isset($titulo_archivo)) {
                    $mod_solinsxdoc1->sins_id = $sins_id;
                    $mod_solinsxdoc1->int_id = $interesado_id;
                    $mod_solinsxdoc1->dadj_id = 1;
                    $mod_solinsxdoc1->sdoc_archivo = $titulo_archivo;
                    $mod_solinsxdoc1->sdoc_observacion = $observacion;
                    $mod_solinsxdoc1->sdoc_estado = "1";
                    $mod_solinsxdoc1->sdoc_estado_logico = "1";
                    if (!$mod_solinsxdoc1->save()) {
                        throw new Exception('Error doc titulo no creado.');
                    }
                }

                $mod_solinsxdoc2 = new SolicitudinsDocumento();
                $mod_solinsxdoc2->sins_id = $sins_id;
                $mod_solinsxdoc2->int_id = $interesado_id;
                $mod_solinsxdoc2->dadj_id = 2;
                $mod_solinsxdoc2->sdoc_archivo = $dni_archivo;
                $mod_solinsxdoc2->sdoc_observacion = $observacion;
                $mod_solinsxdoc2->sdoc_estado = "1";
                $mod_solinsxdoc2->sdoc_estado_logico = "1";

                if ($mod_solinsxdoc2->save()) {
                    $mod_solinsxdoc3 = new SolicitudinsDocumento();
                    $mod_solinsxdoc3->sins_id = $sins_id;
                    $mod_solinsxdoc3->int_id = $interesado_id;
                    $mod_solinsxdoc3->dadj_id = 4;
                    $mod_solinsxdoc3->sdoc_archivo = $foto_archivo;
                    $mod_solinsxdoc3->sdoc_observacion = $observacion;
                    $mod_solinsxdoc3->sdoc_estado = "1";
                    $mod_solinsxdoc3->sdoc_estado_logico = "1";

                    if ($mod_solinsxdoc3->save()) {
                        if ($es_extranjero == "1" or ( empty($es_extranjero))) {
                          $mod_solinsxdoc4 = new SolicitudinsDocumento();
                          $mod_solinsxdoc4->sins_id = $sins_id;
                          $mod_solinsxdoc4->int_id = $interesado_id;
                          $mod_solinsxdoc4->dadj_id = 3;
                          $mod_solinsxdoc4->sdoc_archivo = $certvota_archivo;
                          $mod_solinsxdoc4->sdoc_observacion = $observacion;
                          $mod_solinsxdoc4->sdoc_estado = "1";
                          $mod_solinsxdoc4->sdoc_estado_logico = "1";
                          if (!$mod_solinsxdoc4->save()) {
                          throw new Exception('Error doc certvot no creado.');
                          }
                        } 
                        if ($beca == "1") {
                            $mod_solinsxdoc5 = new SolicitudinsDocumento();
                            $mod_solinsxdoc5->sins_id = $sins_id;
                            $mod_solinsxdoc5->int_id = $interesado_id;
                            $mod_solinsxdoc5->dadj_id = 5;
                            $mod_solinsxdoc5->sdoc_archivo = $beca_archivo;
                            $mod_solinsxdoc5->sdoc_observacion = $observacion;
                            $mod_solinsxdoc5->sdoc_estado = "1";
                            $mod_solinsxdoc5->sdoc_estado_logico = "1";
                            if (!$mod_solinsxdoc5->save()) {
                                throw new Exception('Error doc beca no creado.');
                            }
                        }
                        if ($uaca_id == "2") {
                            //\app\models\Utilities::putMessageLogFile('sins_id ' . $sins_id);
                            /* if (!empty($certmate_archivo)) {
                              $mod_solinsxdoc6 = new SolicitudinsDocumento();
                              $mod_solinsxdoc6->sins_id = $sins_id;
                              $mod_solinsxdoc6->int_id = $interesado_id;
                              $mod_solinsxdoc6->dadj_id = 6;
                              $mod_solinsxdoc6->sdoc_archivo = $certmate_archivo;
                              $mod_solinsxdoc6->sdoc_observacion = $observacion;
                              $mod_solinsxdoc6->sdoc_estado = "1";
                              $mod_solinsxdoc6->sdoc_estado_logico = "1";
                              if (!$mod_solinsxdoc6->save()) {
                              throw new Exception('Error doc certificado materia no creado.');
                              }
                              } */
                            if (!empty($curriculum_archivo)) {
                                $mod_solinsxdoc7 = new SolicitudinsDocumento();
                                $mod_solinsxdoc7->sins_id = $sins_id;
                                $mod_solinsxdoc7->int_id = $interesado_id;
                                $mod_solinsxdoc7->dadj_id = 7;
                                $mod_solinsxdoc7->sdoc_archivo = $curriculum_archivo;
                                $mod_solinsxdoc7->sdoc_observacion = $observacion;
                                $mod_solinsxdoc7->sdoc_estado = "1";
                                $mod_solinsxdoc7->sdoc_estado_logico = "1";
                                if (!$mod_solinsxdoc7->save()) {
                                    throw new Exception('Error doc curriculum no creado.');
                                }
                                $exito = 1;
                            } else {
                                throw new Exception('Tiene que subir curriculum.');
                            }
                        } else {
                            $exito = 1;
                        }
                    } else {
                        throw new Exception('Error doc foto no creado.');
                    }
                } else {
                    throw new Exception('Error doc dni no creado.');
                }
            } else {
                throw new Exception('Tiene que subir todos los documentos.');
            }
            if ($exito) {
                $transaction->commit();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
        } catch (Exception $ex) {
            $transaction->rollback();
            $message = array(
                "wtmessage" => $ex->getMessage(),
                "title" => Yii::t('jslang', 'Error'),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
        }
    }

    public function actionUpdatedocumentos() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($_SESSION['persona_solicita'] != '') {// tomar el de parametro)
                $per_id = base64_decode($_SESSION['persona_solicita']);
            } else {
                unset($_SESSION['persona_ingresa']);
                $per_id = Yii::$app->session->get("PB_perid");
            }
            //$per_id = base64_decode($data['persona_id']);
            $sins_id = base64_decode($data["sins_id"]);
            $cemp_id = $data["cemp_id"];
            $interesado_id = base64_decode($data["interesado_id"]);
            $es_extranjero = base64_decode($data["arc_extranjero"]);
            $beca = base64_decode($data["beca"]);
            $observacion = ucwords(mb_strtolower($data["oserva"]));
            $uaca_id = $data["uaca_id"];
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/" . $data["name_file"] . "_per_" . $per_id . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $titulo_archivo = "";
                if (isset($data["arc_doc_titulo"]) && $data["arc_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                }
                $dni_archivo = "";
                if (isset($data["arc_doc_dni"]) && $data["arc_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                }
                $certvota_archivo = "";
                  if (isset($data["arc_doc_certvota"]) && $data["arc_doc_certvota"] != "") {
                  $arrIm = explode(".", basename($data["arc_doc_certvota"]));
                  $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                  $certvota_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
                } 
                $foto_archivo = "";
                if (isset($data["arc_doc_foto"]) && $data["arc_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                }
                $beca_archivo = "";
                if (isset($data["arc_doc_beca"]) && $data["arc_doc_beca"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_beca"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $beca_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_beca_per_" . $per_id . "." . $typeFile;
                }
                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $carta_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                }
                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $carta_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                }
                /* $certmate_archivo = "";
                  if (isset($data["arc_doc_certmat"]) && $data["arc_doc_certmat"] != "") {
                  $arrIm = explode(".", basename($data["arc_doc_certmat"]));
                  $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                  $certmate_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
                  } */
                $curriculum_archivo = "";
                if (isset($data["arc_doc_curri"]) && $data["arc_doc_curri"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_curri"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $curriculum_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_hojavida_per_" . $per_id . "." . $typeFile;
                }
            }
        }
        $con = \Yii::$app->db_captacion;
        $transaction = $con->beginTransaction();
        //$timeSt = time();
        $timeSt = date(Yii::$app->params["dateByDefault"]);
        try {
            if (isset($data["arc_doc_titulo"]) && $data["arc_doc_titulo"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_titulo"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $titulo_archivoOld = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_titulo_per_" . $per_id . "." . $typeFile;
                $titulo_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $titulo_archivoOld, $timeSt);
                if ($titulo_archivo === false)
                    throw new Exception('Error doc Titulo no renombrado.');
            }
            if (isset($data["arc_doc_dni"]) && $data["arc_doc_dni"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_dni"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dni_archivoOld = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_dni_per_" . $per_id . "." . $typeFile;
                $dni_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $dni_archivoOld, $timeSt);
                if ($dni_archivo === false)
                    throw new Exception('Error doc Dni no renombrado.');
            }
            if (isset($data["arc_doc_certvota"]) && $data["arc_doc_certvota"] != "") {
              $arrIm = explode(".", basename($data["arc_doc_certvota"]));
              $typeFile = strtolower($arrIm[count($arrIm) - 1]);
              $certvota_archivoOld = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certvota_per_" . $per_id . "." . $typeFile;
              $certvota_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $certvota_archivoOld, $timeSt);
              if ($certvota_archivo === false)
              throw new Exception('Error doc certificado vot. no renombrado.');
            }
            if (isset($data["arc_doc_foto"]) && $data["arc_doc_foto"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_foto"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $foto_archivoOld = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_foto_per_" . $per_id . "." . $typeFile;
                $foto_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $foto_archivoOld, $timeSt);
                if ($foto_archivo === false)
                    throw new Exception('Error doc Foto no renombrado.');
            }
            if (isset($data["arc_doc_beca"]) && $data["arc_doc_beca"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_beca"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $beca_archivoOld = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_beca_per_" . $per_id . "." . $typeFile;
                $beca_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $beca_archivoOld, $timeSt);
                if ($beca_archivo === false)
                    throw new Exception('Error doc Beca no renombrado.');
            }
            if ($cemp_id > 0) {
                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $convenio_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                    $convenio_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $convenio_archivo, $timeSt);
                    if ($convenio_archivo === FALSE)
                        throw new Exception('Error doc carta convenio no renombrado.');
                }
            }
            //0996148261
            /* if (isset($data["arc_doc_certmat"]) && $data["arc_doc_certmat"] != "") {
              $arrIm = explode(".", basename($data["arc_doc_certmat"]));
              $typeFile = strtolower($arrIm[count($arrIm) - 1]);
              $certmate_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_certificado_per_" . $per_id . "." . $typeFile;
              \app\models\Utilities::putMessageLogFile('adjuntar cert materias:' . $certmate_archivo);
              $certmate_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $certmate_archivo, $timeSt);
              \app\models\Utilities::putMessageLogFile('despues de adjuntar cert materias:' . $certmate_archivo);
              if ($certmate_archivo === FALSE)
              throw new Exception('Error doc certificado materia no renombrado.');
              } */
            if ($cemp_id > 0) {
                if (isset($data["arc_doc_convenio"]) && $data["arc_doc_convenio"] != "") {
                    $arrIm = explode(".", basename($data["arc_doc_convenio"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $convenio_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_carta_convenio_per_" . $per_id . "." . $typeFile;
                    $convenio_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $convenio_archivo, $timeSt);
                    if ($convenio_archivo === FALSE)
                        throw new Exception('Error doc carta convenio no renombrado.');
                }
            }
            if (isset($data["arc_doc_curri"]) && $data["arc_doc_curri"] != "") {
                $arrIm = explode(".", basename($data["arc_doc_curri"]));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $curriculum_archivo = Yii::$app->params["documentFolder"] . "solicitudinscripcion/" . $per_id . "/doc_hojavida_per_" . $per_id . "." . $typeFile;
                $curriculum_archivo = DocumentoAdjuntar::addLabelTimeDocumentos($sins_id, $curriculum_archivo, $timeSt);
                if ($curriculum_archivo === FALSE)
                    throw new Exception('Error doc curriculum no renombrado.');
            }
            if (!empty($titulo_archivo) && !empty($dni_archivo) && !empty($foto_archivo)) {
                if (!empty($titulo_archivo)) {
                    // Se inactiva los documentos anteriores
                    if (!DocumentoAdjuntar::desactivarDocumentosxSolicitud($sins_id))
                        throw new Exception('Error no se reemplazo files.');
                    $mod_solinsxdoc1 = new SolicitudinsDocumento();
                    //1-Título, 2-DNI,3-Cert votación, 4-Foto, 5-Doc-Beca  
                    if ($cemp_id > 0) {
                        $mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 8, $convenio_archivo, $observacion);
                    }
                    if ($mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 1, $titulo_archivo, $observacion)) {
                        if ($mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 2, $dni_archivo, $observacion)) {
                            if ($mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 4, $foto_archivo, $observacion)) {
                                if ($es_extranjero == "1" or ( empty($es_extranjero))) {
                                    /* if (!$mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 3, $certvota_archivo, $observacion)) {
                                      throw new Exception('Error doc certvot no creado.');
                                      } */
                                    if ($beca == "1") {
                                        if (!$mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 5, $beca_archivo, $observacion)) {
                                            throw new Exception('Error doc beca no creado.');
                                        }
                                    }
                                    //if ($uaca_id == "2") {
                                    //\app\models\Utilities::putMessageLogFile('cert curr:' . $curriculum_archivo);
                                    //if ($mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 6, $certmate_archivo, $observacion)) {
                                    //if (!$mod_solinsxdoc1->insertNewDocument($sins_id, $interesado_id, 7, $curriculum_archivo, $observacion)) {
                                    //   throw new Exception('Error doc curriculum no creado.');
                                    //}
                                    /* } else {
                                      throw new Exception('Error doc certificado materia no creado.');
                                      } */
                                    //}
                                }
                            } else {
                                throw new Exception('Error doc foto no creado.');
                            }
                        } else {
                            throw new Exception('Error doc dni no creado.');
                        }
                    } else {
                        throw new Exception('Error doc titulo no creado.' . $mensaje);
                    }
                    // se cambia a pendiente la solicitud para revision.                    
                    $mod_solinsxdoc1 = new SolicitudinsDocumento();
                    $solicitudInscripcion = SolicitudInscripcion::findOne($sins_id);
                    $solicitudInscripcion->rsin_id = 1;
                    if (!$solicitudInscripcion->save()) {
                        throw new Exception('Error al actualizar Solicitud.');
                    }
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    throw new Exception('Tiene que subir todos los documentos.Titulo:' /* . isset($data["arc_doc_titulo"]) . 'Persona:' . $per_id */);
                }
            } else {
                throw new Exception('Tiene que subir todos los documentos.Titulo:' /* . isset($data["arc_doc_titulo"]) . 'Persona:' . $per_id */);
            }
        } catch (Exception $ex) {
            $transaction->rollback();
            $message = array(
                "wtmessage" => $ex->getMessage(),
                "title" => Yii::t('jslang', 'Error'),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
        }
    }

    public function actionSaverevision() {
        $usuario = new Usuario();
        $security = new Security();
        $usergrol = new UsuaGrolEper();
        $per_sistema = @Yii::$app->session->get("PB_perid");
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $resultado = $data["resultado"];
            $observacion = ucwords(strtolower($data["observacion"]));
            $banderapreaprueba = $data["banderapreaprueba"];
            $sins_id = base64_decode($data["sins_id"]);
            $int_id = base64_decode($data["int_id"]);
            $per_id = base64_decode($data["per_id"]);
            $condicionesTitulo = $data["condicionestitulo"];
            $condicionesDni = $data["condicionesdni"];
            $condicionesConvi = $data["condicionconvi"];
            $titulo = $data["titulo"];
            $dni = $data["dni"];
            $certivot = $data["certi"];
            $convenio = $data["convi"];
            $emp_id = $data["empresa"];
            $rsin_id = base64_decode($data["estado_sol"]);
            $observarevisa = ucwords(strtolower($data["observarevisa"]));
            $cemp_id = $data["cemp_id"];
            $condicionesCerti = $data["condicioncerti"];
            $condicionesFoto = $data["condicionfoto"];
            $foto = $data["foto"];
            $condicionesCurriculum = $data["condicioncurriculum"];
            $curriculum = $data["curriculum"];

            $con = \Yii::$app->db_captacion;
            $transaction = $con->beginTransaction();
            $con2 = \Yii::$app->db_facturacion;
            $transaction2 = $con2->beginTransaction();
            $con3 = \Yii::$app->db_academico;
            $transaction3 = $con3->beginTransaction();
            try {
                $mod_Estudiante = new Estudiante();
                $mod_Modestuni = new ModuloEstudio();
                if ($rsin_id != 2) {
                    $mod_solins = new SolicitudInscripcion();
                    $mod_ordenpago = new OrdenPago();
                    //Verificar que se hayan subido los documentos en Uteg.        
                    if ($empresa == 1) {
                        $respNumDoc = $mod_solins->consultarDocumentostosxSol($sins_id);
                        $numDocumentos = $respNumDoc["numDocumentos"];
                    } else {
                        $numDocumentos = 1;
                    }
                    if ($numDocumentos > 0) {
                        $respusuario = $mod_solins->consultaDatosusuario($per_sistema);
                        if ($banderapreaprueba == 0) {  //etapa de Aprobación.                    
                            if ($resultado == 2) {
                                //consultar estado del pago.     
                                $resp_pago = $mod_ordenpago->consultaOrdenPago($sins_id);
                                if ($resp_pago["opag_estado_pago"] == 'S') {
                                    $respsolins = $mod_solins->apruebaSolicitud($sins_id, $resultado, $observacion, $observarevisa, $banderapreaprueba, $respusuario['usu_id']);
                                    if ($respsolins) {
                                        //Se genera id de aspirante y correo de bienvenida.                                
                                        $resp_encuentra = $mod_ordenpago->encuentraAdmitido($int_id, $sins_id);
                                        if ($resp_encuentra) {
                                            $asp = $resp_encuentra['adm_id'];
                                            $continua = 1;
                                        } else {
                                            //Se asigna al interesado como aspirante                                    
                                            $resp_asp = $mod_ordenpago->insertarAdmitido($int_id, $sins_id);
                                            if ($resp_asp) {
                                                $asp = $resp_asp;
                                                $continua = 1;
                                            }
                                        }
                                    }
                                    if ($continua == 1) {
                                        $resp_inte = $mod_ordenpago->actualizaEstadointeresado($int_id, $respusuario['usu_id']);
                                        if ($resp_inte) {
                                            //Se obtienen el método de ingreso y el nivel de interés según la solicitud.                                                
                                            $resp_sol = $mod_solins->Obtenerdatosolicitud($sins_id);
                                            //Se obtiene el curso para luego registrarlo.
                                            if ($resp_sol) {
                                                $mod_persona = new Persona();
                                                $resp_persona = $mod_persona->consultaPersonaId($per_id);
                                                //Modificar y activar clave de usuario con numero de cedula 
                                                if ($resp_sol["emp_id"] == 1) {
                                                    $usu_sha = $security->generateRandomString();
                                                    $usu_pass = base64_encode($security->encryptByPassword($usu_sha, $resp_persona["per_cedula"]));
                                                    $respUsu = $usuario->actualizarDataUsuario($usu_sha, $usu_pass, $resp_persona["usu_id"]);
                                                    // YA TIENE USUARIO GROL PERO CON ROL 30 DEBERIA MODIFICARSE A 37 SE DEBE ENVIAR EL USU_ID
                                                    if ($respUsu) {
                                                        $respUsugrol = $usergrol->actualizarRolEstudiante($resp_persona["usu_id"]);
                                                        if ($respUsugrol) {
                                                            // Guardar en tabla esdudiante                                                                                                                      
                                                            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                                                            // Consultar el estudiante si no ha sido creado
                                                            $resp_estudianteid = $mod_Estudiante->getEstudiantexperid($per_id);
                                                            if ($resp_estudianteid["est_id"] == "") {
                                                                $resp_estudiante = $mod_Estudiante->insertarEstudiante($per_id, null, null, $usu_autenticado, null, $fecha, null);
                                                            } else {
                                                                $resp_estudiante = $resp_estudianteid["est_id"];
                                                            }
                                                            if ($resp_estudiante) {
                                                                // Obtener el meun_id con lo con el uaca_id, mod_id y eaca_id, el est_id
                                                                $resp_mestuni = $mod_Modestuni->consultarModalidadestudiouni($resp_sol["nivel_interes"], $resp_sol["mod_id"], $resp_sol["eaca_id"]);
                                                                if ($resp_mestuni) {
                                                                    //consultar si no esta guardado en estudiante_carrera_programa
                                                                    $resp_estucarrera = $mod_Estudiante->consultarEstcarreraprogrma($resp_estudiante);
                                                                    if ($resp_estucarrera["ecpr_id"] == "") {
                                                                        // Guardar en tabla estudiante_carrera_programa
                                                                        $resp_estudcarreprog = $mod_Estudiante->insertarEstcarreraprog($resp_estudiante, $resp_mestuni["meun_id"], $fecha, $usu_autenticado, $fecha);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                $correo = $resp_persona["usu_user"];
                                                $apellidos = $resp_persona["per_pri_apellido"];
                                                $nombres = $resp_persona["per_pri_nombre"];
                                                //información del aspirante.
                                                $identi = $resp_persona["per_cedula"];
                                                $cel_fono = $resp_persona["per_celular"];
                                                $mail_asp = $resp_persona["per_correo"];

                                                $link = "http://www.uteg.edu.ec";
                                                $metodo_ingreso = $resp_sol["nombre_metodo_ingreso"];
                                                if ($resp_sol["metodo_ingreso"] == 1) {
                                                    $leyenda = "el curso de nivelación";
                                                }
                                                if ($resp_sol["metodo_ingreso"] == 2) {
                                                    $leyenda = "la preparación para el examen de admisión";
                                                }
                                                $modalidad = ($resp_sol["nombre_modalidad"]);

                                                if ($resp_sol["nivel_interes"] == 1) {  //Grado
                                                    switch ($resp_sol["mod_id"]) {
                                                        case 1:
                                                            $file1 = Url::base(true) . "/files/Bienvenida UTEG ONLINE.pdf";
                                                            $rutaFile = array($file1);
                                                            break;
                                                        case 2:
                                                            $file1 = Url::base(true) . "/files/BienvenidaPresencial.pdf";
                                                            $rutaFile = array($file1);
                                                            break;
                                                        case 3:
                                                            $file1 = Url::base(true) . "/files/BienvenidaSemiPresencial.pdf";
                                                            $rutaFile = array($file1);
                                                            break;
                                                    }
                                                } else {
                                                    if ($resp_sol["nivel_interes"] == 2) {   //Posgrado
                                                        if ($resp_sol["mod_id"] == 1) {
                                                            $file1 = Url::base(true) . "/files/CartaMaestriaOnlinekl.pdf";
                                                            $rutaFile = array($file1);
                                                        } else {
                                                            $file1 = Url::base(true) . "/files/BienvenidaPosgrado.pdf";
                                                            $rutaFile = array($file1);
                                                        }
                                                    }
                                                }
                                                $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                                                $asunto = Yii::t("interesado", "UTEG - Registration Online");
                                                $body = Utilities::getMailMessage("Applicantrecord", array("[[nombre]]" => $nombres, "[[apellido]]" => $apellidos, "[[modalidad]]" => $modalidad, "[[link]]" => $link), Yii::$app->language);
                                                // if (!empty($rutaFile)) {
                                                //     Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $apellidos . " " . $nombres], $asunto, $body, $rutaFile);
                                                // } else {
                                                if ($resp_sol["nivel_interes"] != 1) {
                                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $apellidos . " " . $nombres], $asunto, $body/* , $rutaFile */);
                                                    // }
                                                    Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
                                                }
                                                $exito = 1;
                                            }
                                        }
                                    }
                                } else {
                                    $mensaje = 'La solicitud se encuentra pendiente de pago.';
                                }
                            } else { //No aprueban la solicitud  
                                $respsolins = $mod_solins->apruebaSolicitud($sins_id, $resultado, $observacion, $observarevisa, $banderapreaprueba, $respusuario['usu_id']);
                                if ($respsolins) {
                                    $srec_etapa = "A";  //Aprobación                            
                                    //Grabar en tabla de solicitudes rechazadas.
                                    if ($titulo == 1) {
                                        $obs_rechazo = "No cumple condiciones de aceptación en título.";
                                        for ($c = 0; $c < count($condicionesTitulo); $c++) {
                                            $resp_rechtit = $mod_solins->Insertarsolicitudrechazada($sins_id, 1, $condicionesTitulo[$c], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                            if ($resp_rechtit) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }
                                    if ($dni == 1) {
                                        $obs_rechazo = "No cumple condiciones de aceptación en documento de identidad.";
                                        for ($a = 0; $a < count($condicionesDni); $a++) {
                                            $resp_rechdni = $mod_solins->Insertarsolicitudrechazada($sins_id, 2, $condicionesDni[$a], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                            if ($resp_rechdni) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }
                                    if ($certivot == 1) {
                                        $obs_rechazo = "No cumple condiciones de aceptación en certificado de votación.";
                                        for ($b = 0; $b < count($condicionesCerti); $b++) {
                                            $resp_rechcer = $mod_solins->Insertarsolicitudrechazada($sins_id, 3, $condicionesCerti[$b], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                            if ($resp_rechcer) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }                                    
                                    if ($foto == 1) {
                                        $obs_rechazofoto = "No cumple condiciones de aceptación en foto.";
                                        for ($d = 0; $d < count($condicionesFoto); $d++) {
                                            $resp_rechfoto = $mod_solins->Insertarsolicitudrechazada($sins_id, 4, $condicionesFoto[$d], $srec_etapa, $obs_rechazofoto, $respusuario['usu_id']);
                                            if ($resp_rechfoto) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }
                                    if ($curriculum == 1) {
                                        $obs_rechazocurriculum = "No cumple condiciones de aceptación en curriculum.";
                                        for ($f = 0; $f < count($condicionesCurriculum); $f++) {
                                            $resp_rechcurriculum = $mod_solins->Insertarsolicitudrechazada($sins_id, 7, $condicionesCurriculum[$f], $srec_etapa, $obs_rechazocurriculum, $respusuario['usu_id']);
                                            if ($resp_rechcurriculum) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }
                                    if ($convenio > 0) {
                                        $obs_rechazoconvenio = "No cumple condiciones de convenio.";
                                        for ($b = 0; $b < count($condicionesConvi); $b++) {
                                            $resp_rechcercon = $mod_solins->Insertarsolicitudrechazada($sins_id, 8, $condicionesConvi[$b], $srec_etapa, $obs_rechazoconvenio, $respusuario['usu_id']);
                                            if ($resp_rechcercon) {
                                                $ok = "1";
                                            } else {
                                                $ok = "0";
                                            }
                                        }
                                    }
                                    if ($ok == "1") {
                                        //Se envía correo.
                                        $mod_persona = new Persona();
                                        $resp_persona = $mod_persona->consultaPersonaId($per_id);
                                        $correo = $resp_persona["usu_user"];
                                        $pri_apellido = $resp_persona["per_pri_apellido"];
                                        $pri_nombre = $resp_persona["per_pri_nombre"];
                                        $nombre_completo = $resp_persona["per_pri_apellido"] . " " . $resp_persona["per_seg_apellido"] . " " . $resp_persona["per_pri_nombre"] . " " . $resp_persona["per_seg_nombre"];
                                        $estado = "NO APROBADA";
                                        //Obtener datos del rechazo.
                                        $resp_rechazo = $mod_solins->consultaSolicitudRechazada($sins_id, 'A');
                                        if ($resp_rechazo) {
                                            $obs_condicion = "";
                                            for ($r = 0; $r < count($resp_rechazo); $r++) {
                                                if ($obs_condicion <> $resp_rechazo[$r]['observacion']) {
                                                    $obs_condicion = $resp_rechazo[$r]['observacion'];
                                                    $obs_correo = $obs_correo . "<br/><b>" . $obs_condicion . ":</b><br/>" . "&nbsp;&nbsp;&nbsp;No " . $resp_rechazo[$r]['condicion'];
                                                } else {
                                                    $obs_correo = $obs_correo . "<br/>" . "&nbsp;&nbsp;&nbsp; No " . $resp_rechazo[$r]['condicion'];
                                                }
                                            }
                                        }
                                        // Se bloquea el correo de re probacion de solicitud
                                          /*$tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                                          $asunto = Yii::t("interesado", "UTEG - Registration Online");
                                          $body = Utilities::getMailMessage("Requestapplicantdenied", array("[[observacion]]" => $obs_correo), Yii::$app->language);
                                          $bodyadmision = Utilities::getMailMessage("Requestadmissions", array("[[nombre_aspirante]]" => $nombre_completo, "[[estado_solicitud]]" => $estado), Yii::$app->language);
                                          Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [$correo => $pri_apellido . " " . $pri_nombre], $asunto, $body);
                                          Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $body);
                                          Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $bodyadmision);
                                          Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $bodyadmision);*/
                                        $exito = 1;
                                    } else {
                                        $message = array
                                            ("wtmessage" => Yii::t("notificaciones", "No ha seleccionado condiciones de No Aprobado."), "title" =>
                                            Yii::t('jslang', 'Success'),
                                        );
                                    }
                                }
                            }
                        } else {  //Pre-Aprobación de la solicitud                
                            if ($resultado == 3) {
                                //Verificar que se hayan subido los documentos.
                                $respConsulta = $mod_solins->consultarDocumxSolic($sins_id);
                                if ($respConsulta['numDocumentos'] > 0) {
                                    $respsolins = $mod_solins->apruebaSolicitud($sins_id, $resultado, $observacion, $observarevisa, $banderapreaprueba, $respusuario['usu_id']);
                                    if ($respsolins) {
                                        $exito = 1;
                                    }
                                } else {
                                    $mensaje = 'No se han subido los documentos.';
                                }
                            } else {
                                if ($resultado == 4) {
                                    $respsolins = $mod_solins->apruebaSolicitud($sins_id, $resultado, $observacion, $observarevisa, $banderapreaprueba, $respusuario['usu_id']);
                                    if ($respsolins) {
                                        $srec_etapa = "P";  //Preaprobación                       
                                        //Grabar en tabla de solicitudes rechazadas.
                                        if ($titulo == 1) {
                                            $obs_rechazo = "No cumple condiciones de aceptación en título.";
                                            for ($c = 0; $c < count($condicionesTitulo); $c++) {
                                                $resp_rechtit = $mod_solins->Insertarsolicitudrechazada($sins_id, 1, $condicionesTitulo[$c], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                                if ($resp_rechtit) {
                                                    $ok = "1";
                                                } else {
                                                    $ok = "0";
                                                }
                                            }
                                        }
                                        if ($dni == 1) {
                                            $obs_rechazo = "No cumple condiciones de aceptación en documento de identidad.";
                                            for ($a = 0; $a < count($condicionesDni); $a++) {
                                                $resp_rechdni = $mod_solins->Insertarsolicitudrechazada($sins_id, 2, $condicionesDni[$a], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                                if ($resp_rechdni) {
                                                    $ok = "1";
                                                } else {
                                                    $ok = "0";
                                                }
                                            }
                                        }
                                        if ($certivot == 1) {
                                            $obs_rechazo = "No cumple condiciones de aceptación en certificado de votación.";
                                            for ($b = 0; $b < count($condicionesCerti); $b++) {
                                                $resp_rechcer = $mod_solins->Insertarsolicitudrechazada($sins_id, 3, $condicionesCerti[$b], $srec_etapa, $obs_rechazo, $respusuario['usu_id']);
                                                if ($resp_rechcer) {
                                                    $ok = "1";
                                                } else {
                                                    $ok = "0";
                                                }
                                            }
                                        }
                                    } else {
                                        $ok = "0";
                                    }
                                    if ($ok == "1") {
                                        $link = Url::base(true);
                                        $tituloMensaje = Yii::t("interesado", "UTEG - Registration Online");
                                        $asunto = Yii::t("interesado", "UTEG - Registration Online");
                                        $bodyadmision = Utilities::getMailMessage("Prereviewadmissions", array("[[link_asgard]]" => $link), Yii::$app->language);
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["admisiones"] => "Jefe"], $asunto, $bodyadmision);
                                        Utilities::sendEmail($tituloMensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["soporteEmail"] => "Soporte"], $asunto, $bodyadmision);
                                        $exito = 1;
                                    } else {
                                        $message = array
                                            ("wtmessage" => Yii::t("notificaciones", "No ha seleccionado condiciones de No Aprobado."), "title" =>
                                            Yii::t('jslang', 'Success'),
                                        );
                                    }
                                }
                            }
                        }
                        if ($exito) {
                            $transaction->commit();
                            $transaction2->commit();
                            $transaction3->commit();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "La información ha sido grabada."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        } else {
                            //$paso = 1;
                            $transaction->rollback();
                            $transaction2->rollback();
                            $transaction3->rollback();
                            if (empty($message)) {
                                $message = array
                                    (
                                    "wtmessage" => Yii::t("notificaciones", "Error al grabar. " . $mensaje), "title" =>
                                    Yii::t('jslang', 'Success'),
                                );
                            }
                            return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                        }
                    } else {
                        $transaction->rollback();
                        $transaction2->rollback();
                        $transaction3->rollback();
                        $message = array
                            (
                            "wtmessage" => Yii::t("notificaciones", "Solicitud no tiene los documentos cargados."), "title" =>
                            Yii::t('jslang', 'Success'),
                        );
                        return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    }
                } else {
                    $transaction->rollback();
                    $transaction2->rollback();
                    $transaction3->rollback();
                    $message = array
                        (
                        "wtmessage" => Yii::t("notificaciones", "Solicitud se encuentra Aprobada."), "title" =>
                        Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $transaction2->rollback();
                $transaction3->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }

    public function actionExpexcelsolicitudes() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");

        $arrHeader = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "User login"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Status"),
            financiero::t("Pagos", "Payment")
        );

        $modSolicitudes = new SolicitudInscripcion();
        $data = Yii::$app->request->get();

        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["estadoSol"] = $data['estadoSol'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["carrera"] = $data['carrera'];

        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $modSolicitudes->consultarSolicitudesReporte(array(), true);
        } else {
            $arrData = $modSolicitudes->consultarSolicitudesReporte($arrSearch, true);
        }

        $nameReport = admision::t("Solicitudes", "Request by Interested");
        
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfsolicitudes() {
        $report = new ExportFile();
        $this->view->title = admision::t("Solicitudes", "Request by Interested"); // Titulo del reporte

        $modSolicitudes = new SolicitudInscripcion();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["search"] = $data['search'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["estadoSol"] = $data['estadoSol'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["carrera"] = $data['carrera'];

        $arr_head = array(
            admision::t("Solicitudes", "Request #"),
            admision::t("Solicitudes", "Application date"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "First Names"),
            Yii::t("formulario", "Last Names"),
            Yii::t("formulario", "User login"),
            academico::t("Academico", "Academic unit"),
            academico::t("Academico", "Income Method"),
            academico::t("Academico", "Career/Program"),
            Yii::t("formulario", "Status"),
            financiero::t("Pagos", "Payment")
        );

        if (empty($arrSearch)) {
            $arr_body = $modSolicitudes->consultarSolicitudesReporte(array(), true);
        } else {
            $arr_body = $modSolicitudes->consultarSolicitudesReporte($arrSearch, true);
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arr_head,
                    'arr_body' => $arr_body
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionSaldo() {
        $sins_id = base64_decode($_GET['ids']);
        $int_id = base64_decode($_GET['int']);
        $per_id = base64_decode($_GET['perid']);
        $emp_id = base64_decode($_GET['empid']);
        $mod_solins = new SolicitudInscripcion();
        $personaData = $mod_solins->consultarInteresadoPorSol_id($sins_id);
        $mod_ordenpago = new OrdenPago();
        \app\models\Utilities::putMessageLogFile('unidad:' . $personaData["uaca_id"]);
        \app\models\Utilities::putMessageLogFile('modalidad:' . $personaData["mod_id"]);
        if (empty($personaData["ming_id"])) {
            $metodo = 0;
        } else {
            $metodo = $personaData["ming_id"];
        }
        \app\models\Utilities::putMessageLogFile('metodo:' . $metodo);
        $resp_item = $mod_ordenpago->consultarPrecioXotroItem($personaData["uaca_id"], $personaData["mod_id"], $metodo);
        return $this->render('saldo', [
                    "personaData" => $personaData,
                    "sins_id" => $sins_id,
                    "int_id" => $int_id,
                    "per_id" => $per_id,
                    "emp_id" => $emp_id,
                    "arr_item" => ArrayHelper::map(array_merge(["id" => "0", "name" => "Seleccionar"], $resp_item), "id", "name"),
        ]);
    }

}
