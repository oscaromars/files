<?php

namespace app\modules\academico\controllers;

use Yii;
use yii\base\Exception;
use app\modules\academico\models\Diploma;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\ExportFile;
use app\modules\academico\Module as academico;

academico::registerTranslations();

class DiplomaController extends \app\components\CController {

    public function actionIndex(){
        $model = new Diploma();
        $arr_carreras = array();
        $arr_modalidades = array();
        $arr_programas = array();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"]) && $data["PBgetFilter"] == TRUE) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllDiplomasGrid($data["search"], $data["carrera"], $data["programa"], $data["modalidad"], $data["fechainicio"], $data["fechafin"]),
            ]);
        }
        /*$arr_carreras = ["0" => academico::t("diploma", "-- Select Career --")];
        $arr_modalidades = ["0" => academico::t("diploma", "-- Select Modality --")];
        $arr_programas = ["0" => academico::t("diploma", "-- Select Program/Course --")];*/
        $arr_carreras = ["0" => Yii::t("formulario", "All")];
        $arr_modalidades = ["0" =>Yii::t("formulario", "All")];
        $arr_programas = ["0" => Yii::t("formulario", "All")];
        $carreras = Diploma::find()
                    ->select(['dip_carrera'])
                    ->where(['dip_estado_logico' => '1', 'dip_estado' => '1'])
                    ->groupBy(['dip_carrera'])
                    ->asArray()
                    ->all();
        $modalidades = Diploma::find()
                    ->select(['dip_modalidad'])
                    ->where(['dip_estado_logico' => '1', 'dip_estado' => '1'])
                    ->groupBy(['dip_modalidad'])
                    ->asArray()
                    ->all();
        $programas = Diploma::find()
                    ->select(['dip_programa'])
                    ->where(['dip_estado_logico' => '1', 'dip_estado' => '1'])
                    ->groupBy(['dip_programa'])
                    ->asArray()
                    ->all();
        $arr_carreras = array_merge($arr_carreras, array_column($carreras, 'dip_carrera'));
        $arr_modalidades = array_merge($arr_modalidades, array_column($modalidades, 'dip_modalidad'));
        $arr_programas = array_merge($arr_programas, array_column($programas, 'dip_programa'));

        return $this->render('index', [
            'model' => $model->getAllDiplomasGrid(NULL),
            'arr_carreras' => $arr_carreras,
            'arr_programas' => $arr_programas,
            'arr_modalidades' => $arr_modalidades,
        ]);
    }

    public function actionDiplomadownload(){
        try {
            $ids = $_GET['id'];
            setlocale(LC_TIME, 'es_CO.UTF-8');
            $model = Diploma::findOne($ids);
            /*$desde = date('j', strtotime($model->dip_fecha_inicio)) . " de " . academico::t('matriculacion',date('F', strtotime($model->dip_fecha_inicio))).' del '.date('Y', strtotime($model->dip_fecha_inicio));
            $hasta = date('j', strtotime($model->dip_fecha_fin)).' de '.academico::t('matriculacion',date('F', strtotime($model->dip_fecha_fin))).' del '.date('Y', strtotime($model->dip_fecha_fin));
            if(date('Y', strtotime($model->dip_fecha_inicio)) == date('Y', strtotime($model->dip_fecha_fin))){
                if(date('m', strtotime($model->dip_fecha_inicio)) == date('m', strtotime($model->dip_fecha_fin)))
                    $desde = date('j', strtotime($model->dip_fecha_inicio));
                else
                    $desde = date('j', strtotime($model->dip_fecha_inicio)) . " de " . academico::t('matriculacion',date('F', strtotime($model->dip_fecha_inicio)));
            }*/
            $hasta = strftime("%d de %B %G", strtotime($model->dip_fecha_inicio));
            $fin = strftime("%d de %B %G", strtotime($model->dip_fecha_fin));
            if ($hasta == $fin) {
                $fecha = '<span>el ' . $hasta . '. </span><br/>';
            } elseif($model->dip_id > 4582 && $model->dip_id < 4596) {
                $fecha = '<span> ' . $hasta . ' al ' . $fin .'. </span><br/>';
            }else {
                $fecha = '<span> desde el ' . $hasta . ' hasta el ' . $fin .'. </span><br/>';
            }
            if ($model->dip_id < 50) {
            $title = "El Departamento de Vinculación con la Sociedad<br/>conﬁere el presente certiﬁcado a:";
            $body  = 'Por haber participado en el curso: ';
            $body .= '<span>"'.$model->dip_programa.'",</span> realizado ';
            $body .= $fecha;//'<span>desde el '.$desde.' hasta el '.$hasta.'</span>';
            $body .= ' con una duración de <span>'.$model->dip_horas.' horas pedagógicas.</span>';
           } else{
            if ($model->dip_id > 49 && $model->dip_id < 3515) {
            $title = "OTORGA EL PRESENTE CERTIFICADO A:";
            $body  = '<H6>Por su participación en el seminario:</H6>';
            $body .= '<span><H6>'.$model->dip_programa.'</span></H6>';
            $body .= '<H6>En el marco del desarrollo de las actividades de Vinculación con la sociedad de UTEG-POSGRADO, dictado por las y los maestrantes de la Maestría en Educación Online. Con una duración de '. $model->dip_horas .' horas de capacitación.';
            $dates = '<H6>Dado '.$fecha. '</H6>';
            } elseif ($model->dip_id > 3638 && $model->dip_id < 3654) {
             $title = "OTORGA EL PRESENTE CERTIFICADO A:";
            $body  = '<H6>Por haber participado en el proceso de capacitación del Proyecto de Vinculación: </H6>';
            $body .= '<span><H3>'.$model->dip_programa.'</span></H3>';
            $body .= '<H4> Dado en el marco del desarrollo de las actividades de Vinculación con la Sociedad de los estudiantes de UTEG-GRADO.</h4>';
            $hasta = strftime("%d de %B de %G", strtotime($model->dip_fecha_inicio));
            $fin = strftime("%d de %B de %G", strtotime($model->dip_fecha_fin));
            $fecha = '<span>Del ' . $hasta . ' al ' . $fin .'. </span><br/>';
            $dates = '<H6>'.$fecha. '</H6>';

            } else{
                if ($model->dip_id > 4535 && $model->dip_id < 4596) { //NUEVO
                    $title = "La Universidad Tecnológica Empresarial de Guayaquil otorga el presente certificado a:";
                    $body  = '<H6>Por haber participado en el proceso de capacitación del proyecto de servicio comunitario</H6>';
                    $body .= '<span><H6>"'.$model->dip_programa.'"</span></H6>';
                    $body .= '<H6>Dado en el marco del desarrollo de las actividades de Vinculación con la Sociedad de UTEG, dictado por los estudiantes de la '. $model->dip_carrera . '. Con una duración de '. $model->dip_horas .' horas.';
                    $dates = '<H6>Dado '.$fecha. '</H6>';
                 }else{ //NUEVO
                    if ($model->dip_id > 4595 && $model->dip_id < 4651) {
                        $title = "La Universidad Tecnológica Empresarial de Guayaquil otorga el presente certificado a:";
                        $body  = '<H6>Por haber participado en el proceso de capacitación del proyecto de servicio comunitario:</H6>';
                        $body .= '<span><H6>'.$model->dip_programa.'</span></H6>';
                        $body .= '<H6>Dado en el marco del desarrollo de las actividades de Vinculación con la sociedad de UTEG, dictado por los estudiantes de la Maestría en Educación. Con una duración de '. $model->dip_horas .' horas.';
                        $hasta = strftime("%d de %B de %G", strtotime($model->dip_fecha_inicio));
                        $fin = strftime("%d de %B de %G", strtotime($model->dip_fecha_fin));
                        $fecha = '<span>' . $hasta . ' al ' . $fin .'. </span><br/>';
                        $dates = '<H6>'.$fecha. '</H6>';
                   } else{
                        $title = "El Departamento de Vinculación con la Sociedad confiere el presente certificado a:";
                        $body  = '<H6>Por haber asistido al seminario:</H6>';
                        $body .= '<span><H6>'.$model->dip_programa.'</span></H6>';
                        $body .= '<H6>Dado en el marco del desarrollo de las actividades de Vinculación con la sociedad de UTEG-POSGRADO, dictado por las y los maestrantes de la '. $model->dip_carrera . '. Con una duración de '. $model->dip_horas .' horas técnico pedagógicas.';
                        $dates = '<H6>Dado '.$fecha. '</H6>';
                     }
                 }
            }

          }
            $rep = new ExportFile();
            //$this->layout = false;
            if ($model->dip_id < 50) {
            $this->layout = '@modules/academico/views/diploma/tpl_main';
            }else{
            $this->layout = '@modules/academico/views/diploma/tpl_vinculacion';
            }
            $rep->mgl = 0;
            $rep->mgr = 0;
            $rep->mgt = 0;
            $rep->mgb = 0;
            $rep->mgh = 0;
            $rep->mgf = 0;

            $rep->fontDir = __DIR__ . "/../views/diploma/fonts";
            $fontdata["gothambook"] = ['R' => 'GothamBook.ttf'];
            $fontdata["blacksword"] = ['R' => 'Blacksword.ttf'];
            $fontdata["gothambold"] = ['R' => 'GothamBold.ttf'];
            $rep->fontdata = $fontdata;

            $rep->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
            $rep->footer = FALSE;
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/diploma/tpl_diploma', [
                        'title' => $title,
                        'body' => $body,
                        'dates' => $dates,
                        'code' => $model->dip_codigo,
                        'name' => $model->dip_nombres . " " . $model->dip_apellidos,
                    ])
            );
            $rep->mpdf->Output('DIPLOMA_' . str_replace(" ", "_",$model->dip_nombres . " " . $model->dip_apellidos) . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
            exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}