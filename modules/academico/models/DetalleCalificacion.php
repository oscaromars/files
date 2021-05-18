<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

use app\modules\academico\models\CabeceraAsistencia;

/**
 * This is the model class for table "detalle_calificacion".
 *
 * @property int $dcal_id
 * @property int $ccal_id
 * @property int $cuni_id
 * @property double $dcal_calificacion
 * @property int $dcal_usuario_creacion
 * @property int $dcal_usuario_modificacion
 * @property string $dcal_estado
 * @property string $dcal_fecha_creacion
 * @property string $dcal_fecha_modificacion
 * @property string $dcal_estado_logico
 *
 * @property CabeceraCalificacion $ccal
 * @property ComponenteUnidad $cuni
 */
class DetalleCalificacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_calificacion';
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
            [['ccal_id', 'cuni_id', 'dcal_usuario_creacion', 'dcal_estado', 'dcal_estado_logico'], 'required'],
            [['ccal_id', 'cuni_id', 'dcal_usuario_creacion', 'dcal_usuario_modificacion'], 'integer'],
            [['dcal_calificacion'], 'number'],
            [['dcal_fecha_creacion', 'dcal_fecha_modificacion'], 'safe'],
            [['dcal_estado', 'dcal_estado_logico'], 'string', 'max' => 1],
            [['ccal_id'], 'exist', 'skipOnError' => true, 'targetClass' => CabeceraCalificacion::className(), 'targetAttribute' => ['ccal_id' => 'ccal_id']],
            [['cuni_id'], 'exist', 'skipOnError' => true, 'targetClass' => ComponenteUnidad::className(), 'targetAttribute' => ['cuni_id' => 'cuni_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dcal_id' => 'Dcal ID',
            'ccal_id' => 'Ccal ID',
            'cuni_id' => 'Cuni ID',
            'dcal_calificacion' => 'Dcal Calificacion',
            'dcal_usuario_creacion' => 'Dcal Usuario Creacion',
            'dcal_usuario_modificacion' => 'Dcal Usuario Modificacion',
            'dcal_estado' => 'Dcal Estado',
            'dcal_fecha_creacion' => 'Dcal Fecha Creacion',
            'dcal_fecha_modificacion' => 'Dcal Fecha Modificacion',
            'dcal_estado_logico' => 'Dcal Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCcal()
    {
        return $this->hasOne(CabeceraCalificacion::className(), ['ccal_id' => 'ccal_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCuni()
    {
        return $this->hasOne(ComponenteUnidad::className(), ['cuni_id' => 'cuni_id']);
    }

    /**
     * Retorna el detalle a partir del ID de la cabecera
     * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return 
     */
    public function consultarDetallesDesdeCabecera($ccal_id, $onlyData = false){
        $con = Yii::$app->db_academico;
        $transaccion = $con->beginTransaction();

        $sql = "SELECT * FROM db_academico_mbtu.detalle_calificacion as dcal
                WHERE dcal.ccal_id = $ccal_id
                AND dcal.dcal_estado = 1 AND dcal.dcal_estado_logico = 1";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();
  
        if($onlyData){
            return $resultData;
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'dcal_id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'dcal_id',
                    'ccal_id',
                    'cuni_id',
                    'dcal_calificacion'
                ],
            ],
        ]);

        return $dataProvider;
    }

    /**
     * Retorna el detalle a partir del ID del profesor, la materia, el estudiante y el período
     * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>
     * @param   
     * @return 
     */
    public function consultarDetallesDesdeIDs($est_id, $asi_id, $pro_id, $paca_id, $asistencia_parcial_1, $asistencia_parcial_2, $onlyData = false){
        $con = Yii::$app->db_academico;

        $sql = "SELECT ccal.ccal_id, ccal.paca_id, ccal.est_id, ccal.pro_id, ccal.asi_id, ccal.ecun_id, ccal.ccal_calificacion, dcal.cuni_id, dcal.dcal_calificacion
                FROM db_academico_mbtu.cabecera_calificacion as ccal
                INNER JOIN db_academico_mbtu.detalle_calificacion as dcal ON ccal.ccal_id = dcal.ccal_id
                WHERE ccal.est_id = $est_id AND ccal.asi_id = $asi_id AND ccal.pro_id = $pro_id AND ccal.paca_id = $paca_id
                AND dcal.dcal_estado = 1 AND dcal.dcal_estado_logico = 1
                AND ccal.ccal_estado = 1 AND ccal.ccal_estado_logico = 1";

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();

        // Llamar a la función de Cabecera Asistencia y obtener el porcentaje de asistencias
        $arr_asistencias = (new CabeceraAsistencia())->consultarCabeceraPorIDs($est_id, $asi_id, $pro_id, $paca_id);

        // Crear arreglo para mostrar
        $arrFinal = [
            ["parcial" => "Parcial 1"],
            ["parcial" => "Parcial 2"],
            // ["supletorio" => "Supletorio"],
            // ["mejoramiento" => "Mejoramiento"]
        ];

        for ($i = 0; $i < count($resultData); $i++) {
            $value = $resultData[$i];
            // $asis_value = $arr_asistencias[$i];
            $ecun_id = $value['ecun_id'];

            if($ecun_id == 1 || $ecun_id == 4 || $ecun_id == 7){ // 1er Parcial
                $arrFinal[0]["promedio"] = $value['ccal_calificacion'];
                if(isset($asistencia_parcial_1)){ $arrFinal[0]["asistencia"] = $asistencia_parcial_1 . "%"; }
                $arrFinal[0][] = $value['dcal_calificacion'];
            }
            elseif ($ecun_id == 2 || $ecun_id == 5) { // 2do Parcial
                $arrFinal[1]["promedio"] = $value['ccal_calificacion'];
                if(isset($asistencia_parcial_2)){ $arrFinal[1]["asistencia"] = $asistencia_parcial_2 . "%"; }
                $arrFinal[1][] = $value['dcal_calificacion'];
            }
        }

        // \app\models\Utilities::putMessageLogFile($arrFinal);
  
        if($onlyData){
            return $arrFinal;
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'parcial',
            'allModels' => $arrFinal,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'parcial',
                    '0',
                    '1',
                    '2',
                    '3',
                    '4',
                    'promedio',
                    'asistencia'
                ],
            ],
        ]);

        return $dataProvider;
    }
}
