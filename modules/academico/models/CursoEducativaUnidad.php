<?php

namespace app\modules\academico\models;
use app\models\Utilities;
use Yii;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\Asignatura;
use yii\base\Exception;
use yii\helpers\VarDumper;
/**
 * This is the model class for table "curso_educativa_unidad".
 *
 * @property int $ceuni_id
 * @property int $cedu_id
 * @property int $ceuni_codigo_unidad
 * @property string $ceuni_descripcion_unidad
 * @property int $ceuni_usuario_ingreso
 * @property int $ceuni_usuario_modifica
 * @property string $ceuni_estado
 * @property string $ceuni_fecha_creacion
 * @property string $ceuni_fecha_modificacion
 * @property string $ceuni_estado_logico
 *
 * @property CursoEducativa $cedu
 */
class CursoEducativaUnidad extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'curso_educativa_unidad';
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
            [['cedu_id', 'ceuni_codigo_unidad', 'ceuni_descripcion_unidad', 'ceuni_usuario_ingreso', 'ceuni_estado', 'ceuni_estado_logico'], 'required'],
            [['cedu_id', 'ceuni_codigo_unidad', 'ceuni_usuario_ingreso', 'ceuni_usuario_modifica'], 'integer'],
            [['ceuni_fecha_creacion', 'ceuni_fecha_modificacion'], 'safe'],
            [['ceuni_descripcion_unidad'], 'string', 'max' => 500],
            [['ceuni_estado', 'ceuni_estado_logico'], 'string', 'max' => 1],
            [['cedu_id'], 'exist', 'skipOnError' => true, 'targetClass' => CursoEducativa::className(), 'targetAttribute' => ['cedu_id' => 'cedu_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ceuni_id' => 'Ceuni ID',
            'cedu_id' => 'Cedu ID',
            'ceuni_codigo_unidad' => 'Ceuni Codigo Unidad',
            'ceuni_descripcion_unidad' => 'Ceuni Descripcion Unidad',
            'ceuni_usuario_ingreso' => 'Ceuni Usuario Ingreso',
            'ceuni_usuario_modifica' => 'Ceuni Usuario Modifica',
            'ceuni_estado' => 'Ceuni Estado',
            'ceuni_fecha_creacion' => 'Ceuni Fecha Creacion',
            'ceuni_fecha_modificacion' => 'Ceuni Fecha Modificacion',
            'ceuni_estado_logico' => 'Ceuni Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCedu()
    {
        return $this->hasOne(CursoEducativa::className(), ['cedu_id' => 'cedu_id']);
    }

    /**
     * Function Obtiene información de unidades en educatica.
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarUnidadEducativa($arrFiltro = array(), $reporte, $ids) {
        $con = \Yii::$app->db_academico;        
        $estado = 1;
        if ($ids == 1) {
            $campos = "
            cure.cedu_id,  ";
        }    
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(cure.ceuni_descripcion_unidad like :search) AND ";
   
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "cur.paca_id = :paca_id AND ";
            }

            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $str_search .= "cure.cedu_id = :cedu_id AND ";
            }
                    
        }
        $sql = "SELECT  $campos
                        cur.cedu_asi_nombre,                         
                        cure.ceuni_codigo_unidad,
                        cure.ceuni_descripcion_unidad
                FROM " . $con->dbname . ".curso_educativa_unidad cure 
                INNER JOIN " . $con->dbname . ".curso_educativa cur ON cur.cedu_id = cure.cedu_id
                WHERE $str_search  cure.ceuni_estado = :estado
                AND cure.ceuni_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['curso'] != "" && $arrFiltro['curso'] > 0) {
                $curso = $arrFiltro["curso"];
                $comando->bindParam(":cedu_id", $curso, \PDO::PARAM_INT);
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
                'attributes' => [],
            ],
        ]);
        if ($reporte == 1) {
            return $dataProvider;
        } else {
            return $resultData;
        }
    }
}
