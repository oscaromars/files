<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\components\CController;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ForgotpassForm;
use app\models\UserPassreset;
use app\models\ChangepassForm;
use \yii\helpers\Url;
use app\models\Usuario;
use app\models\Utilities;
use app\models\Modulo;
use app\models\Grupo;
use app\models\Empresa;
use app\models\Dash;
use app\models\DashItem;
use app\models\EmpresaPersona;
use yii\base\Security;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\Profesor;
use app\models\Persona;
use app\models\UsuaGrolEper;
use yii\base\Exception;

class SiteController extends CController {

    protected $widthImg = "141";
    protected $heightImg = "193";

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['login', 'loginemp', 'logout'],
                'rules' => [
                        [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?', '@'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['loginemp'],
                        'roles' => ['?', '@'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'], // usuarios autenticados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['activation'],
                        'roles' => ['?'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['forgotpass'],
                        'roles' => ['?'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['updatepass'],
                        'roles' => ['?'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['getimage'],
                        'roles' => ['?', '@'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['getcredencial'],
                        'roles' => ['?', '@'], // usuarios invitados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['dash'],
                        'roles' => ['@'], // usuarios autenticados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['resourcesfiles'],
                        'roles' => ['@'], // usuarios autenticados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['portalestudiante'],
                        'roles' => ['@'], // usuarios autenticados
                    ],
                        [
                        'allow' => true,
                        'actions' => ['changeempresa'],
                        'roles' => ['@'], // usuarios autenticados
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        if (\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }

        return $this->render('index');
    }

    /**
     * actionResourcesfiles
     *
     * @author Diana Lopez
     * @access 
     * @param 
     */
    public function actionResourcesfiles() {
        $folderResources = 'resourcesfiles';
        $root = Yii::$app->basePath . Yii::$app->params["documentFolder"] . $folderResources;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $postDir = rawurldecode($root . (isset($data['dir']) ? $data['dir'] : null ));
            $checkbox = ( isset($data['multiSelect']) && $data['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
            $onlyFolders = ( isset($data['onlyFolders']) && $data['onlyFolders'] == 'true' ) ? true : false;
            $onlyFiles = ( isset($data['onlyFiles']) && $data['onlyFiles'] == 'true' ) ? true : false;

            if (file_exists($postDir)) {
                $files = scandir($postDir);
                $returnDir = substr($postDir, strlen($root));
                $htmlCode = "";
                natcasesort($files);
                if (count($files) > 2) { // The 2 accounts for . and ..
                    $htmlCode .= "<ul class='jqueryFileTree'>";
                    foreach ($files as $file) {
                        $htmlRel = htmlentities($returnDir . $file, ENT_QUOTES);
                        $htmlName = htmlentities($file);
                        $ext = preg_replace('/^.*\./', '', $file);
                        if (file_exists($postDir . $file) && $file != '.' && $file != '..') {
                            if (is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders))
                                $htmlCode .= "<li class='directory collapsed'>{$checkbox}<a rel='" . $htmlRel . "/'>" . $htmlName . "</a></li>";
                            else if (!$onlyFolders || $onlyFiles)
                                $htmlCode .= "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
                        }
                    }
                    $htmlCode .= "</ul>";
                    return $htmlCode;
                }
            }
            return;
        }else {
            $data = Yii::$app->request->get();
            if ($data["dfile"]) {
                $root = $root . str_replace("../", "", $data["dfile"]);
                if (file_exists($root)) {
                    $mimeType = Utilities::mimeContentType(basename($root));
                    Header("Content-type: $mimeType");
                    Header('Content-Disposition: attachment; filename="' . basename($root) . '"');
                    readfile($root);
                }
                return;
            }
        }
        $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/repositorio.php';
        return $this->render('resources', [
                    //'currentPath' => Url::base() . Yii::$app->params["documentFolder"] . '/resourcesfiles',
                    'rootfolder' => '/',
                    'script' => Url::base() . '/site/resourcesfiles',
        ]);
    }

    public function actionDash() {
        if (\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }
        $mod = new Modulo();
        $link = $mod->getFirstModuleLink();
        $url = Url::base(true) . "/" . $link["url"];
        $url_biblioteca = Yii::$app->params['url_biblioteca'];
        $url_educativa = Yii::$app->session->get("PB_educativa", "");//Yii::$app->params['url_educativa'];

        $modules = Dash::find(['dash_estado' => '1', 'dash_estado_logico' => '1'])->all();
        $dash_items = DashItem::find(['dite_estado' => '1', 'dite_estado_logico' => '1'])->all();
        $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/dash.php';
        return $this->render('dash', [
                    'modules' => $modules,
                    'dash_items' => $dash_items,
                    'url_video' => Url::base(true) . "/site/portalestudiante",
                    'url_asgard' => $url,
                    'url_educativa' => $url_educativa
        ]);
    }
    
    public function actionPortalestudiante(){
        if (\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }
        $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/dash.php';

        $modules_1 = [
                ['title' => 'Video 1',
                'sub_title' => 'Como ingresar al Campus Virtual UTEG',
                'detail' => 'https://player.vimeo.com/video/239000405',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 2',
                'sub_title' => 'Escritorio del Campus Virtual UTEG',
                'detail' => 'https://player.vimeo.com/video/238999051',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 3',
                'sub_title' => 'Como acceder a nuestra aula virtual',
                'detail' => 'https://player.vimeo.com/video/239000199',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 4',
                'sub_title' => 'Opciones del menú: "Introducción a la materia"',
                'detail' => 'https://player.vimeo.com/video/238998005',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 5',
                'sub_title' => 'Opciones del menú: "Material de estudio"',
                'detail' => 'https://player.vimeo.com/video/238997815',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 6',
                'sub_title' => 'Estructura de unidades de una materia',
                'detail' => 'https://player.vimeo.com/video/238999807',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 7',
                'sub_title' => 'Cronograma de actividades',
                'detail' => 'https://player.vimeo.com/video/239000087',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 8',
                'sub_title' => 'Acceder a bibliotecas y calificaciones',
                'detail' => 'https://player.vimeo.com/video/239022371',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 9',
                'sub_title' => 'Acceder a los foros',
                'detail' => 'https://player.vimeo.com/video/238998716',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 10',
                'sub_title' => 'Como acceder al Chat',
                'detail' => 'https://player.vimeo.com/video/238999673',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 11',
                'sub_title' => 'Como acceder a la clase en vivo',
                'detail' => 'https://player.vimeo.com/video/239000541',
                'link' => $url,
                'target' => ''],
                ['title' => 'Video 12',
                'sub_title' => 'Explicación para acceder al taller',
                'detail' => 'https://player.vimeo.com/video/213758590',
                'link' => $url,
                'target' => ''],
        ];


        /* Inicio - Para archivos descargables */
        $folderResources = 'recusos_portal'; //nombre de la carpeta para presentar Instructivos Generales 
        $root = Yii::$app->basePath . Yii::$app->params["documentFolder"] . $folderResources;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $postDir = rawurldecode($root . (isset($data['dir']) ? $data['dir'] : null ));
            $checkbox = ( isset($data['multiSelect']) && $data['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
            $onlyFolders = ( isset($data['onlyFolders']) && $data['onlyFolders'] == 'true' ) ? true : false;
            $onlyFiles = ( isset($data['onlyFiles']) && $data['onlyFiles'] == 'true' ) ? true : false;

            if (file_exists($postDir)) {
                $files = scandir($postDir);
                $returnDir = substr($postDir, strlen($root));
                $htmlCode = "";
                natcasesort($files);
                if (count($files) > 2) { // The 2 accounts for . and ..
                    $htmlCode .= "<ul class='jqueryFileTree'>";
                    foreach ($files as $file) {
                        $htmlRel = htmlentities($returnDir . $file, ENT_QUOTES);
                        $htmlName = htmlentities($file);
                        $ext = preg_replace('/^.*\./', '', $file);
                        if (file_exists($postDir . $file) && $file != '.' && $file != '..') {
                            if (is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders))
                                $htmlCode .= "<li class='directory collapsed'>{$checkbox}<a rel='" . $htmlRel . "/'>" . $htmlName . "</a></li>";
                            else if (!$onlyFolders || $onlyFiles)
                                $htmlCode .= "<li class='file ext_{$ext}'>{$checkbox}<a rel='" . $htmlRel . "'>" . $htmlName . "</a></li>";
                        }
                    }
                    $htmlCode .= "</ul>";
                    return $htmlCode;
                }
            }
            return;
        }else {
            $data = Yii::$app->request->get();
            if ($data["dfile"]) {
                $root = $root . str_replace("../", "", $data["dfile"]);
                if (file_exists($root)) {
                    $mimeType = Utilities::mimeContentType(basename($root));
                    Header("Content-type: $mimeType");
                    Header('Content-Disposition: attachment; filename="' . basename($root) . '"');
                    readfile($root);
                }
                return;
            }
        }
        $this->layout = '@themes/' . Yii::$app->getView()->theme->themeName . '/layouts/repositorio.php';

        /* Inicio - Para archivos descargables */

        return $this->render('portalestudiante', [
                    'modules_1' => $modules_1,
                    'modules_3' => $modules_3,
                    'modules_4' => $modules_4,
                    'modules_5' => $modules_5,
                    'modules_6' => $modules_6,
                    'modules_7' => $modules_7,
                    //'currentPath' => Url::base() . Yii::$app->params["documentFolder"] . '/resourcesfiles',
                    'rootfolder' => '/',
                    'script' => Url::base() . '/site/portalestudiante',
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }
        $model = new LoginForm();
        $empresa_alias = isset($_GET['emp'])?$_GET['emp']:NULL;
        
        if ($model->load(Yii::$app->request->post()) && $model->login($empresa_alias)) {
            // setting default url
            $mod = new Modulo();
            $link = $mod->getFirstModuleLink();
            $url = Url::base(true) . "/" . $link["url"];
            // preguntar el usuario en sesion tiene el grol_id=37 de estudiante mostrar el dash
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
            $per_id  = Yii::$app->session->get("PB_perid", FALSE);
            $modelEper = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
            $modelUGrlEper = new UsuaGrolEper();
            $modEper = $modelUGrlEper->consultarIdUsuaGrolEper($modelEper->eper_id, $user_id, '37');
            if($modEper != 0){
                $url = Url::base(true) . "/site/dash";
            }
            return $this->goBack($url);
        } else {
            if ($model->getErrorSession())
                Yii::$app->session->setFlash('loginFormSubmitted');
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/login.php', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Login action Multiple Empresa.
     *
     * @return string
     */
    public function actionLoginemp() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Url::base(true) . '/site/loginemp');
        }
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // setting default url
            $mod = new Modulo();
            $link = $mod->getFirstModuleLink();
            $url = Url::base(true) . "/" . $link["url"];
            return $this->goBack($url);
        } else {
            if ($model->getErrorSession())
                Yii::$app->session->setFlash('loginFormSubmitted');
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/loginemp.php', [
                        'model' => $model,
            ]);
        }
    }

    public function actionChangeempresa(){
        $id = isset($_GET['id'])?$_GET['id']:0;
        if($id > 0){
            $model_empresa = Empresa::findIdentity($id);
            Yii::$app->session->set('PB_idempresa',$id);
            Yii::$app->session->set('PB_empresa',$model_empresa->emp_nombre_comercial);
        }
        //return $this->redirect(Yii::$app->request->referrer);
        $mod = new Modulo();
        $link = $mod->getFirstModuleLink();
        $url = Url::base(true) . "/" . $link["url"];
        return $this->goBack($url);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout() {
        $usuario = new Usuario();
        $link1 = Utilities::getLoginUrl();
        //$usuario->destroySession();
        Yii::$app->user->logout(true);
        return $this->redirect(Url::base(true) . $link1);
    }

    public function actionForgotpass() {
        if (!\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }
        $model = new ForgotpassForm();
        if ($model->load(Yii::$app->request->post()) && $model->verificarCuenta()) {
            // se limpia los campos
            $model->unsetAttributes();
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/forgot.php', [
                        'model' => $model,
            ]);
        } else {
            if ($model->getErrorSession())
                Yii::$app->session->setFlash('forgotFormSubmitted');
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/forgot.php', [
                        'model' => $model,
            ]);
        }
    }
    
