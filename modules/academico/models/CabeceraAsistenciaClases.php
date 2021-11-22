<?php

namespace app\modules\academico\models; 

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use app\models\Utilities;
use \yii\db\Query;
use yii\base\Exception;
use app\modules\academico\models\PeriodoAcademico;
/**
 * This is the model class for table "db_academico.cabecera_asistencia".
 *
 * @property int $caac_id
 * @property int $pro_id
 * @property int $asi_id
 * @property int $est_id
 * @property int $paca_id
 * @property string $caac_cantidad_sesiones
 * @property double $caac_porcentaje_asistencia
 * @property string $caac_estado
 * @property string $caac_fecha_creacion
 * @property string $caac_usuario_modificacion
 * @property string $caac_fecha_modificacion
 * @property string $caac_estado_logico
 */
class CabeceraAsistenciaClases extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_academico.cabecera_asistencia_clases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paca_id', 'est_id', 'pro_id', 'asi_id', 'caac_estado', 'caac_estado_logico'], 'required'],
            [['paca_id', 'est_id', 'pro_id', 'asi_id','caac_estado', 'caac_estado_logico', 'caac_cantidad_sesiones'], 'integer'],
            [['caac_porcentaje_asistencia'], 'number'],
            [['caac_fecha_creacion', 'caac_fecha_modificacion'], 'safe'],
            [['caac_estado', 'caac_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'caac_id' => 'Caac ID',
            'pro_id' => 'Pro ID',
            'asi_id' => 'Asi ID',
            'est_id' => 'Est ID',
            'paca_id' => 'Paca ID',
            'caac_cantidad_sesiones'        => 'Caac cantidad sesiones',
            'caac_porcentaje_asistencia'    => 'Caac porcentaje asistencia',
            'caac_estado'                   => 'Caac estado',
            'caac_fecha_creacion'           => 'Caac fecha creacion',
            'caac_usuario_modificacion'     => 'Caac usuario modificacion',
            'caac_fecha_modificacion'       => 'Caac fecha modificacion',
            'caac_estado_logico'            => 'Caac estado logico',
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
    public function getEst()
    {
        return $this->hasOne(Estudiante::className(), ['est_id' => 'est_id']);
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
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * Insertar en la tabla cabecera_asistencia_clases
     * @author  Diego Betancourt <analistadesarrollo08@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function insertCabeceraAsistenciaClases($paca_id, $est_id, $pro_id, $asi_id, $caac_cantidad_sesiones,$caac_porcentaje_asistencia){
        $con = Yii::$app->db_academico;
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        try
        {
            $sql = "INSERT INTO " . $con->dbname . ".cabecera_asistencia_clases
                                (   pro_id, 
                                    asi_id, 
                                    est_id, 
                                    paca_id, 
                                    caac_cantidad_sesiones, 
                                    caac_porcentaje_asistencia, 
                                    caac_estado, 
                                    caac_fecha_creacion, 
                                    caac_usuario_modificacion, 
                                    caac_estado_logico) 
                                    VALUES(
                                            $pro_id, 
                                            $asi_id, 
                                            $est_id, 
                                            $paca_id, 
                                            $caac_cantidad_sesiones, 
                                            $caac_porcentaje_asistencia, 1, '$fecha_transaccion', $usu_id, 1)";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            $idtable = $con->getLastInsertID($con->dbname . '.cabecera_asistencia_clases');

            return $idtable;
        }
        catch (Exception $ex){
            \app\models\Utilities::putMessageLogFile('Error insertCabeceraAsistenciaClases '.$ex->getMessage());
            return 0;
        }
    }

    
    public function insertDetalleAsistenciaClases($caac_id, $asi_id, $paca_id, $mod_id, $pro_id,
                                                    $est_id, $daho_id, $hape_id, $rmtm_id, $hora_ini,
                                                    $min_atraso){
        $con = Yii::$app->db_academico;
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);

        try
        {
            $sql = "INSERT INTO " . $con->dbname . ".detalle_asistencia_clases 
            (   caac_id,
                asi_id,
                paca_id,
                mod_id,
                pro_id,
                est_id,
                daho_id,
                hape_id,
                rmtm_id,
                deac_hora_inicio_clase,
                deac_minutos_atraso,
                deac_estado,
                deac_fecha_creacion,
                deac_estado_logico)
                    VALUES($caac_id, $asi_id, $paca_id, $mod_id, $pro_id,
                    $est_id, $daho_id, $hape_id, $rmtm_id, '$hora_ini',
                    $min_atraso, 1, '$fecha_transaccion', 1)";

            $comando = $con->createCommand($sql);
            $result = $comando->execute();

            $idtable = $con->getLastInsertID($con->dbname . '.detalle_asistencia');

            return $idtable;
        
        }catch (Exception $ex){
            \app\models\Utilities::putMessageLogFile('Error insertDetalleAsistenciaClases '.$ex->getMessage());
            return 0;
        }
    }


    public function detalleSesiones($pro_id, $paca_id, $asi_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT x.cantidad,x.duracion,(x.cantidad/x.duracion) as porcentaje from(
                SELECT distinct
                    count(rmg.rmtm_id ) as cantidad,
                    sum(DATE_FORMAT(SUBTIME(hap.hape_hora_salida, hap.hape_hora_entrada),'%H:%i')) as duracion
                from db_academico.registro_marcacion_generada rmg
                inner join db_academico.horario_asignatura_periodo hap on rmg.hape_id = hap.hape_id
                where hap.pro_id = $pro_id and hap.paca_id = $paca_id and hap.asi_id = $asi_id group by hap.pro_id) as x;";                      
                    // -- sum(DATE_FORMAT(SUBTIME(hap.hape_hora_salida, hap.hape_hora_entrada),'%H:%i'))/ 100 as porcentaje

        $comando = $con->createCommand($sql);
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function existeCabeceraAsistenciaClases($est_id, $paca_id, $asi_id, $pro_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT count(caac_id) as cantidad from db_academico.cabecera_asistencia_clases caac
                where est_id = $est_id and paca_id = $paca_id and asi_id = $asi_id and pro_id = $pro_id;";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('existeCabeceraAsistenciaClases '.$comando->getRawSql());
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function consultarCabeceraAsistenciaClases($est_id, $paca_id, $asi_id, $pro_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT distinct caac_id from db_academico.cabecera_asistencia_clases caac
        where est_id = $est_id and paca_id = $paca_id and asi_id = $asi_id and pro_id = $pro_id limit 0,1; ";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('consultarCabeceraAsistenciaClases '.$comando->getRawSql());
        $resultData = $comando->queryOne();

        return $resultData;
    }
    
    public function existeDetalleAsistenciaClases($caac_id, $rmtm_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT count(deac_id) as cantidad from db_academico.detalle_asistencia_clases deac
                where caac_id = $caac_id and rmtm_id = $rmtm_id; ";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('existeDetalleAsistenciaClases '.$comando->getRawSql());
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function consultarDetalleAsistenciaClases($caac_id, $rmtm_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT distinct deac_id, deac_hora_fin_clase as fin, deac_minutos_retiro as retiro from db_academico.detalle_asistencia_clases deac
                where caac_id = $caac_id and rmtm_id = $rmtm_id limit 0,1; ";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('consultarCabeceraAsistenciaClases '.$comando->getRawSql());
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function getDetalleHorario( $rmtm_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT hap.hape_id, hap.hape_hora_entrada as hora_ini, hap.hape_hora_salida as hora_fin from db_academico.registro_marcacion_generada rgm
                inner join db_academico.horario_asignatura_periodo hap on rgm.hape_id = hap.hape_id 
                where rgm.rmtm_id = $rmtm_id;";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('getDetalleHorario '.$comando->getRawSql());
        $resultData = $comando->queryOne();

        return $resultData;
    }

    public function actualizarHoraFinClase( $rmtm_id, $min_retiro, $caac_id, $deac_id, $hora_fin) {
        $con = \Yii::$app->db_academico;
        $sql = "UPDATE db_academico.detalle_asistencia_clases 
                set deac_minutos_retiro = $min_retiro , deac_hora_fin_clase = '$hora_fin'
                where caac_id = $caac_id
                and rmtm_id = $rmtm_id and deac_id = $deac_id;";                      

        $comando = $con->createCommand($sql);
        \app\models\Utilities::putMessageLogFile('actualizarHoraFinClase '.$comando->getRawSql());
        $resultData = $comando->execute();

        return $resultData;
    }

   }
