<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\base\Exception;
use yii\helpers\VarDumper;

// use app\modules\academico\models\Estudiante;

/**
 * This is the model class for table "curso_educativa".
 *
 * @property int $cedu_id
 * @property int $paca_id
 * @property int $asi_id
 * @property int $cedu_asi_id
 * @property string $cedu_asi_nombre
 * @property int $cedu_usuario_ingreso
 * @property int $cedu_usuario_modifica
 * @property string $cedu_estado
 * @property string $cedu_fecha_creacion
 * @property string $cedu_fecha_modificacion
 * @property string $cedu_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property Asignatura $asi
 * @property CursoEducativaEstudiante[] $cursoEducativaEstudiantes
 */
class CursoEducativa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa';
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
            [['paca_id', 'asi_id', 'cedu_asi_id', 'cedu_asi_nombre', 'cedu_usuario_ingreso', 'cedu_estado', 'cedu_estado_logico'], 'required'],
            [['paca_id', 'asi_id', 'cedu_asi_id', 'cedu_usuario_ingreso', 'cedu_usuario_modifica'], 'integer'],
            [['cedu_fecha_creacion', 'cedu_fecha_modificacion'], 'safe'],
            [['cedu_asi_nombre', 'cedu_estado', 'cedu_estado_logico'], 'string', 'max' => 1],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cedu_id' => 'Cedu ID',
            'paca_id' => 'Paca ID',
            'asi_id' => 'Asi ID',
            'cedu_asi_id' => 'Cedu Asi ID',
            'cedu_asi_nombre' => 'Cedu Asi Nombre',
            'cedu_usuario_ingreso' => 'Cedu Usuario Ingreso',
            'cedu_usuario_modifica' => 'Cedu Usuario Modifica',
            'cedu_estado' => 'Cedu Estado',
            'cedu_fecha_creacion' => 'Cedu Fecha Creacion',
            'cedu_fecha_modificacion' => 'Cedu Fecha Modificacion',
            'cedu_estado_logico' => 'Cedu Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCursoEducativaEstudiantes()
    {
        return $this->hasMany(CursoEducativaEstudiante::className(), ['cedu_id' => 'cedu_id']);
    }

    public function CargarArchivoeducativa($fname, $paca_id){
        \app\models\Utilities::putMessageLogFile('fname ' . $fname);
        \app\models\Utilities::putMessageLogFile('CargarArchivoeducativa');
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "educativa/". $fname;
        $fila = 0;
        $chk_ext = explode(".", $file);
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); //se obtiene la transaccion actual
        $mod_educativa = new CursoEducativa();
        if($trans !== null){
            $trans = null;
        } else {
            $trans = $con->beginTransaction();
        }
        if(strtolower(end($chk_ext)) == "xls" || strtolower(end($chk_ext)) == "xlsx"){
            try{
                $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
                $dataArr = $array();
                $validation = false;
                $row_global = 0;

                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                    $worksheetTitle = $worksheet->getTitle();
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestDataColumn();
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromStatus();
                    for($row = 2; $row <= $highestRow; ++$col){
                        $row_global = $row_global + 1;
                        for($col = 1; $col <= $highestColumnIndex; ++$col){
                            $cell = $worksheet->getCellByColumnAndRow($col, $row);
                            $dataArr[$row_global][$col] = $cell->getValue();
                        }
                    }
                }
                $fila = 0;
                foreach ($dataArr as $val) {
                    if(!is_null($val[1]) || $val[1]){
                        \app\models\Utilities::putMessageLogFile('for archivo');
                        //$val[1] = strval($val[1]);
                        //$asi_id = $asi_id->consultarAsindxalias($val[3]); // envio el alias me devuelve el asi_id
                        $fila++;
                        //if(!empty($asi_id['asi_id'])){
                            //$existe = $mod_educativa->consultarasignatura(($val[1], $val[2],$val[3]); //
                            //if($existe['existe_asignatura'] == 0){
                                $asi_id['asi_id'] = 1; //Borrar luego de hacer la consulta
                                $save_documento = $this->saveDocumentoDB($val, $paca_id, $asi_id['asi_id']);
                                \app\models\Utilities::putMessageLogFile('save_documento');
                                if(!$save_documento){
                                    $arroout["status"] = FALSE;
                                    $arroout["error"] = null;
                                    $arroout["message"] = "Error al guardar el registro de la fila => N°$fila Asignatura => $val[1]";
                                    $arroout["data"] = null;
                                    $arroout["validate"] = $val;
                                    \app\models\Utilities::putMessageLogFile('error fila' . $fila);
                                    return $arroout;
                                }
                            /*} else {
                                $ingresadoant .= $val[1] . ", ";
                            }*/
                        /*}else {
                            $noasignatura .= $val[1] . ", ";
                        }*/
                    }
                }
                if(!$trans !== null)
                    $trans->commit();
                $arroout["status"] = TRUE;
                $arroout["error"] = null;
                $arroout["message"] = null;
                $arroout["data"] = null;
                $arroout["validate"] = $fila;
                $arroout["repetido"] = $substr($ingresadoant, 0, -2);
                $arroout["noasignatura"] = $substr($noasignatura, 0, -2);
                //\app\models\Utilities::putMessageLogFile('error fila' . $fila);
                return $arroout;
            }catch (\Exception $ex){
                if(!$trans !== null)
                $trans->rollback();
            $arroout["status"] = FALSE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["data"] = null;
            return $arroout;
            }
        }    
    }

    public function saveDocumentoDB($val, $paca_id, $asi_id){
        $usu_id = Yii::$app->session->get("PB_iduser"); ;
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $mod_educativacurso = new CursoEducativa();
        $mod_educativacurso->paca_id = $paca_id;
        $mod_educativacurso->asi_id = $asi_id;
        $mod_educativacurso->cedu_asi_id = $val[1];
        $mod_educativacurso->cedu_asi_nombre = $val[2];
        $mod_educativacurso->cedu_usuario_ingreso = $usu_id;
        $mod_educativacurso->cedu_estado = "1";
        $mod_educativacurso->cedu_fecha_creacion = "$fecha_transaccion";
        $mod_educativacurso->cedu_estado_logico = "1";

        \app\models\Utilities::putMessageLogFile('paca_id ' .$paca_id);
        \app\models\Utilities::putMessageLogFile('asi_id '. $paca_id);
        \app\models\Utilities::putMessageLogFile('1: ' .$val[1]);
        \app\models\Utilities::putMessageLogFile('2: ' .$val[2]);
        \app\models\Utilities::putMessageLogFile('3: ' .$val[3]);
        \app\models\Utilities::putMessageLogFile('fecha: ' .$fecha_transaccion);
        \app\models\Utilities::putMessageLogFile('usu_id: ' .$usu_id);        

        return $mod_educativacurso->save();
    }
}