    public function actionForgotpassemp() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(Url::base(true) . 'site/loginemp');
        }
        $model = new ForgotpassForm();
        if ($model->load(Yii::$app->request->post()) && $model->verificarCuenta()) {
            // se limpia los campos
            $model->unsetAttributes();
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/forgotemp.php', [
                        'model' => $model,
            ]);
        } else {
            if ($model->getErrorSession())
                Yii::$app->session->setFlash('forgotFormSubmitted');
            return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/forgotemp.php', [
                        'model' => $model,
            ]);
        }
    }

    public function actionActivation() {
        $data = Yii::$app->request->get();
        if (isset($data["wg"])) {
            $link = Url::base(true) . "/site/activation?wg=" . $data["wg"];
            $usuario = Usuario::findOne(['usu_link_activo' => $link]);
            $status = false;
            if (isset($usuario)) {
                $status = $usuario->activarLinkCuenta($link);
            }
            if ($status) {
                Yii::$app->session->setFlash('success', Yii::t("login", "<h4>Success</h4>Account is enabled. Please change your current password."));
                $passReset = new UserPassreset();
                $link2 = $passReset->generarLinkCambioClave($usuario->usu_id);
                return $this->redirect($link2);
            } else {
                $model = new LoginForm();
                Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                $link1 = Utilities::getLoginUrl();
                return $this->redirect(Url::base(true) . $link1);
            }
        }
    }

    public function actionUpdatepass() {
        if (!\Yii::$app->user->isGuest) {
            $link1 = Utilities::getLoginUrl();
            return $this->redirect(Url::base(true) . $link1);
        }
        $data = Yii::$app->request->get();
        if (isset($data["wg"])) {
            $userpass = new UserPassreset();
            $status = $userpass->verificarLinkCambioClave(Url::base(true) . "/site/updatepass?wg=" . $data["wg"]);
            if ($status) {
                $model = new ChangepassForm();
                if ($model->load(Yii::$app->request->post()) && $model->resetearClave(Url::base(true) . "/site/updatepass?wg=" . $data["wg"])) {
                    // se limpia los campos
                    $model->unsetAttributes();
                    return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/changepass.php', [
                                'model' => $model,
                    ]);
                } else {
                    if ($model->getErrorSession())
                        Yii::$app->session->setFlash('updatepassFormSubmitted');
                    return $this->renderFile('@themes/' . \Yii::$app->getView()->theme->themeName . '/layouts/changepass.php', [
                                'model' => $model,
                    ]);
                }
            }else {
                Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Account is disabled. Please confirm the account with link activation in your email account or reset your password."));
                return $this->redirect('login');
            }
        }
    }

    /**
     * Get image from route
     *
     * @author Eduardo Cueva
     * @access protected
     * @param string $route     Ruta de Imagen
     */
    public function actionGetimage($route) {
        $grupo = new Grupo();
        if (Yii::$app->session->get('PB_isuser')) {
            $data = $grupo->getMainGrupo(Yii::$app->session->get('PB_username'));
            $route = str_replace("../", "", $route);
            if (preg_match("/^\/uploads\//", $route)) {
                $url_image = Yii::$app->basePath . $route;
                $arrIm = explode(".", $url_image);
                $typeImage = $arrIm[count($arrIm) - 1];
                if (file_exists($url_image)) {
                    if (strtolower($typeImage) == "png") {
                        Header("Content-type: image/png");
                        $im = imagecreatefromPng($url_image);
                        ImagePng($im); // Mostramos la imagen
                        ImageDestroy($im); // Liberamos la memoria que ocupaba la imagen
                    } elseif (strtolower($typeImage) == "jpg" || strtolower($typeImage) == "jpeg") {
                        Header("Content-type: image/jpeg");
                        $im = imagecreatefromJpeg($url_image);
                        ImageJpeg($im); // Mostramos la imagen
                        ImageDestroy($im); // Liberamos la memoria que ocupaba la imagen
                    } elseif (strtolower($typeImage) == "pdf") {
                        Header("Content-type: application/pdf");
                        return file_get_contents($url_image);
                    }
                    exit();
                }
            }
        }
        /* Crear una imagen en blanco */
        Header("Content-type: image/png");
        $im = imagecreatetruecolor(90, 90);
        $fondo = imagecolorallocate($im, 255, 255, 255);
        $ct = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 150, 30, $fondo);
