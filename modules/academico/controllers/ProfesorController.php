<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Persona;
use app\models\Empresa;
use app\models\EmpresaPersona;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use app\models\Provincia;
use app\models\Pais;
use app\models\Grupo;
use app\models\Rol;
use app\models\GrupRol;
use app\models\ExportFile;
use app\models\Canton;
use app\modules\academico\models\Especies;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\DedicacionDocente;
use app\modules\academico\models\ProfesorExpDoc;
use app\modules\academico\models\ProfesorExpProf;
use app\modules\academico\models\ProfesorIdiomas;
use app\modules\academico\models\ProfesorCapacitacion;
use app\modules\academico\models\ProfesorConferencia;
use app\modules\academico\models\ProfesorCoordinacion;
use app\modules\academico\models\ProfesorEvaluacion;
use app\modules\academico\models\ProfesorInstruccion;
use app\modules\academico\models\ProfesorInvestigacion;
use app\modules\academico\models\ProfesorPublicacion;
use app\modules\academico\models\ProfesorReferencia;
use app\modules\academico\models\TipoPublicacion;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use app\modules\academico\models\NivelInstruccion;
use yii\base\Exception;
use app\modules\Academico\Module as Academico;

Academico::registerTranslations();

class ProfesorController extends \app\components\CController {

    public $folder_cv = 'expediente';
    public $pdf_cla_acceso = "";

