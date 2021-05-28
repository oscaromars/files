<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;


/**
 * This is the model class for table "resumen_evaluacion_docente".
 *
 * @property int $redo_id
 * @property int $pro_id
 * @property int $saca_id
 * @property int $teva_id
 * @property double $redo_cant_horas
 * @property double $redo_puntaje_evaluacion
 * @property string $redo_estado
 * @property string $redo_fecha_creacion
 * @property string $redo_fecha_modificacion
 * @property string $redo_estado_logico
 *
 * @property SemestreAcademico $saca
 * @property Profesor $pro
 * @property TipoEvaluacion $teva
 */
class ResumenEvaluacionDocente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'resumen_evaluacion_docente';
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
            [['pro_id', 'saca_id', 'teva_id', 'redo_estado', 'redo_estado_logico'], 'required'],
            [['pro_id', 'saca_id', 'teva_id'], 'integer'],
            [['redo_cant_horas', 'redo_puntaje_evaluacion'], 'number'],
            [['redo_fecha_creacion', 'redo_fecha_modificacion'], 'safe'],
            [['redo_estado', 'redo_estado_logico'], 'string', 'max' => 1],
            [['saca_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemestreAcademico::className(), 'targetAttribute' => ['saca_id' => 'saca_id']],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['teva_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoEvaluacion::className(), 'targetAttribute' => ['teva_id' => 'teva_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'redo_id' => 'Redo ID',
            'pro_id' => 'Pro ID',
            'saca_id' => 'Saca ID',
            'teva_id' => 'Teva ID',
            'redo_cant_horas' => 'Redo Cant Horas',
            'redo_puntaje_evaluacion' => 'Redo Puntaje Evaluacion',
            'redo_estado' => 'Redo Estado',
            'redo_fecha_creacion' => 'Redo Fecha Creacion',
            'redo_fecha_modificacion' => 'Redo Fecha Modificacion',
            'redo_estado_logico' => 'Redo Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaca()
    {
        return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeva()
    {
        return $this->hasOne(TipoEvaluacion::className(), ['teva_id' => 'teva_id']);
    }
    
    /**
     * Function consulta los tipod de evaluacion a docentes. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarTipoEvaluacion() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                   teva_id as id,
                   teva_nombre as name
                FROM 
                   " . $con->dbname . ".tipo_evaluacion 
                WHERE 
                   teva_estado = :estado AND
                   teva_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    /**
     * Function consultarResumenEvaluacion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>    
     * @property  
     * @return  
     */
    public function consultarResumenEvaluacion($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_pri_nombre like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor OR ";
            $str_search .= "per.per_pri_apellido like :profesor OR ";
            $str_search .= "per.per_seg_nombre like :profesor )  AND ";       
            
            if ($arrFiltro['tipo_evaluacion'] != "" && $arrFiltro['tipo_evaluacion'] > 0) {
                $str_search .= " red.teva_id = :tipo_evaluacion AND ";
            }
            
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $str_search .= " red.saca_id = :semestre AND ";
            }
        }     
        $sql = "
               SELECT 
                        -- GROUP_CONCAT(distinct(red.pro_id)) as profesor_id, 
                        -- GROUP_CONCAT(distinct(red.saca_id)) as semestre, 
                        CONCAT(per.per_pri_nombre, ' ', per.per_pri_apellido) as profesor,
                        CONCAT(sea.saca_nombre, ' ', sea.saca_anio) as semestre_nombre,
                        GROUP_CONCAT(CASE
                            WHEN red.teva_id = 1 THEN 'Docencia'
                            WHEN red.teva_id = 2 THEN 'Investigación'
                            WHEN red.teva_id = 3 THEN 'Dirección y Gestión Académica'
                            END, ' | ', redo_cant_horas,' | ', redo_puntaje_evaluacion, ' ') as valores ,
                            rre.rreva_evaluacion_completa as evaluacion_completa,
                            rre.rreva_total_hora as total_hora,
                            rre.rreva_total_evaluacion as total_evaluacion 
                        FROM " . $con->dbname . ".resumen_evaluacion_docente red
                        INNER JOIN " . $con->dbname . ".resumen_resultado_evaluacion rre ON rre.pro_id = red.pro_id and  rre.saca_id = red.saca_id
                        INNER JOIN " . $con->dbname . ".semestre_academico sea ON sea.saca_id = red.saca_id
                        INNER JOIN " . $con->dbname . ".profesor profe ON profe.pro_id = red.pro_id
                        INNER JOIN " . $con1->dbname . ".persona per ON per.per_id = profe.per_id
                    WHERE $str_search
                        red.redo_estado = :estado AND
                        red.redo_estado_logico = :estado AND
                        rre.rreva_estado = :estado AND
                        rre.rreva_estado_logico = :estado AND
                        sea.saca_estado = :estado AND
                        sea.saca_estado_logico = :estado AND
                        profe.pro_estado = :estado AND
                        profe.pro_estado_logico = :estado AND
                        per.per_estado = :estado AND
                        per.per_estado_logico = :estado 
                        group by red.pro_id, red.saca_id
               ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["profesor"] . "%";
            $comando->bindParam(":profesor", $search_cond, \PDO::PARAM_STR);            
            
            if ($arrFiltro['tipo_evaluacion'] != "" && $arrFiltro['tipo_evaluacion'] > 0) {
                $tipo_evaluacion = $arrFiltro["tipo_evaluacion"];
                $comando->bindParam(":tipo_evaluacion", $tipo_evaluacion, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['semestre'] != "" && $arrFiltro['semestre'] > 0) {
                $semestre = $arrFiltro["semestre"];
                $comando->bindParam(":semestre", $semestre, \PDO::PARAM_INT);
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
     * Function consulta los semestres. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarSemestre() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT
                   saca.saca_id as id,
                   CONCAT(saca.saca_nombre,' ',saca.saca_anio) as name
                FROM 
                   " . $con->dbname . ".semestre_academico saca ";               
        $sql .= "  WHERE 
                   saca.saca_estado = :estado AND
                   saca.saca_estado_logico = :estado
                   ORDER BY name ASC";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