// Imprimir un mensaje de error
        imagestring($im, 1, 5, 5, Yii::t('jslang', 'Bad Request') . ": " . $route, $ct);
        ImagePng($im); // Mostramos la imagen
        ImageDestroy($im); // Liberamos la memoria que ocupaba la imagen
        exit();
    }

    public function actionGetcredencial($id){
        $security = new Security();
        $dataIdCipr = $security->decryptByPassword(Utilities::base64_url_decode($id), Yii::$app->params['keywordEncription']);
        $data = json_decode($dataIdCipr, true);
        $idper = $data["per_id"];
        $idemp = $data["emp_id"];
        $modelPersona = Persona::findOne($idper);
        $modelEstudiante = Estudiante::findOne(['per_id' => $idper, 'est_estado_logico' => '1', 'est_estado' => '1']);
        $modelDocente = Profesor::findOne(['per_id' => $idper, 'pro_estado_logico' => '1', 'pro_estado' => '1']);
        $isEstudiante = false;
        $isDocente = false;
        $images_dir = 'rounded_corners';
        $corner_radius = 10; // El radio de la esquina redondeada se establece en 20px por defecto
        $foto_archivo = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "ficha/" . $idper . "/doc_foto_per_" . $idper . ".jpeg";
        $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-admin-front.jpeg";
        $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-admin-back.jpeg";
        $marginPhoto = 14; // Para credenciales Administrativas
        $image_rounded = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.b.png";
        $image_rounded2 = NULL;
        if($modelEstudiante){
            $marginPhoto = 14; // Para credenciales Estudiantes
            $image_rounded = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.w.png";
            $image_rounded2 = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/$images_dir/rounded_corner_".$corner_radius."px.lb.png";
            $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-estudiante-front.jpeg";
            $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-estudiante-back.jpeg";
            $isEstudiante = true;
        }
        if($modelDocente && !$isEstudiante){
            $bg_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-docente-front.jpeg";
            $bb_credencial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "Credencial/credencial-docente-back.jpeg";
            $isDocente = true;
        }
        
        if(is_file($foto_archivo)){
            // mostrar los archivos
            Header("Content-type: image/png");
            $size = [$this->widthImg,$this->heightImg];
            $im1 = imagecreatefromjpeg($bg_credencial); //image 325 x 523 
            $im2 = imagecreatefromjpeg($foto_archivo); //image 147 x 209 
            $image = ImageCreateTrueColor($size[0], $size[1]);
            imagecopyresampled($image,$im2,0,0,0,0,$size[0], $size[1], $size[0], $size[1]);
            
            $angle = 0; // The default angle is set to 0º
            $topleft = true; // La esquina superior izquierda se muestra por defecto
            $bottomleft = true; // La esquina inferior izquierda se muestra por defecto
            $bottomright = true; // La esquina inferior derecha se muestra por defecto
            $topright = true; // La esquina superior derecha se muestra por defecto
            $white = ImageColorAllocate($image,255,255,255);
            $black = ImageColorAllocate($image,0,0,0);
            $corner_source = imagecreatefrompng($image_rounded);
            $corner_width = imagesx($corner_source);  
            $corner_height = imagesy($corner_source);  
            $corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
            ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);
            $corner_width = imagesx($corner_resized);  
            $corner_height = imagesy($corner_resized);

            // Esquina inferior derecha
            if ($bottomright == true) {
                $dest_x = $size[0] - $corner_width;  
                $dest_y = $size[1] - $corner_height;  
                $rotated = imagerotate($corner_resized, 180, 0);
                imagecolortransparent($rotated, $black); 
                imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
            }

            // Esquina inferior izquierda
            if ($bottomleft == true) {
                $dest_x = 0;  
                $dest_y = $size[1] - $corner_height; 
                $rotated = imagerotate($corner_resized, 90, 0);
                imagecolortransparent($rotated, $black); 
                imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
            }

            if(isset($image_rounded2)){
                $corner_source = imagecreatefrompng($image_rounded2);
                ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);
                $corner_width = imagesx($corner_resized);  
                $corner_height = imagesy($corner_resized);
            }

            // Esquina superior derecha
            if ($topright == true) {
                $dest_x = $size[0] - $corner_width;  
                $dest_y = 0;  
                $rotated = imagerotate($corner_resized, 270, 0);
                imagecolortransparent($rotated, $black); 
                imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);  
            }

            // Esquina superior izquierda
            if ($topleft == true) {
                $dest_x = 0;  
                $dest_y = 0;  
                imagecolortransparent($corner_resized, $black); 
                imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
            } 

            // Rotar la imagen
            $image_rn = imagerotate($image, $angle, $white);

            // Texto
            $ttf_lightS = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Light.otf";
            $ttf_light = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Book.otf";
            $ttf_bold = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Medium.ttf";
            $ttf_boldH = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Gotham-Bold.ttf";
            $ttf_arial = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Arial.ttf";
            $ttf_arialB = Yii::$app->basePath . Yii::$app->params["documentFolder"] . "fonts/Arial-Bold.ttf";
            $colorB = imagecolorallocate($im1, 255, 255, 255);//#FFFFFF
            if($isEstudiante){
                $colorB = imagecolorallocate($im1, 0, 84, 139);//#00548b
            }
            
            $colorW = imagecolorallocate($im1, 255, 255, 255);//#FFFFFF
            $font_size = 11;
            $angulo = 0;
            $posX = 12;
            $posY = 385;

            // Get image dimensions
            $width = imagesx($im1);
            $height = imagesy($im1);
            // Get center coordinates of image
            $centerX = $width / 2;
            $centerY = $height / 2;

            $nombre = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido . " " . $modelPersona->per_seg_apellido; // limite 30 caracteres
            if(strlen($nombre) > 30){
                $nombre = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido; 
            }
            //$nombre = str_replace(array("Á","É", "Í", "Ó", "Ú"), array("A", "E", "I", "O", "U"), $nombre);
            $carrera = ""; // limite 30 caracteres
            $modalidad = ""; // limite 30 caracteres
            $cargo = ""; // limite 30 caracteres
            $matricula = "";
            $ttf_font = $ttf_boldH;
            $widthDifference = 7;
            if($isEstudiante){
                $dataCarrera = $modelEstudiante->getInfoCarreraEstudiante($modelEstudiante->est_id, $idemp);
                $ttf_font = $ttf_light;
                $widthDifference = 0;
                $carrera = (isset($dataCarrera['ResumenCarrera']) && $dataCarrera['ResumenCarrera'] != "")?$dataCarrera['ResumenCarrera']:$dataCarrera['Carrera']; // limite 30 caracteres
                if(strlen($carrera) > 30){
                    $carrera = substr($carrera, 0, 31) . ".";
                }
                //$modalidad = $dataCarrera['Modalidad']; // limite 30 caracteres
                $matricula = $modelEstudiante->est_matricula;
            }

            // Get size of text
            list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_font, strtoupper($nombre));
            // Determine offset of text
            $left_offset = ($right - $left) / 2;
            $top_offset = ($bottom - $top) / 2;
            // Generate coordinates
            $x = $centerX - $left_offset;
            $y = $centerY - $top_offset;
            // Add text to image
            //Imagen, tamaño, ángulo, x, y, color, fuente, texto
            imagefttext($im1, $font_size, $angulo, $x, $posY + $widthDifference, $colorB, $ttf_font, strtoupper($nombre));
            
            if(!$isEstudiante && !$isDocente){
                $cargo = ""; ///********************************************************* */ LLENAR CARGO
                // Get size of text
                list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_font, strtoupper($cargo));
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY - $top_offset;
                // Add text to image
                imagefttext($im1, $font_size, $angle, $x, 397, $colorB, $ttf_font, strtoupper($cargo));
            }
            
            if($isEstudiante){
                // Get size of text
                list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_light, $carrera);
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY - $top_offset;
                // Add text to image
                imagefttext($im1, $font_size, $angulo, $x, 410, $colorB, $ttf_light, $carrera);
            
            
                // Get size of text 
                list($left, $bottom, $right, , , $top) = imageftbbox($font_size, $angulo, $ttf_light, $modalidad);
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY - $top_offset;
                // Add text to image
                imagefttext($im1, $font_size, $angle, $x, 430, $colorB, $ttf_light, $modalidad);

                // Periodo
                $periodo = date("Y") . " - " . ( 1 + date("Y"));
                // Get size of text
                list($left, $bottom, $right, , , $top) = imageftbbox(19, $angulo, $ttf_light, $periodo);
                // Determine offset of text
                $left_offset = ($right - $left) / 2;
                $top_offset = ($bottom - $top) / 2;
                // Generate coordinates
                $x = $centerX - $left_offset;
                $y = $centerY - $top_offset;
                //Imagen, tamaño, ángulo, x, y, color, fuente, texto
                imagefttext($im1, 19, 0, 85, 498, $colorW, $ttf_bold, $periodo);
            }

            // Crear la imagen final
            //imagejpeg($image);
            imagecopy($im1, $image_rn, (imagesx($im1)/2)-(imagesx($image_rn)/2), (imagesy($im1)/2)-(imagesy($image_rn)/2)-$marginPhoto, 0, 0, imagesx($image_rn), imagesy($image_rn));
            
            imagepng($im1);
            imagedestroy($im1);
            imagedestroy($image_rn);
            exit();
        }
    }

}
