<?php
use yii\helpers\Html;
use app\assets\FontAwesomeAsset;
use app\assets\AppAsset;
use app\models\Menu;
use app\themes\adminLTE\resources\AdminLTEAsset;
use odaialali\yii2toastr\ToastrAsset;
use app\vendor\penblu\blockui\BlockuiAsset;
use app\vendor\penblu\magnificpopup\MagnificPopupAsset;

Menu::getScripts($this, Yii::$app->controller->id, Yii::$app->controller->module->id);
$assetsAdminLTE = AdminLTEAsset::register($this);
$assetsApp  = AppAsset::register($this);
$assetsFont = FontAwesomeAsset::register($this);
$assetsToastr   = ToastrAsset::register($this);
$assetsBlockui  = BlockuiAsset::register($this);
$assetsPopup    = MagnificPopupAsset::register($this);
//$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@bower') . '/admin-lte';
$directoryAsset = $assetsAdminLTE->baseUrl;
$this->title = $this->params["siteName"];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?= $directoryAsset; ?>/img/logos/favicon.ico" type="image/x-icon" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <?php Menu::generateJSLang("messages", Yii::$app->language); ?>
    </head>
    <body >

        <div class="login-box">
        <div class="">
            <h3 class="bg-success" style="text-align: center;color:#4682B4"><b>TÉRMINOS Y CONDICIONES DE ADQUISICIÓN DE SERVICIO EDUCATIVO</b></h3>
            <p style="text-align: justify;">El presente acuerdo de términos y condiciones de adquisición de servicios educativos se suscribe con la Universidad Tecnológica Empresarial de
               Guayaquil. <b>Por favor, lea el siguiente acuerdo con detenimiento</b> pues, constituye un marco vinculante entre Usted y UTEG.</p>
            <p style="color:#4682B4";><b>DOCUMENTACIÓN</b></p>
            <p>Conozco y acepto los siguientes términos:</p>
            <p style="text-align: justify;"><b>Sobre la entrega de documentos:</b> La documentación requerida para iniciar el semestre será entregada al área de Admisiones, como
                fecha máxima al quinto día hábil de haber realizado la inscripción, bajo las condiciones establecidas por el área responsable.</p>
            <p style="text-align: justify;"><b>Sobre la devolución de documentos:</b> en caso de que la documentación entregada sea copias, estas no me serán devueltas bajo ningún
               concepto.</p>
            <p style="text-align: justify;"><b>Sobre la devolución de documentos:</b> en caso de que la documentación entregada sean documentos originales, podré solicitar la
               devolución de estos en un plazo máximo de 6 meses, a partir de la fecha de entrega de los documentos.</p>
            </div><br>
            <p style="color:#4682B4";><b>PAGOS, AUTORIZACIÓN, REEMBOLSOS Y TERMINACIÓN</b></p>
            <p>Acepto los siguientes términos:</p>
            <p style="text-align: justify;">Ningún importe es reembolsable, si paga en cuotas mensuales, todos los importes se adeudan por adelantado, no son reembolsables
               y se cobrarán automáticamente a su instrumento de pago, empezando con el primer pago adeudado cuando acepte estos Términos
               de Adquisición de Servicio. Si su Instrumento de Pago es “Alternativo”, su pago debe realizarse a través de los sistemas de la
               Universidad y sus centros de cobro autorizados.</p>
            <p style="text-align: justify;">Ud. garantiza que legalmente es el titular del referido instrumento de pago y que está autorizado para realizar el pago del Precio de
               Adquisición de Servicio y también para crear este compromiso de pago con la Universidad. Una vez que el alumno ha registrado sus
               materias del semestre, cualquier materia se tomará como adicional y se dividirá entre el número de cuotas que faltan para terminar el
               bloque o semestre.</p>
            <p style="text-align: justify;">Los datos arriba declarados tienen carácter de DECLARACIÓN JURADA, aceptando en su totalidad las condiciones establecidas.</p>
            <p style="text-align: justify;">La Universidad se reserva el derecho de apertura, aplazamiento o suspensión de los cursos en caso de no contar con el número
               mínimo de inscriptos. Se deberá realizar el pago de matrícula antes del inicio de este.</p>
            <p style="text-align: justify;">A partir de la recepción de la presente inscripción, el alumno se compromete a realizar el pago de sus cuotas, a excepción que con
               anticipación solicite la baja con carácter voluntario, quedando así obligado al pago de las cuotas vencidas a la fecha de dicha solicitud.</p>
            </div>
        </div><!-- /.login-box -->

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

