<?php

namespace app\controllers;

use Yii;
use app\models\Utilities;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
use app\modules\admision\models\Oportunidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademico;
use app\models\Pais;
use app\models\Provincia;
use app\models\Canton;
use app\models\EstadoCivil;
use app\models\TipoParentesco;
use app\models\PersonaContacto;
use app\modules\academico\models\MallaAcademica;
use app\models\InscripcionGrado;
use app\modules\admision\models\MetodoIngreso;
use app\modules\admision\models\ConvenioEmpresa;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
use app\modules\admision\Module as admision;
use app\models\ExportFile;

academico::registerTranslations();

class FormulariogradoController extends \app\components\CController {

    public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    private function financiamiento() {
        return [
            '1' => Yii::t("formulario", "Crédito directo"),
            '2' => Yii::t("formulario", "Crédito bancario"),
            '3' => Yii::t("formulario", "Beca"),
        ];
    }

        public function actionAspirantegrado() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model_grado = new InscripcionGrado();
        $unidad_model = new UnidadAcademica();
        $carrera_model = new EstudioAcademico();
        $moda_model = new Modalidad();
        $periodo_model = new PeriodoAcademico();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"]      = $data['search'];
            $arrSearch["periodo"]     = $data['periodo'];
            $arrSearch["unidad"]      = $data['unidad'];
            $arrSearch["carreras"]      = $data['carreras'];
            $arrSearch["modalidad"]   = $data['modalidad'];
            $model = $model_grado->consultaRegistroAdmisiongrado($arrSearch, 1);
            return $this->render('_aspirantegradogrid', [
                "model" => $model,
            ]);

        }else {
            $model = $model_grado->consultaRegistroAdmisiongrado(null, 1);
        }

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data['getmodalidades'])) {
                $modalidad = $carrera_model->consultarmodalidadxcarrera($data['carrera']);
                $message = array('modalidades' => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $unidad_model->consultarUnidadAcademicas();
        $arr_carrera = $carrera_model->consultarCarreraxunidad($arr_unidad[0]['id']);
        $arr_modalidad = $carrera_model->consultarmodalidadxcarrera($arr_carrera[0]['id']);
        $arr_periodo = $periodo_model->consultarPeriodosActivos();
        return $this->render('aspirantegrado_index', [
            "arr_unidad" => ArrayHelper::map($arr_unidad, "id", "name"),
            'arr_carrera' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_carrera), 'id', 'name'),
            'arr_modalidad' => ArrayHelper::map(array_merge([['id' => '0', 'name' => 'Seleccionar']], $arr_modalidad), 'id', 'name'),
            'arr_periodo' => ArrayHelper::map(array_merge([['id' => '0', 'paca_nombre' => 'Seleccionar']], $arr_periodo), 'id', 'paca_nombre'),
            "model" => $model,
        ]);
    }

    public function actionRegisterpdf() {

         try {
            $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
            $ids = $_GET['ids'];
            $mod_insgrado = new InscripcionGrado();
            $persona_model = $mod_insgrado->consultarPdf($ids);
            $rep = new ExportFile();
             $this->layout = 'register';

            $rep->orientation = "P";

            $rep->createReportPdf(
                    $this->render('register', [
                     'persona_model' => $persona_model,
                    ])
            );

            $rep->mpdf->Output('INSCRIPCION_PG' . $ids . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);

        } catch (Exception $e) {
            echo $e->getMessage();
        }


    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {

            $igra_id = base64_decode($_GET['ids']);

            $id = $data['id']; // per_id
            $per_cedula = $data['cedula'];
            $persona_model = Persona::findOne($id);

            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            /*$grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['formulariogrado/aspirantegrado']);
            }*/

            /**
             * Inf. Personal
             */
            $arr_ciu_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
            //$arr_can = Canton::findAll(["can_id" => $persona_model->can_id_nacimiento, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

            $ViewFormTab1 = $this->renderPartial('ViewFormTab1', [
                'arr_ciu_nac' => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. contacto
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $ViewFormTab2 = $this->renderPartial('ViewFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "Seleccionar")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "Seleccionar")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "Seleccionar")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. en caso de emergencia
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $financia = InscripcionGrado::findOne(['per_id' => $persona_model->per_id]);
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $ViewFormTab3 = $this->renderPartial('ViewFormTab3', [
                'arr_tipparentesco' => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'arr_financiamiento' => $this->financiamiento(),
                'datos_financiamiento' => $financia,
            ]);

            /**
             * Documentación
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
            $mod_insgrado = new InscripcionGrado();
            $documentos = $mod_insgrado->ObtenerdocumentosInscripcionGrado($persona_model->per_id);
            $ViewFormTab4 = $this->renderPartial('ViewFormTab4', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                "arch1" => $documentos['igra_ruta_doc_titulo'],
                "arch2" => $documentos['igra_ruta_doc_dni'],
                "arch3" => $documentos['igra_ruta_doc_certvota'],
                "arch4" => $documentos['igra_ruta_doc_foto'],
                "arch5" => $documentos['igra_ruta_doc_comprobantepago'],
                "arch6" => $documentos['igra_ruta_doc_recordacademico'],
                "arch7" => $documentos['igra_ruta_doc_certificado'],
                "arch8" => $documentos['igra_ruta_doc_syllabus'],
                "arch9" => $documentos['igra_ruta_doc_homologacion'],
                "arch10" => $documentos['igra_ruta_documento'],
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'documentos' => $documentos,

            ]);


            $items = [
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos Personales'),
                    'content' => $ViewFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos de Contacto'),
                    'content' => $ViewFormTab2,
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Datos en caso de Emergencia y Financiamiento'),
                    'content' => $ViewFormTab3,
                ],
                [
                    'label' => Academico::t('inscripciongrado', 'Info. Documentación'),
                    'content' => $ViewFormTab4,
                ],
            ];
            return $this->render('view', ['items' => $items, 'persona_model' => $persona_model, 'contacto_model' => $contacto_model]);
        }
        return $this->redirect(['formulariogrado/aspirantegrado']);
    }

    public function actionEdit() {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            if (isset($data["getprovincias"])) {
                $provincias = Provincia::find()->select("pro_id AS id, pro_nombre AS name")->where(["pro_estado_logico" => "1", "pro_estado" => "1", "pai_id" => $data['pai_id']])->asArray()->all();
                $message = array("provincias" => $provincias);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getcantones"])) {
                $cantones = Canton::find()->select("can_id AS id, can_nombre AS name")->where(["can_estado_logico" => "1", "can_estado" => "1", "pro_id" => $data['prov_id']])->asArray()->all();
                $message = array("cantones" => $cantones);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }
            if (isset($data["getarea"])) {
                //obtener el codigo de area del pais en informacion personal
                $area = $mod_pais->consultarCodigoArea($data["codarea"]);
                $message = array("area" => $area);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                return;
            }

            if ($data["upload_file"]) {

                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
            if ($typeFile == 'pdf' /*|| $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg'*/) {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "expediente/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                    }
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                }
            }

                  if ($data["upload_foto"]) {

                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if (($typeFile == 'png') or ( $typeFile == 'jpg') or ( $typeFile == 'jpeg')) {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "expediente/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                    }
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File " . basename($files['name']) . ". Try again.")]);
                }
            }
        }
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $id = $data['id'];

            if (Yii::$app->request->isAjax) {
                if (isset($data["pai_id"])) {
                    $model = new Provincia();
                    $arr_pro = $model->provinciabyPais($data["pai_id"]);

                    list($firstpro) = $arr_pro;

                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $firstpro['id']])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', ['arr_pro' => $arr_pro, 'arr_can' => $arr_can]);
                } else if (isset($data["pro_id"])) {
                    $arr_can = Canton::find()
                                    ->select(["can_id as id", "can_nombre as name"])
                                    ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                                        "pro_id" => $data["pro_id"]])->asArray()->all();

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $arr_can);
                }
            }

            $persona_model = Persona::findOne($id);
            Utilities::putMessageLogFile('id:' . $id);
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);
            $email = (isset($persona_model->per_correo) && $persona_model->per_correo != "") ? ($persona_model->per_correo) : ($usuario_model->usu_user);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            /*$grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);
            }*/

            /**
             * Inf. Basica
             */
            $arr_ciu_nac= Canton::find()->select("can_id AS id, can_nombre AS value")->where(["can_estado_logico" => "1", "can_estado" => "1"])->asArray()->all();
            //$arr_can = Canton::findAll(["can_id" => $persona_model->can_id_nacimiento, "can_estado" => 1, "can_estado_logico" => 1]);
            $arr_nacionalidad = Pais::find()->select("pai_id AS id, pai_nacionalidad AS value")->where(["pai_estado_logico" => "1", "pai_estado" => "1"])->asArray()->all();
            $arr_estado_civil = EstadoCivil::find()->select("eciv_id AS id, eciv_nombre AS value")->where(["eciv_estado_logico" => "1", "eciv_estado" => "1"])->asArray()->all();

            $EditFormTab1 = $this->renderPartial('EditFormTab1', [
                'arr_ciu_nac' => ArrayHelper::map($arr_ciu_nac, "id", "value"),
                "arr_nacionalidad" => ArrayHelper::map($arr_nacionalidad, "id", "value"),
                "arr_estado_civil" => ArrayHelper::map($arr_estado_civil, "id", "value"),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. contacto
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            Utilities::putMessageLogFile('pais:' . $persona_model->pai_id_domicilio);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);
            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $EditFormTab2 = $this->renderPartial('EditFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "Seleccionar")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "Seleccionar")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "Seleccionar")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
                'email' => $email,
            ]);

            /**
             * Inf. caso de emergencia
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $financia = InscripcionGrado::findOne(['per_id' => $persona_model->per_id]);
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();

            $EditFormTab3 = $this->renderPartial('EditFormTab3', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'arr_financiamiento' => $this->financiamiento(),
                'datos_financiamiento' => $financia,
            ]);

            /**
             * Documentación
             */
            $contacto_model = PersonaContacto::findOne(['per_id' => $persona_model->per_id]); // obtiene el pcon_id con el per_id
            $arr_tipparentesco = TipoParentesco::find()->select("tpar_id AS id, tpar_nombre AS value")->where(["tpar_estado_logico" => "1", "tpar_estado" => "1"])->asArray()->all();
            $mod_insgrado = new InscripcionGrado();
            $documentos = $mod_insgrado->ObtenerdocumentosInscripcionGrado($persona_model['per_id']);

            $EditFormTab4 = $this->renderPartial('EditFormTab4', [
                "arr_tipparentesco" => ArrayHelper::map($arr_tipparentesco, "id", "value"),
                "arch1" => $documentos['igra_ruta_doc_titulo'],
                "arch2" => $documentos['igra_ruta_doc_dni'],
                "arch3" => $documentos['igra_ruta_doc_certvota'],
                "arch4" => $documentos['igra_ruta_doc_foto'],
                "arch5" => $documentos['igra_ruta_doc_comprobantepago'],
                "arch6" => $documentos['igra_ruta_doc_recordacademico'],
                "arch7" => $documentos['igra_ruta_doc_certificado'],
                "arch8" => $documentos['igra_ruta_doc_syllabus'],
                "arch9" => $documentos['igra_ruta_doc_homologacion'],
                "arch10" =>$documentos['igra_ruta_documento'],
                'persona_model' => $persona_model,
                'contacto_model' => $contacto_model,
                'documentos' => $documentos,

            ]);


            $items = [
                [
                    'label' => Academico::t('formulario', 'Info. Datos Personales'),
                    'content' => $EditFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('formulario', 'Info. Datos de contacto'),
                    'content' => $EditFormTab2,
                ],
                [
                    'label' => Academico::t('formulario', 'Info. Datos en caso de Emergencia y Financiamiento'),
                    'content' => $EditFormTab3,
                ],
                [
                    'label' => Academico::t('formulario', 'Info. Documentos'),
                    'content' => $EditFormTab4,
                ],
            ];

            return $this->render('edit', [
                        'items' => $items,
                        'persona_model' => $persona_model,
                        'contacto_model' => $contacto_model,
            ]);
        }
        return $this->redirect(['formulariogrado/aspirantegrado']);
    }
    public function actionUpdate() {
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $per_id = $data["per_id"];
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $per_id = $data["per_id"];
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' /*|| $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg'*/) {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        //return;
                    }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes pdf, jpg, png.")]);
                }
            }

              if ($data["upload_foto"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $per_id = $data["per_id"];
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                        //return;
                    }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes jpg, png.")]);
                }
            }

            $con = \Yii::$app->db_inscripcion;
            $transaction = $con->beginTransaction();
            //$timeSt = time();
            $timeSt = date(Yii::$app->params["dateByDefault"]);

            try {
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $per_id = $data["per_id"];
                \app\models\Utilities::putMessageLogFile('per del java:  '.$per_id);
                /* Validacion de acceso a vistas por usuario */
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $user_usermane = Yii::$app->session->get("PB_username");
                $user_perId = Yii::$app->session->get("PB_perid");
                $grupo_model = new Grupo();
                $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);


                $inscripgrado_id = $data["igra_id"];
                if (isset($data["igra_ruta_doc_documento"]) && $data["igra_ruta_doc_documento"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_documento"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_documentoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_documento_per_" . $per_id . "." . $typeFile;
                    $titulo_documento = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_documentoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_documento"] = $titulo_documento;
                    if ($titulo_documento === false)
                        throw new Exception('Error Documneto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_titulo"]) && $data["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_titulo_per_" . $per_id . "." . $typeFile;
                    $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["igra_ruta_doc_dni"]) && $data["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certvota"]) && $data["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["igra_ruta_doc_foto"]) && $data["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_comprobantepago"]) && $data["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_comprobantepago_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }

                if (isset($data["igra_ruta_doc_record"]) && $data["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_record_per_" . $per_id . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certificado"]) && $data["igra_ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_syllabus"]) && $data["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_homologacion"]) && $data["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_especievalorada_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $homologacion_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                }
                //datos academicos
                $uaca_id = null /*$data['unidad']*/;
                $mod_id = null /*$data['modalidad']*/;
                $eaca_id = null /*$data['carrera']*/;
                $paca_id = null /*$data['periodo']*/;

                //datos personales
                $per_dni = $data['cedula'];
                $per_pri_nombre = ucwords(strtolower($data["primer_nombre"]));
                $per_seg_nombre = ucwords(strtolower($data["segundo_nombre"]));
                $per_pri_apellido = ucwords(strtolower($data["primer_apellido"]));
                $per_seg_apellido = ucwords(strtolower($data["segundo_apellido"]));
                $can_id_nacimiento = $data["cuidad_nac"];
                $per_fecha_nacimiento = $data["fecha_nac"];
                $per_nacionalidad = $data["nacionalidad"];
                $eciv_id = $data["estado_civil"];

                //datos contacto
                $pai_id_domicilio = $data["pais"];
                $pro_id_domicilio = $data["provincia"];
                $can_id_domicilio = $data["canton"];
                $per_domicilio_csec = ucwords(strtolower($data["parroquia"]));
                $per_domicilio_ref = ucwords(strtolower($data["dir_domicilio"]));
                $per_celular = $data["celular"];
                $per_domicilio_telefono = $data["telefono"];
                $per_correo = ucwords(strtolower($data["correo"]));

                //datos en caso de emergencias
                $per_trabajo_direccion = ucwords(strtolower($data["dir_trabajo"]));
                $pcon_nombre = ucwords(strtolower($data["cont_emergencia"]));
                $tpar_id = $data["parentesco"];
                $pcon_celular = ucwords(strtolower($data["tel_emergencia"]));
                $pcon_direccion = ucwords(strtolower($data["dir_personacontacto"]));

                //Datos de financiamiento
                $tfinanciamiento = $data["financiamiento"];
                $instituto_beca = ucwords(strtolower($data["instituto"]));

                // if estos data vienen null no hacer nada
                if(empty($data['igra_ruta_doc_documento'])){
                    $igra_ruta_doc_documento = null;
                }else{
                    $igra_ruta_doc_documento = $data['igra_ruta_doc_documento'];
                }
                if(empty($data['igra_ruta_doc_titulo'])){
                    $igra_ruta_doc_titulo = null;
                }else{
                    $igra_ruta_doc_titulo = $data['igra_ruta_doc_titulo'];
                }
                if(empty($data['igra_ruta_doc_dni'])){
                    $igra_ruta_doc_dni = null;
                }else{
                    $igra_ruta_doc_dni = $data['igra_ruta_doc_dni'];
                }
                if(empty($data['igra_ruta_doc_certvota'])){
                    $igra_ruta_doc_certvota = null;
                }else{
                    $igra_ruta_doc_certvota = $data['igra_ruta_doc_certvota'];
                }
                if(empty($data['igra_ruta_doc_foto'])){
                    $igra_ruta_doc_foto = null;
                }else{
                    $igra_ruta_doc_foto = $data['igra_ruta_doc_foto'];
                }
                if(empty($data['igra_ruta_doc_comprobantepago'])){
                    $$igra_ruta_doc_comprobantepago = null;
                }else{
                    $igra_ruta_doc_comprobantepago = $data['igra_ruta_doc_comprobantepago'];
                }
                if(empty($data['igra_ruta_doc_record'])){
                    $igra_ruta_doc_record = null;
                }else{
                    $igra_ruta_doc_record = $data['igra_ruta_doc_record'];
                }
                if(empty($data['igra_ruta_doc_certificado'])){
                    $igra_ruta_doc_certificado = null;
                }else{
                    $igra_ruta_doc_certificado = $data['igra_ruta_doc_certificado'];
                }
                if(empty($data['igra_ruta_doc_syllabus'])){
                    $igra_ruta_doc_syllabus = null;
                }else{
                    $igra_ruta_doc_syllabus = $data['igra_ruta_doc_syllabus'];
                }
                if(empty($data['igra_ruta_doc_homologacion'])){
                    $igra_ruta_doc_homologacion = null;
                }else{
                    $igra_ruta_doc_homologacion = $data['igra_ruta_doc_homologacion'];
                }

                $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

                $persona_model = Persona::findOne($per_id);
                $persona_model->per_cedula = $per_dni;
                $persona_model->per_pri_nombre =  ucwords(strtolower($per_pri_nombre));
                $persona_model->per_seg_nombre = ucwords(strtolower($per_seg_nombre)) ;
                $persona_model->per_pri_apellido = ucwords(strtolower($per_pri_apellido));
                $persona_model->per_seg_apellido = ucwords(strtolower($per_seg_apellido));
                $persona_model->can_id_nacimiento = $can_id_nacimiento;
                $persona_model->per_fecha_nacimiento = $per_fecha_nacimiento;
                $persona_model->per_nacionalidad = $per_nacionalidad;
                $persona_model->eciv_id = $eciv_id;


                $persona_model->pai_id_domicilio = $pai_id_domicilio;
                $persona_model->pro_id_domicilio = $pro_id_domicilio;
                $persona_model->can_id_domicilio = $can_id_domicilio;
                $persona_model->per_domicilio_csec = ucwords(strtolower($per_domicilio_csec));
                $persona_model->per_domicilio_ref = ucwords(strtolower($per_domicilio_ref));
                $persona_model->per_celular = $per_celular;
                $persona_model->per_domicilio_telefono = $per_domicilio_telefono;
                $persona_model->per_correo = ucwords(strtolower($per_correo));
                $persona_model->per_trabajo_direccion = ucwords(strtolower($per_trabajo_direccion));
                $persona_model->per_usuario_modifica = $usuario;
                $persona_model->per_fecha_modificacion = $fecha_modificacion;
                $persona_model->update();

                $per_id = $data["per_id"];

                $mod_percontacto = new PersonaContacto();
                $contacto = $mod_percontacto->modificarPersonacontacto($per_id, $tpar_id, $pcon_nombre, $pcon_celular, $pcon_celular, $pcon_direccion);

                $mod_inscripciongrado = new InscripcionGrado();
                //$gradoinscripcion = $mod_inscripciongrado->consultarDatosInscripcionContinuagrado($per_id);

                $gradoinscripcion = $mod_inscripciongrado->consultarDatosInscripciongrado($per_id);
                if($gradoinscripcion['existe_inscripcion'] == 1){
                    $inscripciongrado = $mod_inscripciongrado->updateDataInscripciongrado($con, $per_id, $uaca_id , $eaca_id, $mod_id ,  $paca_id, $per_dni, $tfinanciamiento, $instituto_beca, $igra_ruta_doc_documento, $igra_ruta_doc_titulo, $igra_ruta_doc_dni, $igra_ruta_doc_certvota, $igra_ruta_doc_foto, $igra_ruta_doc_comprobantepago, $igra_ruta_doc_record, $igra_ruta_doc_certificado, $igra_ruta_doc_syllabus, $igra_ruta_doc_homologacion);
                }



                if ($contacto && $inscripciongrado) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();

                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha modificado los datos del Aspirante."),
                            "title" => Yii::t('jslang', 'Success'),
                        );

                    //if ($persona_model->update()) {
                    //$usuario_model = Usuario::findOne(["per_id" => $per_id]);

                        /*$contacto_model = PersonaContacto::findOne(["per_id" => $persona_model->per_id]);
                        $contacto_model->pcon_nombre = $pcon_nombre;
                        $contacto_model->tpar_id = $tpar_id;
                        $contacto_model->pcon_celular = $pcon_celular;
                        $contacto_model->pcon_direccion = $pcon_direccion;
                        $contacto_model->pcon_fecha_modificacion = $fecha_modificacion;
                        $contacto_model->update();*/



                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al modificar. No cuenta con una solicitud de inscripción" . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }

            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.' . $ex->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionExpexcelaspirantegrado() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Yii::t("formulario", "Cedula"),
            Yii::t("formulario", "Estudiante"),
            Yii::t("formulario", "Periodo"),
            Yii::t("formulario", "Carrera"),
            Yii::t("formulario", "Modalidad"),
        );

        $model_grado = new InscripcionGrado();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["carreras"] = $data['carreras'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $model_grado->consultaRegistroAdmisiongrado(array(), 0);
        } else {
            $arrData = $model_grado->consultaRegistroAdmisiongrado($arrSearch, 0);
        }
        for ($i = 0; $i < count($arrData); $i++) {
            unset($arrData[$i]['per_id']);
        }
        $nameReport = academico::t("Academico", "Listado de Aspirantes de Grado");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionGuardarinscripciongrado() {
        $mod_persona = new Persona();
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $fecha_registro = date(Yii::$app->params["dateTimeByDefault"]);
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //if ($per_id > 0) {
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'pdf' /*|| $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg'*/) {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                       }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes pdf, jpg, png.")]);
                 //}
                }
            }

               if ($data["upload_foto"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                }
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                //if ($per_id > 0) {
                //Recibe Parámetros.
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                $dirFileEnd = Yii::$app->params["documentFolder"] . "inscripciongrado/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                       }
                }else {
                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Solo formato imagenes jpg, png.")]);
                 //}
                }
            }

            $con = \Yii::$app->db;
            $transaction = $con->beginTransaction();

            $timeSt = date(Yii::$app->params["dateByDefault"]);
            try {

                $unidad = $data['unidad'];
                $carrera = $data['carrera'];
                $modalidad = $data['modalidad'];
                $periodo = $data['periodo'];
                $ming_id = $data['ming_id'];
                $insc_persona = new Persona();
                $resp_persona = $insc_persona->consultaPeridxdni($data['cedula']);
                $per_id = $resp_persona['per_id'];
                $per_dni = $data['cedula'];
                //if ($per_id > 0) {
                $inscripgrado_id = $data["igra_id"];
                if (isset($data["igra_ruta_doc_documento"]) && $data["igra_ruta_doc_documento"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_documento"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_documentoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_documento_per_" . $per_id . "." . $typeFile;
                    $titulo_documento = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_documentoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_documento"] = $titulo_documento;
                    if ($titulo_documento === false)
                        throw new Exception('Error Documento no renombrado.');
                }
                if (isset($data["igra_ruta_doc_titulo"]) && $data["igra_ruta_doc_titulo"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_titulo"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $titulo_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_titulo_per_" . $per_id . "." . $typeFile;
                $titulo_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $titulo_archivoOld, '' /*$timeSt*/);
                //Utilities::putMessageLogFile('titulo_archivo xXx.. ' .$titulo_archivo );
                $data["igra_ruta_doc_titulo"] = $titulo_archivo;
                    if ($titulo_archivo === false)
                        throw new Exception('Error doc Titulo no renombrado.');
                }
                if (isset($data["igra_ruta_doc_dni"]) && $data["igra_ruta_doc_dni"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_dni"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $dni_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_dni_per_" . $per_id . "." . $typeFile;
                    $dni_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $dni_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_dni"] = $dni_archivo;
                    if ($dni_archivo === false)
                        throw new Exception('Error doc Dni no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certvota"]) && $data["igra_ruta_doc_certvota"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certvota"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certvota_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certvota_per_" . $per_id . "." . $typeFile;
                    $certvota_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certvota_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certvota"] = $certvota_archivo;
                    if ($certvota_archivo === false)
                        throw new Exception('Error doc certificado vot. no renombrado.');
                }
                if (isset($data["igra_ruta_doc_foto"]) && $data["igra_ruta_doc_foto"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_foto"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $foto_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_foto_per_" . $per_id . "." . $typeFile;
                    $foto_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $foto_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_foto"] = $foto_archivo;
                    if ($foto_archivo === false)
                        throw new Exception('Error doc Foto no renombrado.');
                }
                if (isset($data["igra_ruta_doc_comprobantepago"]) && $data["igra_ruta_doc_comprobantepago"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_comprobantepago"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $comprobantepago_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_comprobantepago_per_" . $per_id . "." . $typeFile;
                    $comprobantepago_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $comprobantepago_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_comprobantepago"] = $comprobantepago_archivo;
                    if ($comprobantepago_archivo === false)
                        throw new Exception('Error doc Comprobante de pago de matrícula no renombrado.');
                }

                if (isset($data["igra_ruta_doc_record"]) && $data["igra_ruta_doc_record"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_record"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $record_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_record_per_" . $per_id . "." . $typeFile;
                    $record_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $record_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_record"] = $record_archivo;
                    if ($record_archivo === false)
                        throw new Exception('Error doc Récord Académico no renombrado.');
                }
                if (isset($data["igra_ruta_doc_certificado"]) && $data["igra_ruta_doc_certificado"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_certificado"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $certificado_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_certificado_per_" . $per_id . "." . $typeFile;
                    $certificado_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $certificado_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_certificado"] = $certificado_archivo;
                    if ($certificado_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_syllabus"]) && $data["igra_ruta_doc_syllabus"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_syllabus"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $syllabus_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_syllabus_per_" . $per_id . "." . $typeFile;
                    $syllabus_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $syllabus_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_syllabus"] = $syllabus_archivo;
                    if ($syllabus_archivo === false)
                        throw new Exception('Error doc Certificado No Sanción no renombrado.');
                }
                if (isset($data["igra_ruta_doc_homologacion"]) && $data["igra_ruta_doc_homologacion"] != "") {
                    $arrIm = explode(".", basename($data["igra_ruta_doc_homologacion"]));
                    $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                    $homologacion_archivoOld = Yii::$app->params["documentFolder"] . "inscripciongrado/doc_especievalorada_per_" . $per_id . "." . $typeFile;
                    $homologacion_archivo = InscripcionGrado::addLabelTimeDocumentos($inscripgrado_id, $homologacion_archivoOld, '' /*$timeSt*/);
                    $data["igra_ruta_doc_homologacion"] = $homologacion_archivo;
                    if ($homologacion_archivo === false)
                        throw new Exception('Error doc Especie valorada por homologación no renombrado.');
                 }
                //}
                //Utilities::putMessageLogFile('cedula o pasaporte.. ' . $per_dni );
                //datos academicos
                $uaca_id = $data['unidad'];
                $eaca_id = $data['carrera'];
                $mod_id = $data['modalidad'];
                $paca_id = $data['periodo'];

                //datos personales
                $per_dni = $data['cedula'];
                $per_pri_nombre = ucwords(strtolower($data["primer_nombre"]));
                $per_seg_nombre = ucwords(strtolower($data["segundo_nombre"]));
                $per_pri_apellido = ucwords(strtolower($data["primer_apellido"]));
                $per_seg_apellido = ucwords(strtolower($data["segundo_apellido"]));
                $can_id_nacimiento = $data["cuidad_nac"];
                $per_fecha_nacimiento = $data["fecha_nac"];
                $per_nacionalidad = $data["nacionalidad"];
                $eciv_id = $data["estado_civil"];

                //datos contacto
                $pai_id_domicilio = $data["pais"];
                $pro_id_domicilio = $data["provincia"];
                $can_id_domicilio = $data["canton"];
                $per_domicilio_csec = ucwords(strtolower($data["parroquia"]));
                $per_domicilio_ref = ucwords(strtolower($data["dir_domicilio"]));
                $per_celular = $data["celular"];
                $per_domicilio_telefono = $data["telefono"];
                $per_correo = ucwords(strtolower($data["correo"]));

                //datos en caso de emergencias
                $per_trabajo_direccion = ucwords(strtolower($data["dir_trabajo"]));
                $pcon_nombre = ucwords(strtolower($data["cont_emergencia"]));
                $tpar_id = $data["parentesco"];
                $pcon_celular = ucwords(strtolower($data["tel_emergencia"]));
                $pcon_direccion = ucwords(strtolower($data["dir_personacontacto"]));

                //Datos de financiamiento
                $tfinanciamiento = $data["financiamiento"];
                $instituto_beca = ucwords(strtolower($data["instituto"]));

                //imagenes
                $igra_ruta_doc_documento = $data['igra_ruta_doc_documento'];
                $igra_ruta_doc_titulo = $data['igra_ruta_doc_titulo'];
                $igra_ruta_doc_dni = $data['igra_ruta_doc_dni'];
                $igra_ruta_doc_certvota = $data['igra_ruta_doc_certvota'];
                $igra_ruta_doc_foto = $data['igra_ruta_doc_foto'];
                $igra_ruta_doc_comprobantepago = $data['igra_ruta_doc_comprobantepago'];
                $igra_ruta_doc_record = $data['igra_ruta_doc_record'];
                $igra_ruta_doc_certificado = $data['igra_ruta_doc_certificado'];
                $igra_ruta_doc_syllabus = $data['igra_ruta_doc_syllabus'];
                $igra_ruta_doc_homologacion = $data['igra_ruta_doc_homologacion'];
                if ($per_id > 0) {
                    $model = new InscripcionGrado();
                    // persona ya exite se actualizan datos
                    $respPersona = $mod_persona->modificaPersonaInscripciongrado($per_pri_nombre, $per_seg_nombre, $per_pri_apellido, $per_seg_apellido, $per_dni, $eciv_id,  $can_id_nacimiento, $per_fecha_nacimiento, $per_celular, $per_correo, $per_domicilio_csec, $per_domicilio_ref, $per_domicilio_telefono, $pai_id_domicilio, $pro_id_domicilio, $can_id_domicilio, $per_nacionalidad, $per_trabajo_direccion);
                    //consultar si existe  la persona en la tabla inscripcion_grado
                    $resp_inscripcion = $model->consultarDatosInscripciongrado($per_id);
                    //si existe modificar los datos
                    if ($resp_inscripcion['existe_inscripcion'] > 0){
                        // modificar la tabla
                        $cone = \Yii::$app->db_inscripcion;
                        $mod_inscripciongrado = new InscripcionGrado();
                        $inscripciongrado = $mod_inscripciongrado->updateDataInscripciongrado($cone, $per_id, $uaca_id , $eaca_id, $mod_id , $paca_id, $per_dni, $tfinanciamiento, $instituto_beca, $igra_ruta_doc_documento, $igra_ruta_doc_titulo, $igra_ruta_doc_dni, $igra_ruta_doc_certvota, $igra_ruta_doc_foto, $igra_ruta_doc_comprobantepago, $igra_ruta_doc_record, $igra_ruta_doc_certificado, $igra_ruta_doc_syllabus, $igra_ruta_doc_homologacion);
                        $exito=1;
                    }else{ // caso contrario crear
                        $resul = $model->insertarDataInscripciongrado($per_id, $uaca_id, $eaca_id, $mod_id, $paca_id, $per_dni, $data);
                    }
                        //consultar persona contacto
                        $insc_personacont = new PersonaContacto();
                        $exist_personacon = $insc_personacont->consultaPersonaContacto($per_id);
                        // si existe modificar
                        if ($exist_personacon['contacto_id'] > 0) {
                            $modi_personacon = $insc_personacont->modificarPersonacontacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, $pcon_direccion);
                        }
                        // sino crear
                        else{
                            $crea_personacon = $insc_personacont->crearPersonaContacto($per_id, $tpar_id, $pcon_nombre, null, $pcon_celular, $pcon_direccion);
                            //if($crea_personacon){
                            $exito=1;
                            //}
                      }
                    if($exito){
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have been saved"),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }else{
                        $message = array(
                            "wtmessage" => Yii::t("formulario", "The information have not been saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message, $resul);
                    }
                    }else{

                    //Aqui debe ser un mensaje que no existe la persona
                    $message = array(
                        "wtmessage" => Yii::t("formulario", "No se encuentra documento de identidad de la persona registrada como aspirante, no se puede actualizar la información"),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), 'false', $message);
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
    }

 
}