    public function actionIndex() {
        $pro_model = new profesor();
        Utilities::putMessageLogFile('saludos1');
        /* Validacion de acceso a vistas por usuario */
        $user_ingresa = Yii::$app->session->get("PB_iduser");
        $user_usermane = Yii::$app->session->get("PB_username");
        $user_perId = Yii::$app->session->get("PB_perid");
        $grupo_model = new Grupo();
        $search = NULL;
        $perfil = '0'; // perfil administrador o talento humano        

        $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
        if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos)) {
            $search = $user_perId;
            $perfil = '1';
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->get();
            $search = $data["search"];
            if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos)) {
                $search = $user_perId;
                $perfil = '1';  // perfil profesor o no administrador ni talento humano
            }
            $model = $pro_model->getAllProfesorGrid($search, $perfil);
            if (isset($data["search"])) {
                return $this->renderPartial('index-grid', [
                            "model" => $model,
                ]);
            }
        }
        Utilities::putMessageLogFile('search:' . $search);
        $model = $pro_model->getAllProfesorGrid($search, $perfil);
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {

            $id = $data['id']; // per_id

            $persona_model = Persona::findOne($id);
            $usuario_model = Usuario::findOne(["per_id" => $id, "usu_estado" => '1', "usu_estado_logico" => '1']);
            $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $id, "eper_estado" => '1', "eper_estado_logico" => '1']);

            /* Validacion de acceso a vistas por usuario */
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            $user_usermane = Yii::$app->session->get("PB_username");
            $user_perId = Yii::$app->session->get("PB_perid");
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);
            }

            /**
             * Inf. Basica
             */
            $arr_dedic = DedicacionDocente::findAll(["ddoc_estado" => 1, "ddoc_estado_logico" => 1]);
            $ViewFormTab1 = $this->renderPartial('ViewFormTab1', [
                'arr_dedic' => (empty(ArrayHelper::map($arr_dedic, "ddoc_id", "ddoc_nombre"))) ? array(Yii::t("dedicacion", "-- Select Dedicación --")) : (ArrayHelper::map($arr_dedic, "ddoc_id", "ddoc_nombre")),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. Domicilio
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);

            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);

            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $ViewFormTab2 = $this->renderPartial('ViewFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
            ]);

            /**
             * Inf. Cuenta
             */
            $profesor_model = Profesor::findOne(['per_id' => $persona_model->per_id]); // obtiene el pro_id con el per_id
            $arr_inst_level = NivelInstruccion::findAll(["nins_estado" => 1, "nins_estado_logico" => 1]);
            $instruccion_model = new ProfesorInstruccion();

            $ViewFormTab4 = $this->renderPartial('ViewFormTab4', [
                //'model' => new ArrayDataProvider(array()),
                'model' => $instruccion_model->getAllInstruccionGrid($profesor_model->pro_id),
                'arr_inst_level' => (empty(ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre")),
            ]);

            $proExpDoc = new ProfesorExpDoc();
            $arr_profExDoc = $proExpDoc->getInstituciones();
            $ViewFormTab5 = $this->renderPartial('ViewFormTab5', [
                'model' => $proExpDoc->getAllExperienciaGrid($profesor_model->pro_id),
                'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
            ]);
            $proExpPro = new ProfesorExpProf();
            $ViewFormTab6 = $this->renderPartial('ViewFormTab6', [
                'model' => $proExpPro->getAllExperienciaGrid($profesor_model->pro_id),
            ]);

            $proIdiomas = new ProfesorIdiomas();
            $arr_profIdi = $proIdiomas->getIdiomas();
            $ViewFormTab7 = $this->renderPartial('ViewFormTab7', [
                'model' => $proIdiomas->getAllIdiomasGrid($profesor_model->pro_id),
                'arr_languages' => (empty(ArrayHelper::map($arr_profIdi, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Language --")) : (ArrayHelper::map($arr_profIdi, "id", "nombre")),
            ]);
            $proInvestigacion = new ProfesorInvestigacion();
            $ViewFormTab8 = $this->renderPartial('ViewFormTab8', [
                'model' => $proInvestigacion->getAllInvestigacionGrid($profesor_model->pro_id),
            ]);
            $proCap = new ProfesorCapacitacion();
            $arr_capItems = $proCap->getItems();
            $ViewFormTab9 = $this->renderPartial('ViewFormTab9', [
                'model' => $proCap->getAllCapacitacionGrid($profesor_model->pro_id),
                'arr_items' => (empty(ArrayHelper::map($arr_capItems, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Item --")) : (ArrayHelper::map($arr_capItems, "id", "nombre")),
            ]);

            $proConf = new ProfesorConferencia();
            $ViewFormTab10 = $this->renderPartial('ViewFormTab10', [
                'model' => $proConf->getAllConferenciaGrid($profesor_model->pro_id),
            ]);

            $proPub = new ProfesorPublicacion();
            $ViewFormTab11 = $this->renderPartial('ViewFormTab11', [
                'model' => $proPub->getAllPublicacionGrid($profesor_model->pro_id),
            ]);

            $proCoor = new ProfesorCoordinacion();
            $ViewFormTab12 = $this->renderPartial('ViewFormTab12', [
                'model' => $proCoor->getAllCoordinacionGrid($profesor_model->pro_id),
                'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
            ]);

            $proEva = new ProfesorEvaluacion();
            $ViewFormTab13 = $this->renderPartial('ViewFormTab13', [
                'model' => $proEva->getAllEvaluacionGrid($profesor_model->pro_id),
            ]);

            $proRef = new ProfesorReferencia();
            $ViewFormTab14 = $this->renderPartial('ViewFormTab14', [
                'model' => $proRef->getAllReferenciaGrid($profesor_model->pro_id),
            ]);

            $items = [
                [
                    'label' => Academico::t('profesor', 'Basic Info.'),
                    'content' => $ViewFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('profesor', 'Address Info.'),
                    'content' => $ViewFormTab2,
                ],
                [
                    'label' => Academico::t('profesor', 'Instruction Level'),
                    'content' => $ViewFormTab4,
                ],
                [
                    'label' => Academico::t('profesor', 'Teaching Experience'),
                    'content' => $ViewFormTab5,
                ],
                [
                    'label' => Academico::t('profesor', 'Professional Expirence'),
                    'content' => $ViewFormTab6,
                ],
                [
                    'label' => Academico::t('profesor', 'Languages'),
                    'content' => $ViewFormTab7,
                ],
                [
                    'label' => Academico::t('profesor', 'Research'),
                    'content' => $ViewFormTab8,
                ],
                [
                    'label' => Academico::t('profesor', 'Training'),
                    'content' => $ViewFormTab9,
                ],
                [
                    'label' => Academico::t('profesor', 'Conferences'),
                    'content' => $ViewFormTab10,
                ],
                [
                    'label' => Academico::t('profesor', 'Publishing'),
                    'content' => $ViewFormTab11,
                ],
                [
                    'label' => Academico::t('profesor', 'Thesis Direction'),
                    'content' => $ViewFormTab12,
                ],
                [
                    'label' => Academico::t('profesor', 'Performance Evaluation'),
                    'content' => $ViewFormTab13,
                ],
                [
                    'label' => Academico::t('profesor', 'References'),
                    'content' => $ViewFormTab14,
                ],
            ];
            return $this->render('view', ['items' => $items, 'persona_model' => $persona_model, 'pro_id' => $profesor_model->pro_id]);
        }
        return $this->redirect(['profesor/index']);
    }

    public function actionEdit() {

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {

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
            $grupo_model = new Grupo();
            $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
            if ($id != $user_perId) {
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);
            }

            /**
             * Inf. Basica
             */
            $arr_dedic = DedicacionDocente::findAll(["ddoc_estado" => 1, "ddoc_estado_logico" => 1]);

            $EditFormTab1 = $this->renderPartial('EditFormTab1', ['arr_dedic' => (empty(ArrayHelper::map($arr_dedic, "ddoc_id", "ddoc_nombre"))) ? array(Yii::t("dedicacion", "-- Select Dedicación --")) : (ArrayHelper::map($arr_dedic, "ddoc_id", "ddoc_nombre")),
                'persona_model' => $persona_model,
                'email' => $email,
            ]);

            /**
             * Inf. Domicilio
             */
            $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
            Utilities::putMessageLogFile('pais:' . $persona_model->pai_id_domicilio);
            $arr_pro = Provincia::findAll(["pai_id" => $persona_model->pai_id_domicilio, "pro_estado" => 1, "pro_estado_logico" => 1]);

            $arr_can = Canton::findAll(["pro_id" => $persona_model->pro_id_domicilio, "can_estado" => 1, "can_estado_logico" => 1]);

            $EditFormTab2 = $this->renderPartial('EditFormTab2', [
                'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
                'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
                'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
                'persona_model' => $persona_model,
            ]);

            $profesor_model = Profesor::findOne(['per_id' => $persona_model->per_id]);
            $arr_inst_level = NivelInstruccion::findAll(["nins_estado" => 1, "nins_estado_logico" => 1]);
            $instruccion_model = new ProfesorInstruccion();

            $EditFormTab4 = $this->renderPartial('EditFormTab4', [
                //'model' => new ArrayDataProvider(array()),
                'model' => $instruccion_model->getAllInstruccionGrid($profesor_model->pro_id),
                'arr_inst_level' => (empty(ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre")),
            ]);

            $proExpDoc = new ProfesorExpDoc();
            $arr_profExDoc = $proExpDoc->getInstituciones();
            $EditFormTab5 = $this->renderPartial('EditFormTab5', [
                'model' => $proExpDoc->getAllExperienciaGrid($profesor_model->pro_id),
                'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
            ]);

            $proExpPro = new ProfesorExpProf();
            $EditFormTab6 = $this->renderPartial('EditFormTab6', [
                'model' => $proExpPro->getAllExperienciaGrid($profesor_model->pro_id),
            ]);

            $proIdiomas = new ProfesorIdiomas();
            $arr_profIdi = $proIdiomas->getIdiomas();
            $arr_nivelIdi = $proIdiomas->getNivelidiomas();
            $EditFormTab7 = $this->renderPartial('EditFormTab7', [
                'model' => $proIdiomas->getAllIdiomasGrid($profesor_model->pro_id),
                'arr_languages' => (empty(ArrayHelper::map($arr_profIdi, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Language --")) : (ArrayHelper::map($arr_profIdi, "id", "nombre")),
                'arr_certificado' => array("0" => "Seleccione", "1" => "Si", "2" => "No"),
                'arr_nivel_ingles' => (empty(ArrayHelper::map($arr_nivelIdi, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Level --")) : (ArrayHelper::map($arr_nivelIdi, "id", "nombre")),
            ]);

            $proInvestigacion = new ProfesorInvestigacion();
            $EditFormTab8 = $this->renderPartial('EditFormTab8', [
                'model' => $proInvestigacion->getAllInvestigacionGrid($profesor_model->pro_id),
            ]);

            $proCap = new ProfesorCapacitacion();
            $arr_capItems = $proCap->getItems();
            $EditFormTab9 = $this->renderPartial('EditFormTab9', [
                'model' => $proCap->getAllCapacitacionGrid($profesor_model->pro_id),
                'arr_items' => (empty(ArrayHelper::map($arr_capItems, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Item --")) : (ArrayHelper::map($arr_capItems, "id", "nombre")),
            ]);

            $proConf = new ProfesorConferencia();
            $EditFormTab10 = $this->renderPartial('EditFormTab10', [
                'model' => $proConf->getAllConferenciaGrid($profesor_model->pro_id),
            ]);

            $proPub = new ProfesorPublicacion();
            $tipoPub = new TipoPublicacion();
            $respTipoPub = $tipoPub->getTipoPublicacion();
            $EditFormTab11 = $this->renderPartial('EditFormTab11', [
                'model' => $proPub->getAllPublicacionGrid($profesor_model->pro_id),
                'arr_tipo_publicacion' => ArrayHelper::map($respTipoPub, "id", "nombre"),
            ]);

            $proCoor = new ProfesorCoordinacion();
            $EditFormTab12 = $this->renderPartial('EditFormTab12', [
                'model' => $proCoor->getAllCoordinacionGrid($profesor_model->pro_id),
                'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
            ]);

            $proEva = new ProfesorEvaluacion();
            $EditFormTab13 = $this->renderPartial('EditFormTab13', [
                'model' => $proEva->getAllEvaluacionGrid($profesor_model->pro_id),
            ]);

            $proRef = new ProfesorReferencia();
            $EditFormTab14 = $this->renderPartial('EditFormTab14', [
                'model' => $proRef->getAllReferenciaGrid($profesor_model->pro_id),
            ]);

            $items = [
                [
                    'label' => Academico::t('profesor', 'Basic Info.'),
                    'content' => $EditFormTab1,
                    'active' => true
                ],
                [
                    'label' => Academico::t('profesor', 'Address Info.'),
                    'content' => $EditFormTab2,
                ],
                [
                    'label' => Academico::t('profesor', 'Instruction Level'),
                    'content' => $EditFormTab4,
                ],
                [
                    'label' => Academico::t('profesor', 'Teaching Experience'),
                    'content' => $EditFormTab5,
                ],
                [
                    'label' => Academico::t('profesor', 'Professional Expirence'),
                    'content' => $EditFormTab6,
                ],
                [
                    'label' => Academico::t('profesor', 'Languages'),
                    'content' => $EditFormTab7,
                ],
                [
                    'label' => Academico::t('profesor', 'Research'),
                    'content' => $EditFormTab8,
                ],
                [
                    'label' => Academico::t('profesor', 'Training'),
                    'content' => $EditFormTab9,
                ],
                [
                    'label' => Academico::t('profesor', 'Conferences'),
                    'content' => $EditFormTab10,
                ],
                [
                    'label' => Academico::t('profesor', 'Publishing'),
                    'content' => $EditFormTab11,
                ],
                [
                    'label' => Academico::t('profesor', 'Thesis Direction'),
                    'content' => $EditFormTab12,
                ],
                [
                    'label' => Academico::t('profesor', 'Performance Evaluation'),
                    'content' => $EditFormTab13,
                ],
                [
                    'label' => Academico::t('profesor', 'References'),
                    'content' => $EditFormTab14,
                ],
            ];

            return $this->render('edit', [
                        'items' => $items,
                        'persona_model' => $persona_model,
                        'storage_instruccion' => $instruccion_model->getDataToStorage($profesor_model->pro_id, true),
                        'storage_docencia' => $proExpDoc->getDataToStorage($profesor_model->pro_id, true),
                        'storage_experiencia' => $proExpPro->getDataToStorage($profesor_model->pro_id, true),
                        'storage_idioma' => $proIdiomas->getDataToStorage($profesor_model->pro_id, true),
                        'storage_investigacion' => $proInvestigacion->getDataToStorage($profesor_model->pro_id, true),
                        'storage_capacitacion' => $proCap->getDataToStorage($profesor_model->pro_id, true),
                        'storage_conferencia' => $proConf->getDataToStorage($profesor_model->pro_id, true),
                        'storage_publicacion' => $proPub->getDataToStorage($profesor_model->pro_id, true),
                        'storage_coordinacion' => $proCoor->getDataToStorage($profesor_model->pro_id, true),
                        'storage_evaluacion' => $proEva->getDataToStorage($profesor_model->pro_id, true),
                        'storage_referencia' => $proRef->getDataToStorage($profesor_model->pro_id, true),
            ]);
        }
        return $this->redirect(['profesor/index']);
    }

    public function actionNew() {

        $_SESSION['JSLANG']['Must be Fill all information in fields with label *.'] = Academico::t("profesor", "Must be Fill all information in fields with label *.");

        $arr_pais = Pais::findAll(["pai_estado" => 1, "pai_estado_logico" => 1]);
        list($firstpais) = $arr_pais;

        $arr_pro = Provincia::find()
                        ->select(["pro_id", "pro_nombre"])
                        ->andWhere(["pro_estado" => 1, "pro_estado_logico" => 1,
                            "pai_id" => $firstpais->pai_id])->asArray()->all();

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
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

            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros

                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);

                if (($typeFile == 'jpg') or ( $typeFile == 'jpeg') or ( $typeFile == 'png')) {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "expediente/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    //\app\models\Utilities::putMessageLogFile('pedro: ' .$typeFile);

                    $this->renderPartial('NewFormTab1', [
                        "typeFile" => $typeFile,
                    ]);
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

        list($firstpro) = $arr_pro;

        $dedicacionDocente = new \app\modules\academico\models\DedicacionDocente();
        $model_dedicacion = $dedicacionDocente->getDedicacionDocente();
        $NewFormTab1 = $this->renderPartial('NewFormTab1', ['model_dedicacion' => ArrayHelper::map(array_merge([["Id" => "0", "name" => Yii::t("formulario", "Select")]], $model_dedicacion), "Id", "name"),
        ]);
        $arr_can = Canton::find()
                        ->select(["can_id", "can_nombre"])
                        ->andWhere(["can_estado" => 1, "can_estado_logico" => 1,
                            "pro_id" => $firstpro['pro_id']])->asArray()->all();

        $NewFormTab2 = $this->renderPartial('NewFormTab2', [
            'arr_pais' => (empty(ArrayHelper::map($arr_pais, "pai_id", "pai_nombre"))) ? array(Yii::t("pais", "-- Select Pais --")) : (ArrayHelper::map($arr_pais, "pai_id", "pai_nombre")),
            'arr_pro' => (empty(ArrayHelper::map($arr_pro, "pro_id", "pro_nombre"))) ? array(Yii::t("provincia", "-- Select Provincia --")) : (ArrayHelper::map($arr_pro, "pro_id", "pro_nombre")),
            'arr_can' => (empty(ArrayHelper::map($arr_can, "can_id", "can_nombre"))) ? array(Yii::t("canton", "-- Select Canton --")) : (ArrayHelper::map($arr_can, "can_id", "can_nombre")),
        ]);

        $arr_inst_level = NivelInstruccion::findAll(["nins_estado" => 1, "nins_estado_logico" => 1]);

        $NewFormTab4 = $this->renderPartial('NewFormTab4', [
            'model' => new ArrayDataProvider(array()),
            'arr_inst_level' => (empty(ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_inst_level, "nins_id", "nins_nombre")),
        ]);

        $proExpDoc = new ProfesorExpDoc();
        $arr_profExDoc = $proExpDoc->getInstituciones();
        $NewFormTab5 = $this->renderPartial('NewFormTab5', [
            'model' => new ArrayDataProvider(array()),
            'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
        ]);
        $NewFormTab6 = $this->renderPartial('NewFormTab6', [
            'model' => new ArrayDataProvider(array()),
        ]);
        $proIdiomas = new ProfesorIdiomas();
        $arr_profIdi = $proIdiomas->getIdiomas();
        $arr_nivelIdi = $proIdiomas->getNivelidiomas();
        $NewFormTab7 = $this->renderPartial('NewFormTab7', [
            'model' => new ArrayDataProvider(array()),
            'arr_languages' => (empty(ArrayHelper::map($arr_profIdi, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Language --")) : (ArrayHelper::map($arr_profIdi, "id", "nombre")),
            'arr_certificado' => array("0" => "Seleccione", "1" => "Si", "2" => "No"),
            'arr_nivel_ingles' => (empty(ArrayHelper::map($arr_nivelIdi, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Level --")) : (ArrayHelper::map($arr_nivelIdi, "id", "nombre")),
        ]);

        $NewFormTab8 = $this->renderPartial('NewFormTab8', [
            'model' => new ArrayDataProvider(array()),
        ]);
        $proCap = new ProfesorCapacitacion();
        $arr_capItems = $proCap->getItems();
        $NewFormTab9 = $this->renderPartial('NewFormTab9', [
            'model' => new ArrayDataProvider(array()),
            'arr_items' => (empty(ArrayHelper::map($arr_capItems, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Item --")) : (ArrayHelper::map($arr_capItems, "id", "nombre")),
        ]);

        $NewFormTab10 = $this->renderPartial('NewFormTab10', [
            'model' => new ArrayDataProvider(array()),
        ]);

        $tipoPub = new TipoPublicacion();
        $respTipoPub = $tipoPub->getTipoPublicacion();
        $NewFormTab11 = $this->renderPartial('NewFormTab11', [
            'model' => new ArrayDataProvider(array()),
            'arr_tipo_publicacion' => ArrayHelper::map($respTipoPub, "id", "nombre"),
        ]);

        $NewFormTab12 = $this->renderPartial('NewFormTab12', [
            'model' => new ArrayDataProvider(array()),
            'arr_inst' => (empty(ArrayHelper::map($arr_profExDoc, "id", "nombre"))) ? array(Academico::t("profesor", "-- Select Instruction Level --")) : (ArrayHelper::map($arr_profExDoc, "id", "nombre")),
        ]);

        $NewFormTab13 = $this->renderPartial('NewFormTab13', [
            'model' => new ArrayDataProvider(array()),
        ]);

        $NewFormTab14 = $this->renderPartial('NewFormTab14', [
            'model' => new ArrayDataProvider(array()),
        ]);

        $items = [
            [
                'label' => Academico::t('profesor', 'Basic Info.'),
                'content' => $NewFormTab1,
                'active' => true
            ],
            [
                'label' => Academico::t('profesor', 'Address Info.'),
                'content' => $NewFormTab2,
            ],
            [
                'label' => Academico::t('profesor', 'Instruction Level'),
                'content' => $NewFormTab4,
            ],
            [
                'label' => Academico::t('profesor', 'Teaching Experience'),
                'content' => $NewFormTab5,
            ],
            [
                'label' => Academico::t('profesor', 'Professional Expirence'),
                'content' => $NewFormTab6,
            ],
            [
                'label' => Academico::t('profesor', 'Languages'),
                'content' => $NewFormTab7,
            ],
            [
                'label' => Academico::t('profesor', 'Research'),
                'content' => $NewFormTab8,
            ],
            [
                'label' => Academico::t('profesor', 'Training'),
                'content' => $NewFormTab9,
            ],
            [
                'label' => Academico::t('profesor', 'Conferences'),
                'content' => $NewFormTab10,
            ],
            [
                'label' => Academico::t('profesor', 'Publishing'),
                'content' => $NewFormTab11,
            ],
            [
                'label' => Academico::t('profesor', 'Thesis Direction'),
                'content' => $NewFormTab12,
            ],
            [
                'label' => Academico::t('profesor', 'Performance Evaluation'),
                'content' => $NewFormTab13,
            ],
            [
                'label' => Academico::t('profesor', 'References'),
                'content' => $NewFormTab14,
            ],
        ];

        return $this->render('new', ['items' => $items]);
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_ingresa = Yii::$app->session->get("PB_iduser");
            // $con = \Yii::$app->db_academico;
            //  $con = \Yii::$app->db_asgard;
            try {

                /**
                 * Inf. Basica
                 */
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $cedula = $data["cedula"];
                $ruc = $data["ruc"];
                $pasaporte = $data["pasaporte"];
                $correo = strtolower($data["correo"]);
                $nacionalidad = $data["nacionalidad"];
                $celular = $data["celular"];
                $phone = $data["phone"];
                $dedicacion = $data["dedicacion"];
                $pro_num_contrato = $data["pro_num_contrato"];
                $fecha_nacimiento = $data["fecha_nacimiento"];
                $foto = $data['foto'];
                $extfoto = $data['extfoto'];

                /**
                 * Inf. Domicilio
                 */
                $pai_id_domicilio = $data["pai_id"];
                $pro_id_domicilio = $data["pro_id"];
                $can_id_domicilio = $data["can_id"];
                $sector = ucwords($data["sector"]);
                $calle_pri = ucwords($data["calle_pri"]);
                $calle_sec = ucwords($data["calle_sec"]);
                $numeracion = ucwords($data["numeracion"]);
                $referencia = ucwords($data["referencia"]);

                /**
                 * Inf. Cuenta
                 */
                $usuario = $correo; //strtolower($data["usuario"]);
                $clave = $cedula; //$data["clave"];
                $gru_id = 13; //$data["gru_id"];
                $rol_id = 17; //$data["rol_id"];
                $emp_id = 1; //$data["emp_id"];                 

                /**
                 * Inf. Session Storages
                 */
                $arr_instuccion = (isset($data["grid_instruccion_list"]) && $data["grid_instruccion_list"] != "") ? $data["grid_instruccion_list"] : NULL;
                $arr_docencia = (isset($data["grid_docencia_list"]) && $data["grid_docencia_list"] != "") ? $data["grid_docencia_list"] : NULL;
                $arr_experiencia = (isset($data["grid_experiencia_list"]) && $data["grid_experiencia_list"] != "") ? $data["grid_experiencia_list"] : NULL;
                $arr_idioma = (isset($data["grid_idioma_list"]) && $data["grid_idioma_list"] != "") ? $data["grid_idioma_list"] : NULL;
                $arr_investigacion = (isset($data["grid_investigacion_list"]) && $data["grid_investigacion_list"] != "") ? $data["grid_investigacion_list"] : NULL;
                $arr_evento = (isset($data["grid_evento_list"]) && $data["grid_evento_list"] != "") ? $data["grid_evento_list"] : NULL;
                $arr_conferencia = (isset($data["grid_conferencia_list"]) && $data["grid_conferencia_list"] != "") ? $data["grid_conferencia_list"] : NULL;
                $arr_publicacion = (isset($data["grid_publicacion_list"]) && $data["grid_publicacion_list"] != "") ? $data["grid_publicacion_list"] : NULL;
                $arr_coordinacion = (isset($data["grid_coordinacion_list"]) && $data["grid_coordinacion_list"] != "") ? $data["grid_coordinacion_list"] : NULL;
                $arr_evaluacion = (isset($data["grid_evaluacion_list"]) && $data["grid_evaluacion_list"] != "") ? $data["grid_evaluacion_list"] : NULL;
                $arr_referencia = (isset($data["grid_referencia_list"]) && $data["grid_referencia_list"] != "") ? $data["grid_referencia_list"] : NULL;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );

                $arr_grupo_rol = GrupRol::find()->where(['gru_id' => $gru_id, 'rol_id' => $rol_id])->asArray()->all();

                $validacion = Persona::VerificarPersonaExiste($cedula, $pasaporte, $ruc);

                if ($validacion === 1) {
                    /**
                     * Si la persona existe y no esta eliminada
                     */
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Persona ya existente.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );

                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                } else if ($validacion === 0) {
                    /**
                     * Si la persona existe y esta eliminada
                     */
                    $per_id_existente = Persona::ObtenerPersonabyCedulaPasaporteRuc($cedula, $pasaporte, $ruc);

                    $persona_model = Persona::findOne($per_id_existente);
                    $persona_model->per_pri_nombre = $pri_nombre;
                    $persona_model->per_seg_nombre = $seg_nombre;
                    $persona_model->per_pri_apellido = $pri_apellido;
                    $persona_model->per_seg_apellido = $seg_apellido;
                    $persona_model->per_cedula = $cedula;
                    $persona_model->per_nacionalidad = $nacionalidad;
                    $persona_model->per_domicilio_telefono = $phone;
                    $persona_model->per_celular = $celular;
                    $persona_model->per_fecha_nacimiento = $fecha_nacimiento;

                    $arr_file = explode($foto, '.jpg');
                    if (isset($arr_file[0]) && $arr_file[0] != "") {
                        $oldFile = $this->folder_cv . '/' . $foto;
                        $persona_model->per_foto = $this->folder_cv . '/' . $per_id_existente . "_" . $foto;
                        $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                        rename($urlBase . $oldFile, $urlBase . $persona_model->per_foto);
                    }

                    if ($ruc != "") {
                        $persona_model->per_ruc = $ruc;
                    }
                    if ($pasaporte != "") {
                        $persona_model->per_pasaporte = $pasaporte;
                    }
                    $persona_model->per_correo = $correo;
                    $persona_model->pai_id_domicilio = $pai_id_domicilio;
                    $persona_model->pro_id_domicilio = $pro_id_domicilio;
                    $persona_model->can_id_domicilio = $can_id_domicilio;
                    $persona_model->per_domicilio_sector = $sector;
                    $persona_model->per_domicilio_cpri = $calle_pri;
                    $persona_model->per_domicilio_csec = $calle_sec;
                    $persona_model->per_domicilio_num = $numeracion;
                    $persona_model->per_domicilio_ref = $referencia;
                    $persona_model->per_estado = '1';
                    $persona_model->per_estado_logico = '1';

                    if ($persona_model->save()) {
                        $profesor_model = new Profesor();
                        $profesor_model->per_id = $per_id_existente;
                        $profesor_model->ddoc_id = $dedicacion;
                        $profesor_model->pro_num_contrato = $pro_num_contrato;
                        $profesor_model->pro_estado = '1';
                        $profesor_model->pro_fecha_creacion = date("Y-m-d H:i:s");
                        $profesor_model->pro_estado_logico = '1';
                        $profesor_model->pro_usuario_ingreso = $user_ingresa;
                        $profesor_model->save();

                        $usuario_model = new Usuario();
                        $usuario_model->per_id = $per_id_existente;
                        $usuario_model->usu_user = $usuario;
                        $usuario_model->generateAuthKey();
                        $usuario_model->setPassword($clave);

                        $usuario_model->usu_estado = '1';
                        $usuario_model->usu_estado_logico = '1';
                        $usuario_model->save();
                        $usu_id = $usuario_model->getPrimaryKey();

                        $empresa_persona_model = new EmpresaPersona();
                        $empresa_persona_model->emp_id = $emp_id;
                        $empresa_persona_model->per_id = $per_id_existente;
                        $empresa_persona_model->eper_estado = '1';
                        $empresa_persona_model->eper_estado_logico = '1';
                        $empresa_persona_model->save();
                        $eper_id = $empresa_persona_model->getPrimaryKey();

                        /** Se agregan Informacion de Expediente * */
                        if (isset($arr_instuccion)) {
                            foreach ($arr_instuccion as $key0 => $value0) {
                                if ($value0[6] == "N") {
                                    $instruccion_model = new ProfesorInstruccion();
                                    $instruccion_model->nins_id = $value0[1];
                                    $instruccion_model->pins_institucion = strtolower($value0[2]);
                                    $instruccion_model->pins_especializacion = strtolower($value0[3]);
                                    $instruccion_model->pins_titulo = strtolower($value0[4]);
                                    $instruccion_model->pins_senescyt = strtolower($value0[5]);
                                    $instruccion_model->pro_id = $profesor_model->pro_id;
                                    $instruccion_model->pins_estado = '1';
                                    $instruccion_model->pins_estado_logico = '1';
                                    $instruccion_model->pins_usuario_ingreso = $user_ingresa;
                                    $instruccion_model->save();
                                }
                            }
                        }
                        if (isset($arr_docencia)) {
                            foreach ($arr_docencia as $key1 => $value1) {
                                if ($value1[6] == "N") {
                                    $docencia_model = new ProfesorExpDoc();
                                    $docencia_model->ins_id = $value1[1];
                                    $docencia_model->pedo_fecha_inicio = $value1[2];
                                    $docencia_model->pedo_fecha_fin = $value1[3];
                                    $docencia_model->pedo_denominacion = strtolower($value1[4]);
                                    $docencia_model->pedo_asignaturas = strtolower($value1[5]);
                                    $docencia_model->pro_id = $profesor_model->pro_id;
                                    $docencia_model->pedo_estado = '1';
                                    $docencia_model->pedo_estado_logico = '1';
                                    $docencia_model->pedo_usuario_ingreso = $user_ingresa;
                                    $docencia_model->save();
                                }
                            }
                        }
                        if (isset($arr_experiencia)) {
                            foreach ($arr_experiencia as $key2 => $value2) {
                                if ($value2[6] == "N") {
                                    $experiencia_model = new ProfesorExpProf();
                                    $experiencia_model->pepr_organizacion = strtolower($value2[1]);
                                    $experiencia_model->pepr_fecha_inicio = $value2[2];
                                    $experiencia_model->pepr_fecha_fin = $value2[3];
                                    $experiencia_model->pepr_denominacion = strtolower($value2[4]);
                                    $experiencia_model->pepr_funciones = strtolower($value2[5]);
                                    $experiencia_model->pro_id = $profesor_model->pro_id;
                                    $experiencia_model->pepr_estado = '1';
                                    $experiencia_model->pepr_estado_logico = '1';
                                    $experiencia_model->pepr_usuario_ingreso = $user_ingresa;
                                    $experiencia_model->save();
                                }
                            }
                        }
                        if (isset($arr_idioma)) {
                            foreach ($arr_idioma as $key3 => $value3) {
                                if ($value3[6] == "N") {
                                    $idiomas_model = new ProfesorIdiomas();
                                    $idiomas_model->idi_id = $value3[1];
                                    $idiomas_model->pidi_nivel_escrito = ucfirst($value3[2]);
                                    $idiomas_model->pidi_nivel_oral = ucfirst($value3[3]);
                                    $idiomas_model->pidi_certificado = ucfirst($value3[4]);
                                    $idiomas_model->pidi_institucion = ucwords($value3[5]);
                                    $idiomas_model->pro_id = $profesor_model->pro_id;
                                    $idiomas_model->pidi_estado = '1';
                                    $idiomas_model->pidi_estado_logico = '1';
                                    $idiomas_model->pidi_usuario_ingreso = $user_ingresa;
                                    $idiomas_model->save();
                                }
                            }
                        }
                        if (isset($arr_investigacion)) {
                            foreach ($arr_investigacion as $key4 => $value4) {
                                if ($value4[7] == "N") {
                                    $investigacion_model = new ProfesorInvestigacion();
                                    $investigacion_model->pinv_proyecto = strtolower($value4[1]);
                                    $investigacion_model->pinv_ambito = strtolower($value4[2]);
                                    $investigacion_model->pinv_responsabilidad = strtolower($value4[3]);
                                    $investigacion_model->pinv_entidad = strtolower($value4[4]);
                                    $investigacion_model->pinv_anio = strtolower($value4[5]);
                                    $investigacion_model->pinv_duracion = strtolower($value4[6]);
                                    $investigacion_model->pro_id = $profesor_model->pro_id;
                                    $investigacion_model->pinv_estado = '1';
                                    $investigacion_model->pinv_estado_logico = '1';
                                    $investigacion_model->pinv_usuario_ingreso = $user_ingresa;
                                    $investigacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_evento)) {
                            foreach ($arr_evento as $key5 => $value5) {
                                if ($value5[6] == "N") {
                                    $capacitacion_model = new ProfesorCapacitacion();
                                    $capacitacion_model->pcap_tipo = strtolower($value5[4]);
                                    $capacitacion_model->pcap_evento = strtolower($value5[1]);
                                    $capacitacion_model->pcap_institucion = strtolower($value5[2]);
                                    $capacitacion_model->pcap_anio = strtolower($value5[3]);
                                    $capacitacion_model->pcap_duracion = strtolower($value5[5]);
                                    $capacitacion_model->pro_id = $profesor_model->pro_id;
                                    $capacitacion_model->pcap_estado = '1';
                                    $capacitacion_model->pcap_estado_logico = '1';
                                    $capacitacion_model->pcap_usuario_ingreso = $user_ingresa;
                                    $capacitacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_conferencia)) {
                            foreach ($arr_conferencia as $key6 => $value6) {
                                if ($value6[5] == "N") {
                                    $capacitacion_model = new ProfesorConferencia();
                                    $capacitacion_model->pcon_evento = strtolower($value6[1]);
                                    $capacitacion_model->pcon_institucion = strtolower($value6[2]);
                                    $capacitacion_model->pcon_anio = strtolower($value6[3]);
                                    $capacitacion_model->pcon_ponencia = strtolower($value6[4]);
                                    $capacitacion_model->pro_id = $profesor_model->pro_id;
                                    $capacitacion_model->pcon_estado = '1';
                                    $capacitacion_model->pcon_estado_logico = '1';
                                    $capacitacion_model->pcon_usuario_ingreso = $user_ingresa;
                                    $capacitacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_coordinacion)) {
                            foreach ($arr_coordinacion as $key7 => $value7) {
                                if ($value7[6] == "N") {
                                    $coordinacion_model = new ProfesorCoordinacion();
                                    $coordinacion_model->pcoo_alumno = ucwords($value7[1]);
                                    $coordinacion_model->pcoo_programa = ucfirst($value7[2]);
                                    $coordinacion_model->pcoo_academico = ucfirst($value7[3]);
                                    $coordinacion_model->ins_id = ucwords($value7[4]);
                                    $coordinacion_model->pcoo_anio = strtolower($value7[5]);
                                    $coordinacion_model->pro_id = $profesor_model->pro_id;
                                    $coordinacion_model->pcoo_estado = '1';
                                    $coordinacion_model->pcoo_estado_logico = '1';
                                    $coordinacion_model->pcoo_usuario_ingreso = $user_ingresa;
                                    $coordinacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_evaluacion)) {
                            foreach ($arr_evaluacion as $key8 => $value8) {
                                if ($value8[4] == "N") {
                                    $evaluacion_model = new ProfesorEvaluacion();
                                    $evaluacion_model->peva_periodo = strtolower($value8[1]);
                                    $evaluacion_model->peva_institucion = strtolower($value8[2]);
                                    $evaluacion_model->peva_evaluacion = strtolower($value8[3]);
                                    $evaluacion_model->pro_id = $profesor_model->pro_id;
                                    $evaluacion_model->peva_estado = '1';
                                    $evaluacion_model->peva_estado_logico = '1';
                                    $evaluacion_model->peva_usuario_ingreso = $user_ingresa;
                                    $evaluacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_publicacion)) {
                            foreach ($arr_publicacion as $key9 => $value9) {
                                if ($value9[6] == "N") {
                                    $publicacion_model = new ProfesorPublicacion();
                                    $publicacion_model->tpub_id = $value9[1];
                                    $publicacion_model->ppub_titulo = strtolower($value9[2]);
                                    $publicacion_model->ppub_editorial = strtolower($value9[3]);
                                    $publicacion_model->ppub_isbn = strtolower($value9[4]);
                                    $publicacion_model->ppub_autoria = strtolower($value9[5]);
                                    $publicacion_model->pro_id = $profesor_model->pro_id;
                                    $publicacion_model->ppub_estado = '1';
                                    $publicacion_model->ppub_estado_logico = '1';
                                    $publicacion_model->ppub_usuario_ingreso = $user_ingresa;
                                    $publicacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_referencia)) {
                            foreach ($arr_referencia as $key10 => $value10) {
                                if ($value10[5] == "N") {
                                    $referencia_model = new ProfesorReferencia();
                                    $referencia_model->pref_contacto = strtolower($value10[1]);
                                    $referencia_model->pref_relacion_cargo = strtolower($value10[2]);
                                    $referencia_model->pref_organizacion = strtolower($value10[3]);
                                    $referencia_model->pref_numero = strtolower($value10[4]);
                                    $referencia_model->pro_id = $profesor_model->pro_id;
                                    $referencia_model->pref_estado = '1';
                                    $referencia_model->pref_estado_logico = '1';
                                    $referencia_model->pref_usuario_ingreso = $user_ingresa;
                                    $referencia_model->save();
                                }
                            }
                        }
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    } else {
                        throw new Exception('Error SubModulo no creado.');
                    }
                } else {
                    /**
                     * Registro nuevo
                     */
                    // $transaction = $con->beginTransaction();

                    $persona_model = new Persona();
                    $persona_model->per_pri_nombre = $pri_nombre;
                    $persona_model->per_seg_nombre = $seg_nombre;
                    $persona_model->per_pri_apellido = $pri_apellido;
                    $persona_model->per_seg_apellido = $seg_apellido;
                    $persona_model->per_cedula = $cedula;
                    if ($ruc != "") {
                        $persona_model->per_ruc = $ruc;
                    }
                    if ($pasaporte != "") {
                        $persona_model->per_pasaporte = $pasaporte;
                    }
                    $persona_model->per_correo = $correo;
                    $persona_model->per_nacionalidad = $nacionalidad;
                    $persona_model->per_celular = $celular;
                    $persona_model->per_domicilio_telefono = $phone;
                    $persona_model->per_fecha_nacimiento = $fecha_nacimiento;
                    $persona_model->pai_id_domicilio = $pai_id_domicilio;
                    $persona_model->pro_id_domicilio = $pro_id_domicilio;
                    $persona_model->can_id_domicilio = $can_id_domicilio;
                    $persona_model->per_domicilio_sector = $sector;
                    $persona_model->per_domicilio_cpri = $calle_pri;
                    $persona_model->per_domicilio_csec = $calle_sec;
                    $persona_model->per_domicilio_num = $numeracion;
                    $persona_model->per_domicilio_ref = $referencia;
                    $persona_model->per_estado = '1';
                    $persona_model->per_estado_logico = '1';
                    \app\models\Utilities::putMessageLogFile('1');
                    if ($persona_model->save()) {
                        \app\models\Utilities::putMessageLogFile('2');
                        $per_id = $persona_model->getPrimaryKey();
                        $arr_file_ext = explode('.', $extfoto);

                        \app\models\Utilities::putMessageLogFile($arr_file_ext);
                        $foto = $foto . '.' . $arr_file_ext[1];
                       
                        if (isset($arr_file_ext[0]) && $arr_file_ext[0] != "") {
                            //$oldFile = $this->folder_cv . '/' . $foto;
                            $oldFile1 = $this->folder_cv . '/' . $foto;
                            $persona_model = Persona::findOne(["per_id" => $per_id]);
                            Utilities::putMessageLogFile('ruta foto:' . $this->folder_cv . '/' . $per_id . "_" . $foto);
                            $persona_model->per_foto = $this->folder_cv . '/' . $per_id . "_" . $foto;

                            \app\models\Utilities::putMessageLogFile('1118    $persona_model->per_foto : ' .$persona_model->per_foto );  
                            //\app\models\Utilities::putMessageLogFile('1119     $per_id : ' .$per_id );  

                            $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                            rename($urlBase . $oldFile1, $urlBase . $persona_model->per_foto);
                            $persona_model->update();
                        }

                        /*    $per_id = $persona_model->getPrimaryKey();
                          \app\models\Utilities::putMessageLogFile('imagen: '.$foto);
                          $arr_file = explode($foto, '.jpg');
                          \app\models\Utilities::putMessageLogFile('imagen-aaa: '.$arr_file[0]);
                          if (isset($arr_file[0]) && $arr_file[0] != "") {
                          $oldFile = $this->folder_cv . '/' . $foto;
                          \app\models\Utilities::putMessageLogFile('oldFile: '.$oldFile);
                          $persona_model = Persona::findOne(["per_id" => $per_id]);
                          \app\models\Utilities::putMessageLogFile('ruta foto:' . $this->folder_cv.'/'. $per_id . "_" . $foto);
                          $persona_model->per_foto = $this->folder_cv . '/' . $per_id . "_" . $foto;
                          $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                          rename($urlBase . $oldFile, $urlBase . $persona_model->per_foto);
                          $persona_model->update();


                          } */
                        
                            
                        
                        $profesor_model = new Profesor();
                        $profesor_model->ddoc_id = $dedicacion;
                        $profesor_model->pro_num_contrato = $pro_num_contrato;
                        $profesor_model->per_id = $per_id;
                        $profesor_model->pro_estado = '1';
                        $profesor_model->pro_estado_logico = '1';
                        $profesor_model->pro_fecha_creacion = date("Y-m-d H:i:s");
                        $profesor_model->pro_usuario_ingreso = $user_ingresa;
                        if($profesor_model->save()){
                           \app\models\Utilities::putMessageLogFile('persona: '  );   
                        }else{
                           \app\models\Utilities::putMessageLogFile('erro: ' .$dedicacion );    
                        }
                        
                        $usuario_model = new Usuario();
                        $usuario_model->per_id = $per_id;
                        $usuario_model->usu_user = $usuario;
                        $usuario_model->generateAuthKey();
                        $usuario_model->setPassword($clave);
                        $usuario_model->usu_estado = '1';
                        $usuario_model->usu_estado_logico = '1';
                        $usuario_model->save();
                        $usu_id = $usuario_model->getPrimaryKey();

                        $empresa_persona_model = new EmpresaPersona();
                        $empresa_persona_model->emp_id = $emp_id;
                        $empresa_persona_model->per_id = $per_id;
                        $empresa_persona_model->eper_estado = '1';
                        $empresa_persona_model->eper_estado_logico = '1';
                        $empresa_persona_model->save();
                        $eper_id = $empresa_persona_model->getPrimaryKey();

                        $usua_grol_eper_model = new UsuaGrolEper();
                        $usua_grol_eper_model->eper_id = $eper_id;
                        $usua_grol_eper_model->usu_id = $usu_id;
                        $usua_grol_eper_model->grol_id = $arr_grupo_rol[0]['grol_id'];
                        $usua_grol_eper_model->ugep_estado = '1';
                        $usua_grol_eper_model->ugep_estado_logico = '1';
                        $usua_grol_eper_model->save();

                        /** Se agregan Informacion de Expediente * */
                        if (isset($arr_instuccion)) {
                            foreach ($arr_instuccion as $key0 => $value0) {
                                if ($value0[6] == "N") {
                                    $instruccion_model = new ProfesorInstruccion();
                                    $instruccion_model->nins_id = $value0[1];
                                    $instruccion_model->pins_institucion = ucwords($value0[2]);
                                    $instruccion_model->pins_especializacion = ucwords($value0[3]);
                                    $instruccion_model->pins_titulo = ucwords($value0[4]);
                                    $instruccion_model->pins_senescyt = strtolower($value0[5]);
                                    $instruccion_model->pro_id = $profesor_model->pro_id;
                                    $instruccion_model->pins_estado = '1';
                                    $instruccion_model->pins_estado_logico = '1';
                                    $instruccion_model->pins_usuario_ingreso = $user_ingresa;
                                    $instruccion_model->save();
                                }
                            }
                        }
                        if (isset($arr_docencia)) {
                            foreach ($arr_docencia as $key1 => $value1) {
                                if ($value1[6] == "N") {
                                    $docencia_model = new ProfesorExpDoc();
                                    $docencia_model->ins_id = $value1[1];
                                    $docencia_model->pedo_fecha_inicio = $value1[2];
                                    $docencia_model->pedo_fecha_fin = $value1[3];
                                    $docencia_model->pedo_denominacion = ucwords($value1[4]);
                                    $docencia_model->pedo_asignaturas = ucwords($value1[5]);
                                    $docencia_model->pro_id = $profesor_model->pro_id;
                                    $docencia_model->pedo_estado = '1';
                                    $docencia_model->pedo_estado_logico = '1';
                                    $docencia_model->pedo_usuario_ingreso = $user_ingresa;
                                    $docencia_model->save();
                                }
                            }
                        }
                        if (isset($arr_experiencia)) {
                            foreach ($arr_experiencia as $key2 => $value2) {
                                if ($value2[6] == "N") {
                                    $experiencia_model = new ProfesorExpProf();
                                    $experiencia_model->pepr_organizacion = strtolower($value2[1]);
                                    $experiencia_model->pepr_fecha_inicio = $value2[2];
                                    $experiencia_model->pepr_fecha_fin = $value2[3];
                                    $experiencia_model->pepr_denominacion = ucwords($value2[4]);
                                    $experiencia_model->pepr_funciones = ucwords($value2[5]);
                                    $experiencia_model->pro_id = $profesor_model->pro_id;
                                    $experiencia_model->pepr_estado = '1';
                                    $experiencia_model->pepr_estado_logico = '1';
                                    $experiencia_model->pepr_usuario_ingreso = $user_ingresa;
                                    $experiencia_model->save();
                                }
                            }
                        }
                        if (isset($arr_idioma)) {
                            foreach ($arr_idioma as $key3 => $value3) {
                                if ($value3[6] == "N") {
                                    $idiomas_model = new ProfesorIdiomas();
                                    $idiomas_model->idi_id = $value3[1];
                                    $idiomas_model->pidi_nivel_escrito = ucfirst($value3[2]);
                                    $idiomas_model->pidi_nivel_oral = ucfirst($value3[3]);
                                    $idiomas_model->pidi_certificado = ucfirst($value3[4]);
                                    $idiomas_model->pidi_institucion = ucwords($value3[5]);
                                    $idiomas_model->pro_id = $profesor_model->pro_id;
                                    $idiomas_model->pidi_estado = '1';
                                    $idiomas_model->pidi_estado_logico = '1';
                                    $idiomas_model->pidi_usuario_ingreso = $user_ingresa;
                                    $idiomas_model->save();
                                }
                            }
                        }
                        if (isset($arr_investigacion)) {
                            foreach ($arr_investigacion as $key4 => $value4) {
                                if ($value4[7] == "N") {
                                    $investigacion_model = new ProfesorInvestigacion();
                                    $investigacion_model->pinv_proyecto = ucwords($value4[1]);
                                    $investigacion_model->pinv_ambito = ucwords($value4[2]);
                                    $investigacion_model->pinv_responsabilidad = ucwords($value4[3]);
                                    $investigacion_model->pinv_entidad = ucwords($value4[4]);
                                    $investigacion_model->pinv_anio = strtolower($value4[5]);
                                    $investigacion_model->pinv_duracion = strtolower($value4[6]);
                                    $investigacion_model->pro_id = $profesor_model->pro_id;
                                    $investigacion_model->pinv_estado = '1';
                                    $investigacion_model->pinv_estado_logico = '1';
                                    $investigacion_model->pinv_usuario_ingreso = $user_ingresa;
                                    $investigacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_evento)) {
                            foreach ($arr_evento as $key5 => $value5) {
                                if ($value5[6] == "N") {
                                    $capacitacion_model = new ProfesorCapacitacion();
                                    $capacitacion_model->pcap_tipo = strtolower($value5[4]);
                                    $capacitacion_model->pcap_evento = ucwords($value5[1]);
                                    $capacitacion_model->pcap_institucion = ucwords($value5[2]);
                                    $capacitacion_model->pcap_anio = strtolower($value5[3]);
                                    $capacitacion_model->pcap_duracion = strtolower($value5[5]);
                                    $capacitacion_model->pro_id = $profesor_model->pro_id;
                                    $capacitacion_model->pcap_estado = '1';
                                    $capacitacion_model->pcap_estado_logico = '1';
                                    $capacitacion_model->pcap_usuario_ingreso = $user_ingresa;
                                    $capacitacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_conferencia)) {
                            foreach ($arr_conferencia as $key6 => $value6) {
                                if ($value6[5] == "N") {
                                    $capacitacion_model = new ProfesorConferencia();
                                    $capacitacion_model->pcon_evento = ucwords($value6[1]);
                                    $capacitacion_model->pcon_institucion = ucwords($value6[2]);
                                    $capacitacion_model->pcon_anio = strtolower($value6[3]);
                                    $capacitacion_model->pcon_ponencia = ucwords($value6[4]);
                                    $capacitacion_model->pro_id = $profesor_model->pro_id;
                                    $capacitacion_model->pcon_estado = '1';
                                    $capacitacion_model->pcon_estado_logico = '1';
                                    $capacitacion_model->pcon_usuario_ingreso = $user_ingresa;
                                    $capacitacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_coordinacion)) {
                            foreach ($arr_coordinacion as $key7 => $value7) {
                                if ($value7[6] == "N") {
                                    $coordinacion_model = new ProfesorCoordinacion();
                                    $coordinacion_model->pcoo_alumno = ucwords($value7[1]);
                                    $coordinacion_model->pcoo_programa = ucwords($value7[2]);
                                    $coordinacion_model->pcoo_academico = ucwords($value7[3]);
                                    $coordinacion_model->ins_id = ($value7[4]);
                                    $coordinacion_model->pcoo_anio = strtolower($value7[5]);
                                    $coordinacion_model->pro_id = $profesor_model->pro_id;
                                    $coordinacion_model->pcoo_estado = '1';
                                    $coordinacion_model->pcoo_estado_logico = '1';
                                    $coordinacion_model->pcoo_usuario_ingreso = $user_ingresa;
                                    $coordinacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_evaluacion)) {
                            foreach ($arr_evaluacion as $key8 => $value8) {
                                if ($value8[4] == "N") {
                                    $evaluacion_model = new ProfesorEvaluacion();
                                    $evaluacion_model->peva_periodo = strtolower($value8[1]);
                                    $evaluacion_model->peva_institucion = ucwords($value8[2]);
                                    $evaluacion_model->peva_evaluacion = ucwords($value8[3]);
                                    $evaluacion_model->pro_id = $profesor_model->pro_id;
                                    $evaluacion_model->peva_estado = '1';
                                    $evaluacion_model->peva_estado_logico = '1';
                                    $evaluacion_model->peva_usuario_ingreso = $user_ingresa;
                                    $evaluacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_publicacion)) {
                            foreach ($arr_publicacion as $key9 => $value9) {
                                if ($value9[6] == "N") {
                                    $publicacion_model = new ProfesorPublicacion();
                                    $publicacion_model->tpub_id = $value9[1];
                                    $publicacion_model->ppub_titulo = ucwords($value9[2]);
                                    $publicacion_model->ppub_editorial = ucwords($value9[3]);
                                    $publicacion_model->ppub_isbn = strtolower($value9[4]);
                                    $publicacion_model->ppub_autoria = ucwords($value9[5]);
                                    $publicacion_model->pro_id = $profesor_model->pro_id;
                                    $publicacion_model->ppub_estado = '1';
                                    $publicacion_model->ppub_estado_logico = '1';
                                    $publicacion_model->ppub_usuario_ingreso = $user_ingresa;
                                    $publicacion_model->save();
                                }
                            }
                        }
                        if (isset($arr_referencia)) {
                            foreach ($arr_referencia as $key10 => $value10) {
                                if ($value10[5] == "N") {
                                    $referencia_model = new ProfesorReferencia();
                                    $referencia_model->pref_contacto = ucwords($value10[1]);
                                    $referencia_model->pref_relacion_cargo = ucwords($value10[2]);
                                    $referencia_model->pref_organizacion = ucwords($value10[3]);
                                    $referencia_model->pref_numero = strtolower($value10[4]);
                                    $referencia_model->pro_id = $profesor_model->pro_id;
                                    $referencia_model->pref_estado = '1';
                                    $referencia_model->pref_estado_logico = '1';
                                    $referencia_model->pref_usuario_ingreso = $user_ingresa;
                                    $referencia_model->save();
                                }
                            }
                        }
                        //  $transaction->commit();
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    } else {
                        //    $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
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

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $per_id = $data["per_id"];

                /* Validacion de acceso a vistas por usuario */
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $user_usermane = Yii::$app->session->get("PB_username");
                $user_perId = Yii::$app->session->get("PB_perid");
                $grupo_model = new Grupo();
                $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
                if ($per_id != $user_perId) {
                    // if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos)  && !in_array(['id' => '15'], $arr_grupos))

                    if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '6'], $arr_grupos) && !in_array(['id' => '7'], $arr_grupos) && !in_array(['id' => '8'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                        return $this->redirect(['profesor/index']);
                }

                /**
                 * Inf. Basica
                 */
                $pri_nombre = $data["pri_nombre"];
                $seg_nombre = $data["seg_nombre"];
                $pri_apellido = $data["pri_apellido"];
                $seg_apellido = $data["seg_apellido"];
                $cedula = $data["cedula"];
                $ruc = $data["ruc"];
                $pasaporte = $data["pasaporte"];
                $correo = strtolower($data["correo"]);
                $nacionalidad = $data["nacionalidad"];
                $celular = $data["celular"];
                $phone = $data["phone"];
                $dedicacion = $data["dedicacion"];
                $pro_num_contrato = $data["pro_num_contrato"];
                $fecha_nacimiento = $data["fecha_nacimiento"];
                $foto = $data['foto'];

                /**
                 * Inf. Domicilio
                 */
                $pai_id_domicilio = $data["pai_id"];
                $pro_id_domicilio = $data["pro_id"];
                $can_id_domicilio = $data["can_id"];
                $sector = ucwords($data["sector"]);
                $calle_pri = ucwords($data["calle_pri"]);
                $calle_sec = ucwords($data["calle_sec"]);
                $numeracion = ucwords($data["numeracion"]);
                $referencia = ucwords($data["referencia"]);

                /**
                 * Inf. Cuenta
                 */
                $usuario = strtolower($data["usuario"]);
                $clave = $data["clave"];
                $gru_id = $data["gru_id"];
                $rol_id = $data["rol_id"];
                $emp_id = $data["emp_id"];

                $persona_model = Persona::findOne($per_id);
                $persona_model->per_pri_nombre = $pri_nombre;
                $persona_model->per_seg_nombre = $seg_nombre;
                $persona_model->per_pri_apellido = $pri_apellido;
                $persona_model->per_seg_apellido = $seg_apellido;
                $persona_model->per_cedula = $cedula;
                if ($ruc != "") {
                    $persona_model->per_ruc = $ruc;
                }
                if ($pasaporte != "") {
                    $persona_model->per_pasaporte = $pasaporte;
                }
                $persona_model->per_correo = $correo;
                $persona_model->per_nacionalidad = $nacionalidad;
                $persona_model->per_celular = $celular;
                $persona_model->per_domicilio_telefono = $phone;
                $persona_model->per_fecha_nacimiento = $fecha_nacimiento;
                $persona_model->pai_id_domicilio = $pai_id_domicilio;
                $persona_model->pro_id_domicilio = $pro_id_domicilio;
                $persona_model->can_id_domicilio = $can_id_domicilio;
                $persona_model->per_domicilio_sector = $sector;
                $persona_model->per_domicilio_cpri = $calle_pri;
                $persona_model->per_domicilio_csec = $calle_sec;
                $persona_model->per_domicilio_num = $numeracion;
                $persona_model->per_domicilio_ref = $referencia;
                $arr_file = explode($foto, '.jpg');
                if (isset($arr_file[0]) && $arr_file[0] != "") {
                    $oldFile = $this->folder_cv . '/' . $foto;
                    $persona_model->per_foto = $this->folder_cv . '/' . $per_id . "_" . $foto;
                    $urlBase = Yii::$app->basePath . Yii::$app->params["documentFolder"];
                    rename($urlBase . $oldFile, $urlBase . $persona_model->per_foto);
                }

                /**
                 * Inf. Session Storages
                 */
                $arr_instuccion = (isset($data["grid_instruccion_list"]) && $data["grid_instruccion_list"] != "") ? $data["grid_instruccion_list"] : NULL;
                $arr_docencia = (isset($data["grid_docencia_list"]) && $data["grid_docencia_list"] != "") ? $data["grid_docencia_list"] : NULL;
                $arr_experiencia = (isset($data["grid_experiencia_list"]) && $data["grid_experiencia_list"] != "") ? $data["grid_experiencia_list"] : NULL;
                $arr_idioma = (isset($data["grid_idioma_list"]) && $data["grid_idioma_list"] != "") ? $data["grid_idioma_list"] : NULL;
                $arr_investigacion = (isset($data["grid_investigacion_list"]) && $data["grid_investigacion_list"] != "") ? $data["grid_investigacion_list"] : NULL;
                $arr_evento = (isset($data["grid_evento_list"]) && $data["grid_evento_list"] != "") ? $data["grid_evento_list"] : NULL;
                $arr_conferencia = (isset($data["grid_conferencia_list"]) && $data["grid_conferencia_list"] != "") ? $data["grid_conferencia_list"] : NULL;
                $arr_publicacion = (isset($data["grid_publicacion_list"]) && $data["grid_publicacion_list"] != "") ? $data["grid_publicacion_list"] : NULL;
                $arr_coordinacion = (isset($data["grid_coordinacion_list"]) && $data["grid_coordinacion_list"] != "") ? $data["grid_coordinacion_list"] : NULL;
                $arr_evaluacion = (isset($data["grid_evaluacion_list"]) && $data["grid_evaluacion_list"] != "") ? $data["grid_evaluacion_list"] : NULL;
                $arr_referencia = (isset($data["grid_referencia_list"]) && $data["grid_referencia_list"] != "") ? $data["grid_referencia_list"] : NULL;

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );

                if ($persona_model->save()) {
                    $usuario_model = Usuario::findOne(["per_id" => $per_id]);

                    /** Se agregan Informacion de Expediente * */
                    $profesor_model = Profesor::findOne(["per_id" => $per_id]);
                    $profesor_model->ddoc_id = $dedicacion;
                    $profesor_model->pro_num_contrato = $pro_num_contrato;
                    $profesor_model->save();
                    //ProfesorInstruccion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_instuccion)) {
                        foreach ($arr_instuccion as $key0 => $value0) {
                            if ($value0[6] == "N") {
                                $instruccion_model = new ProfesorInstruccion();
                                $instruccion_model->nins_id = $value0[1];
                                $instruccion_model->pins_institucion = ucwords($value0[2]);
                                $instruccion_model->pins_especializacion = ucwords($value0[3]);
                                $instruccion_model->pins_titulo = ucwords($value0[4]);
                                $instruccion_model->pins_senescyt = strtoupper($value0[5]);
                                $instruccion_model->pro_id = $profesor_model->pro_id;
                                $instruccion_model->pins_estado = '1';
                                $instruccion_model->pins_estado_logico = '1';
                                $instruccion_model->pins_usuario_ingreso = $user_ingresa;
                                $instruccion_model->save();
                            }
                        }
                    }
                    //ProfesorExpDoc::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_docencia)) {
                        foreach ($arr_docencia as $key1 => $value1) {
                            if ($value1[6] == "N") {
                                $docencia_model = new ProfesorExpDoc();
                                $docencia_model->ins_id = $value1[1];
                                $docencia_model->pedo_fecha_inicio = $value1[2];
                                $docencia_model->pedo_fecha_fin = $value1[3];
                                $docencia_model->pedo_denominacion = ucwords($value1[4]);
                                $docencia_model->pedo_asignaturas = ucwords($value1[5]);
                                $docencia_model->pro_id = $profesor_model->pro_id;
                                $docencia_model->pedo_estado = '1';
                                $docencia_model->pedo_estado_logico = '1';
                                $docencia_model->pedo_usuario_ingreso = $user_ingresa;
                                $docencia_model->save();
                            }
                        }
                    }
                    //ProfesorExpProf::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_experiencia)) {
                        foreach ($arr_experiencia as $key2 => $value2) {
                            if ($value2[6] == "N") {
                                $experiencia_model = new ProfesorExpProf();
                                $experiencia_model->pepr_organizacion = ucwords($value2[1]);
                                $experiencia_model->pepr_fecha_inicio = $value2[2];
                                $experiencia_model->pepr_fecha_fin = $value2[3];
                                $experiencia_model->pepr_denominacion = ucwords($value2[4]);
                                $experiencia_model->pepr_funciones = ucwords($value2[5]);
                                $experiencia_model->pro_id = $profesor_model->pro_id;
                                $experiencia_model->pepr_estado = '1';
                                $experiencia_model->pepr_estado_logico = '1';
                                $experiencia_model->pepr_usuario_ingreso = $user_ingresa;
                                $experiencia_model->save();
                            }
                        }
                    }
                    //ProfesorIdiomas::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_idioma)) {
                        foreach ($arr_idioma as $key3 => $value3) {
                            if ($value3[6] == "N") {
                                $idiomas_model = new ProfesorIdiomas();
                                $idiomas_model->idi_id = $value3[1];
                                $idiomas_model->pidi_nivel_escrito = ucfirst($value3[2]);
                                $idiomas_model->pidi_nivel_oral = ucfirst($value3[3]);
                                $idiomas_model->pidi_certificado = ucfirst($value3[4]);
                                $idiomas_model->pidi_institucion = ucwords($value3[5]);
                                $idiomas_model->pro_id = $profesor_model->pro_id;
                                $idiomas_model->pidi_estado = '1';
                                $idiomas_model->pidi_estado_logico = '1';
                                $idiomas_model->pidi_usuario_ingreso = $user_ingresa;
                                $idiomas_model->save();
                            }
                        }
                    }
                    //ProfesorInvestigacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_investigacion)) {
                        foreach ($arr_investigacion as $key4 => $value4) {
                            if ($value4[7] == "N") {
                                $investigacion_model = new ProfesorInvestigacion();
                                $investigacion_model->pinv_proyecto = ucwords($value4[1]);
                                $investigacion_model->pinv_ambito = ucwords($value4[2]);
                                $investigacion_model->pinv_responsabilidad = ucwords($value4[3]);
                                $investigacion_model->pinv_entidad = ucwords($value4[4]);
                                $investigacion_model->pinv_anio = strtolower($value4[5]);
                                $investigacion_model->pinv_duracion = strtolower($value4[6]);
                                $investigacion_model->pro_id = $profesor_model->pro_id;
                                $investigacion_model->pinv_estado = '1';
                                $investigacion_model->pinv_estado_logico = '1';
                                $investigacion_model->pinv_usuario_ingreso = $user_ingresa;
                                $investigacion_model->save();
                            }
                        }
                    }
                    //ProfesorCapacitacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evento)) {
                        foreach ($arr_evento as $key5 => $value5) {
                            if ($value5[6] == "N") {
                                $capacitacion_model = new ProfesorCapacitacion();
                                $capacitacion_model->pcap_tipo = strtolower($value5[4]);
                                $capacitacion_model->pcap_evento = ucwords($value5[1]);
                                $capacitacion_model->pcap_institucion = ucwords($value5[2]);
                                $capacitacion_model->pcap_anio = strtolower($value5[3]);
                                $capacitacion_model->pcap_duracion = strtolower($value5[5]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcap_estado = '1';
                                $capacitacion_model->pcap_estado_logico = '1';
                                $capacitacion_model->pcap_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorConferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_conferencia)) {
                        foreach ($arr_conferencia as $key6 => $value6) {
                            if ($value6[5] == "N") {
                                $capacitacion_model = new ProfesorConferencia();
                                $capacitacion_model->pcon_evento = ucwords($value6[1]);
                                $capacitacion_model->pcon_institucion = ucwords($value6[2]);
                                $capacitacion_model->pcon_anio = strtolower($value6[3]);
                                $capacitacion_model->pcon_ponencia = ucwords($value6[4]);
                                $capacitacion_model->pro_id = $profesor_model->pro_id;
                                $capacitacion_model->pcon_estado = '1';
                                $capacitacion_model->pcon_estado_logico = '1';
                                $capacitacion_model->pcon_usuario_ingreso = $user_ingresa;
                                $capacitacion_model->save();
                            }
                        }
                    }
                    //ProfesorCoordinacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_coordinacion)) {
                        foreach ($arr_coordinacion as $key7 => $value7) {
                            if ($value7[6] == "N") {
                                $coordinacion_model = new ProfesorCoordinacion();
                                $coordinacion_model->pcoo_alumno = ucwords($value7[1]);
                                $coordinacion_model->pcoo_programa = ucwords($value7[2]);
                                $coordinacion_model->pcoo_academico = ucwords($value7[3]);
                                $coordinacion_model->ins_id = ($value7[4]);
                                $coordinacion_model->pcoo_anio = strtolower($value7[5]);
                                $coordinacion_model->pro_id = $profesor_model->pro_id;
                                $coordinacion_model->pcoo_estado = '1';
                                $coordinacion_model->pcoo_estado_logico = '1';
                                $coordinacion_model->pcoo_usuario_ingreso = $user_ingresa;
                                $coordinacion_model->save();
                            }
                        }
                    }
                    //ProfesorEvaluacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_evaluacion)) {
                        foreach ($arr_evaluacion as $key8 => $value8) {
                            if ($value8[4] == "N") {
                                $evaluacion_model = new ProfesorEvaluacion();
                                $evaluacion_model->peva_periodo = strtolower($value8[1]);
                                $evaluacion_model->peva_institucion = ucwords($value8[2]);
                                $evaluacion_model->peva_evaluacion = ucwords($value8[3]);
                                $evaluacion_model->pro_id = $profesor_model->pro_id;
                                $evaluacion_model->peva_estado = '1';
                                $evaluacion_model->peva_estado_logico = '1';
                                $evaluacion_model->peva_usuario_ingreso = $user_ingresa;
                                $evaluacion_model->save();
                            }
                        }
                    }
                    //ProfesorPublicacion::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_publicacion)) {
                        foreach ($arr_publicacion as $key9 => $value9) {
                            if ($value9[6] == "N") {
                                $publicacion_model = new ProfesorPublicacion();
                                $publicacion_model->tpub_id = $value9[1];
                                $publicacion_model->ppub_titulo = ucwords($value9[2]);
                                $publicacion_model->ppub_editorial = ucwords($value9[3]);
                                $publicacion_model->ppub_isbn = strtoupper($value9[4]);
                                $publicacion_model->ppub_autoria = ucwords($value9[5]);
                                $publicacion_model->pro_id = $profesor_model->pro_id;
                                $publicacion_model->ppub_estado = '1';
                                $publicacion_model->ppub_estado_logico = '1';
                                $publicacion_model->ppub_usuario_ingreso = $user_ingresa;
                                $publicacion_model->save();
                            }
                        }
                    }
                    //ProfesorReferencia::deleteAllInfo($profesor_model->pro_id);
                    if (isset($arr_referencia)) {
                        foreach ($arr_referencia as $key10 => $value10) {
                            if ($value10[5] == "N") {
                                $referencia_model = new ProfesorReferencia();
                                $referencia_model->pref_contacto = ucwords($value10[1]);
                                $referencia_model->pref_relacion_cargo = ucwords($value10[2]);
                                $referencia_model->pref_organizacion = ucwords($value10[3]);
                                $referencia_model->pref_numero = strtolower($value10[4]);
                                $referencia_model->pro_id = $profesor_model->pro_id;
                                $referencia_model->pref_estado = '1';
                                $referencia_model->pref_estado_logico = '1';
                                $referencia_model->pref_usuario_ingreso = $user_ingresa;
                                $referencia_model->save();
                            }
                        }
                    }
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no creado.');
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

    public function actionDelete() {
        \app\models\Utilities::putMessageLogFile('saludos-1');
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $per_id = $data["per_id"];
                $persona_model = Persona::findOne($per_id);
                $persona_model->per_estado_logico = '0';
                $persona_model->per_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

                /* Validacion de acceso a vistas por usuario */
                $user_ingresa = Yii::$app->session->get("PB_iduser");
                $user_usermane = Yii::$app->session->get("PB_username");
                $user_perId = Yii::$app->session->get("PB_perid");
                $grupo_model = new Grupo();
                $arr_grupos = $grupo_model->getAllGruposByUser($user_usermane);
                if (!in_array(['id' => '1'], $arr_grupos) && !in_array(['id' => '15'], $arr_grupos))
                    return $this->redirect(['profesor/index']);

                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($persona_model->update() !== false) {
                    $profesor_model = Profesor::findOne(["per_id" => $per_id]);
                    $profesor_model->pro_estado_logico = '0';
                    $profesor_model->pro_usuario_modifica = $user_ingresa;
                    $profesor_model->pro_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $profesor_model->update();

                    $usuario_model = Usuario::findOne(["per_id" => $per_id]);
                    $usu_id = $usuario_model->usu_id;
                    $usuario_model->usu_estado_logico = '0';
                    $usuario_model->usu_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $usuario_model->update();

                    $empresa_persona_model = EmpresaPersona::findOne(["per_id" => $per_id]);
                    $empresa_persona_model->eper_estado_logico = '0';
                    $empresa_persona_model->eper_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $empresa_persona_model->update();

                    $usua_grol_eper_model = UsuaGrolEper::findOne(["usu_id" => $usu_id]);
                    $usua_grol_eper_model->ugep_estado_logico = '0';
                    $usua_grol_eper_model->ugep_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $usua_grol_eper_model->update();
                    //\app\models\Utilities::putMessageLogFile('despues de actualizar Usuario Grupo Rol');

                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no ha sido eliminado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDownload($route, $type) {
        $grupo = new Grupo();
        if (Yii::$app->session->get('PB_isuser')) {
            $route = str_replace("../", "", $route);
            if (preg_match("/^" . $this->folder_cv . "\//", $route)) {
                $url_image = Yii::$app->basePath . "/uploads/" . $route;
                $arrIm = explode(".", $url_image);
                $typeImage = $arrIm[count($arrIm) - 1];
                if (file_exists($url_image)) {
                    if (strtolower($typeImage) == "pdf") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/pdf");
                        if ($type == "view") {
                            header('Content-Disposition: inline; filename="cv_' . time() . '.pdf";');
                        } else {
                            header('Content-Disposition: attachment; filename="cv_' . time() . '.pdf";');
                        }
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_image));
                        readfile($url_image);
                        //return file_get_contents($url_image);
                    }
                }
            }
        }
        exit();
    }

    public function actionEliminaritems() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user = Yii::$app->session->get("PB_iduser");
            $id = $data["codigo_id"];
            $tabla = $data["tabla_id"];
            try {
                if ($tabla == 1) {
                    $instruccion_model = ProfesorInstruccion::findOne(["pins_id" => $id]);
                    $instruccion_model->pins_estado_logico = '0';
                    $instruccion_model->pins_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $instruccion_model->pins_usuario_modifica = $user;
                    $instruccion_model->update();
                }
                if ($tabla == 2) {
                    $docencia_model = ProfesorExpDoc::findOne(["pedo_id" => $id]);
                    $docencia_model->pedo_estado_logico = '0';
                    $docencia_model->pedo_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $docencia_model->pedo_usuario_modifica = $user;
                    $docencia_model->update();
                }
                if ($tabla == 3) {
                    $experiencia_model = ProfesorExpProf::findOne(["pepr_id" => $id]);
                    $experiencia_model->pepr_estado_logico = '0';
                    $experiencia_model->pepr_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $experiencia_model->pepr_usuario_modifica = $user;
                    $experiencia_model->update();
                }
                if ($tabla == 4) {
                    $idiomas_model = ProfesorIdiomas::findOne(["pidi_id" => $id]);
                    $idiomas_model->pidi_estado_logico = '0';
                    $idiomas_model->pidi_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $idiomas_model->pidi_usuario_modifica = $user;
                    $idiomas_model->update();
                }
                if ($tabla == 5) {
                    $investigacion_model = ProfesorInvestigacion::findOne(["pinv_id" => $id]);
                    $investigacion_model->pinv_estado_logico = '0';
                    $investigacion_model->pinv_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $investigacion_model->pinv_usuario_modifica = $user;
                    $investigacion_model->update();
                }
                if ($tabla == 6) {
                    $capacitacion_model = ProfesorCapacitacion::findOne(["pcap_id" => $id]);
                    $capacitacion_model->pcap_estado_logico = '0';
                    $capacitacion_model->pcap_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $capacitacion_model->pcap_usuario_modifica = $user;
                    $capacitacion_model->update();
                }
                if ($tabla == 7) {
                    $conferencia_model = ProfesorConferencia::findOne(["pcon_id" => $id]);
                    $conferencia_model->pcon_estado_logico = '0';
                    $conferencia_model->pcon_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $conferencia_model->pcon_usuario_modifica = $user;
                    $conferencia_model->update();
                }
                if ($tabla == 8) {
                    $publicacion_model = ProfesorPublicacion::findOne(["ppub_id" => $id]);
                    $publicacion_model->ppub_estado_logico = '0';
                    $publicacion_model->ppub_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $publicacion_model->ppub_usuario_modifica = $user;
                    $publicacion_model->update();
                }
                if ($tabla == 9) {
                    $coordinacion_model = ProfesorCoordinacion::findOne(["pcoo_id" => $id]);
                    $coordinacion_model->pcoo_estado_logico = '0';
                    $coordinacion_model->pcoo_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $coordinacion_model->pcoo_usuario_modifica = $user;
                    $coordinacion_model->update();
                }
                if ($tabla == 10) {
                    $evaluacion_model = ProfesorEvaluacion::findOne(["peva_id" => $id]);
                    $evaluacion_model->peva_estado_logico = '0';
                    $evaluacion_model->peva_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $evaluacion_model->peva_usuario_modifica = $user;
                    $evaluacion_model->update();
                }
                if ($tabla == 11) {
                    $referencia_model = ProfesorReferencia::findOne(["pref_id" => $id]);
                    $referencia_model->pref_estado_logico = '0';
                    $referencia_model->pref_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                    $referencia_model->pref_usuario_modifica = $user;
                    $referencia_model->update();
                }
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionCurriculumpdf($ids) {//ok
        try {
            $ids = $_GET['ids'];
            $persona_model = Persona::findOne($ids);
            $ciudad = new Canton();
            $canton = $ciudad->NombrecantonXid($persona_model['can_id_domicilio']);
            $instruccion_model = new ProfesorInstruccion();
            $profesor_model = Profesor::findOne(['per_id' => $persona_model->per_id]);
            $proExpDoc = new ProfesorExpDoc();
            $proExpPro = new ProfesorExpProf();
            $proIdiomas = new ProfesorIdiomas();
            $proInvestigacion = new ProfesorInvestigacion();
            $proCap = new ProfesorCapacitacion();
            $proConf = new ProfesorConferencia();
            $proPub = new ProfesorPublicacion();
            $proCoor = new ProfesorCoordinacion();
            $proRef = new ProfesorReferencia();

            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/tpl_profesor/main';
            setlocale(LC_TIME, 'es_CO.UTF-8');

            //$cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime(date("d-m-Y")));   
            $this->pdf_cla_acceso = $ids;
            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical   
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/tpl_profesor/profesor', [
                        'canton' => $canton,
                        'persona_model' => $persona_model, // 1
                        'instruccion' => $instruccion_model->getAllInstruccionGrid($profesor_model->pro_id, true), //2
                        'experienciadoc' => $proExpDoc->getAllExperienciaGrid($profesor_model->pro_id, true), //3
                        'experienciapro' => $proExpPro->getAllExperienciaGrid($profesor_model->pro_id, true), //4
                        'idioma' => $proIdiomas->getAllIdiomasGrid($profesor_model->pro_id, true), //5
                        'investigacion' => $proInvestigacion->getAllInvestigacionGrid($profesor_model->pro_id, true), //6
                        'capacitacion' => $proCap->getAllCapacitacionGrid($profesor_model->pro_id, true), //7
                        'conferencia' => $proConf->getAllConferenciaGrid($profesor_model->pro_id, true), //8
                        'publicacion' => $proPub->getAllPublicacionGrid($profesor_model->pro_id, true), //9
                        'coodirecion' => $proCoor->getAllCoordinacionGrid($profesor_model->pro_id, true), //10
                        'referencia' => $proRef->getAllReferenciaGrid($profesor_model->pro_id, true), //11
                    ])
            );
            $rep->mpdf->Output('PROFESOR_' . $ids . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
            //exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
