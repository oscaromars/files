<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "cancelacion_registro_online".
 *
 * @property int $cron_id
 * @property int $ron_id
 * @property int $per_id
 * @property int $pla_id
 * @property int $paca_id
 * @property int $rpm_id
 * @property string $cron_estado_cancelacion
 * @property int $cron_aprueba
 * @property int $cron_confirma
 * @property string $cron_estado
 * @property string $cron_fecha_creacion
 * @property string $cron_fecha_modificacion
 * @property string $cron_estado_logico
 *
 * @property RegistroOnline $ron
 * @property CancelacionRegistroOnlineItem[] $cancelacionRegistroOnlineItems
 */
class CancelacionRegistroOnline extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cancelacion_registro_online';
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
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'cron_estado', 'cron_estado_logico'], 'required'],
            [['ron_id', 'per_id', 'pla_id', 'paca_id', 'rpm_id', 'cron_aprueba', 'cron_confirma'], 'integer'],
            [['cron_fecha_creacion', 'cron_fecha_modificacion'], 'safe'],
            [['cron_estado_cancelacion', 'cron_estado', 'cron_estado_logico'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cron_id' => 'Cron ID',
            'ron_id' => 'Ron ID',
            'per_id' => 'Per ID',
            'pla_id' => 'Pla ID',
            'paca_id' => 'Paca ID',
            'rpm_id' => 'Rpm ID',
            'cron_estado_cancelacion' => 'Cron Estado Cancelacion',
            'cron_aprueba' => 'Cron Aprueba',
            'cron_confirma' => 'Cron Confirma',
            'cron_estado' => 'Cron Estado',
            'cron_fecha_creacion' => 'Cron Fecha Creacion',
            'cron_fecha_modificacion' => 'Cron Fecha Modificacion',
            'cron_estado_logico' => 'Cron Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }
 
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCancelacionRegistroOnlineItems()
    {
        return $this->hasMany(CancelacionRegistroOnlineItem::className(), ['cron_id' => 'cron_id']);
    }

    public function getAllListCancelGrid($search = NULL, $mod_id = NULL, $estado = NULL, $periodo = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != "" && $estado != "-1"){
            //$estado = ($estado == 1)?1:0;
            $condition .= "cro.cron_estado_cancelacion = :estado AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }
        
        $sql = "SELECT
                    cro.cron_id as Id, 
                    r.ron_id as ron_id, 
                    p.pla_periodo_academico as Periodo,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.per_id as per_id,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    uac.uaca_nombre as UnidadAcademica,
                    eac.eaca_nombre as Programa,
                    cro.cron_estado_cancelacion as Estado,
                    rpm.rpm_estado_aprobacion as Pagado,
                    rpm.rpm_tipo_pago as TipoPago
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante as est on est.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa as ecp on est.est_id = ecp.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad as meu on meu.meun_id = ecp.meun_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica as uac on uac.uaca_id = meu.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico as eac on eac.eaca_id = meu.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online as cro on cro.ron_id = r.ron_id
                    INNER JOIN " . $con_academico->dbname . ".registro_pago_matricula as rpm on rpm.ron_id = cro.ron_id  
                WHERE 
                    $str_search 
                    $condition
                    rpm.rpm_tipo_pago = 2 and  
                    pe.pes_estado =1 and pe.pes_estado_logico =1 and
                    p.pla_estado =1 and p.pla_estado_logico =1 and
                    r.ron_estado =1 and r.ron_estado_logico =1 and
                    est.est_estado =1 and est.est_estado_logico =1 and
                    ecp.ecpr_estado =1 and ecp.ecpr_estado_logico =1 and
                    meu.meun_estado =1 and meu.meun_estado_logico =1 and
                    uac.uaca_estado =1 and uac.uaca_estado_logico =1 and
                    cro.cron_estado =1 and cro.cron_estado_logico =1 and
                    eac.eaca_estado =1 and eac.eaca_estado_logico =1 and
                    rpm.rpm_estado = 1 and rpm_estado_logico = 1";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0)  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "" && $estado != "-1")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }

    public function getAllListCancelAcademicoGrid($search = NULL, $mod_id = NULL, $estado = NULL, $periodo = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != "" && $estado != "-1"){
            //$estado = ($estado == 1)?1:0;
            $condition .= "cro.cron_estado_cancelacion = :estado AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }
        
        $sql = "SELECT distinct
                    cro.cron_id as Id, 
                    r.ron_id as ron_id, 
                    p.pla_periodo_academico as Periodo,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.per_id as per_id,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    uac.uaca_nombre as UnidadAcademica,
                    eac.eaca_nombre as Programa,
                    cro.cron_estado_cancelacion as Estado
                    -- rpm.rpm_tipo_pago as TipoPago
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante as est on est.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa as ecp on est.est_id = ecp.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad as meu on meu.meun_id = ecp.meun_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica as uac on uac.uaca_id = meu.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico as eac on eac.eaca_id = meu.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online as cro on cro.ron_id = r.ron_id
                    -- INNER JOIN " . $con_academico->dbname . ".registro_pago_matricula as rpm on rpm.ron_id = cro.ron_id  
                WHERE 
                    $str_search 
                    $condition                    
                    pe.pes_estado =1 and pe.pes_estado_logico =1 and
                    p.pla_estado =1 and p.pla_estado_logico =1 and
                    r.ron_estado =1 and r.ron_estado_logico =1 and
                    est.est_estado =1 and est.est_estado_logico =1 and
                    ecp.ecpr_estado =1 and ecp.ecpr_estado_logico =1 and
                    meu.meun_estado =1 and meu.meun_estado_logico =1 and
                    uac.uaca_estado =1 and uac.uaca_estado_logico =1 and
                    cro.cron_estado =1 and cro.cron_estado_logico =1 and
                    eac.eaca_estado =1 and eac.eaca_estado_logico =1 -- and
                    -- rpm.rpm_estado = 1 and rpm_estado_logico = 1
                     -- and rpm.rpm_tipo_pago =2
                    -- and r.ron_id =280";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0)  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "" && $estado != "-1")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }

    public function getAllInfoCancelGrid($search = NULL, $mod_id = NULL, $estado = NULL, $periodo = NULL, $per_id = NULL, $dataProvider = false){
        $con_academico = \Yii::$app->db_academico;
        $search_cond = "%" . $search . "%";
        $condition = "";
        $str_search = "";

        if (isset($search) && $search != "") {
            $str_search = "(pe.pes_nombres like :search OR ";
            $str_search .= "pe.pes_dni like :search) AND ";
        }
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0){
            $condition .= "m.mod_id = :mod_id AND ";
        }
        if(isset($estado) && $estado != "" && $estado != "-1"){
            //$estado = ($estado == 1)?1:0;
            $condition .= "cro.cron_estado_cancelacion = :estado AND ";
        }
        if(isset($periodo) && $periodo != ""){
            $periodo = "%" . $periodo . "%";
            $condition .= "p.pla_periodo_academico like :periodo AND ";
        }
        if(isset($per_id) && $per_id > 0){
            $condition .= "pe.per_id = :per_id AND ";
        }
        
        $sql = "SELECT
                    cro.cron_id as Id, 
                    r.ron_id as ron_id, 
                    p.pla_periodo_academico as Periodo,
                    pe.pes_nombres as Estudiante,
                    pe.pes_dni as Cedula,
                    pe.per_id as per_id,
                    pe.pes_carrera as Carrera,
                    m.mod_nombre as Modalidad,
                    uac.uaca_nombre as UnidadAcademica,
                    eac.eaca_nombre as Programa,
                    roi.roi_materia_cod as CodigoMateria,
                    roi.roi_materia_nombre as Materia,
                    roi.roi_creditos as Creditos,
                    roi.roi_costo as Costo,
                    cro.cron_estado_cancelacion as Estado
                FROM " . $con_academico->dbname . ".planificacion_estudiante AS pe
                    INNER JOIN " . $con_academico->dbname . ".planificacion as p on p.pla_id = pe.pla_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad as m on m.mod_id = p.mod_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online as r on r.pes_id = pe.pes_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante as est on est.per_id = pe.per_id
                    INNER JOIN " . $con_academico->dbname . ".estudiante_carrera_programa as ecp on est.est_id = ecp.est_id
                    INNER JOIN " . $con_academico->dbname . ".modalidad_estudio_unidad as meu on meu.meun_id = ecp.meun_id
                    INNER JOIN " . $con_academico->dbname . ".unidad_academica as uac on uac.uaca_id = meu.uaca_id
                    INNER JOIN " . $con_academico->dbname . ".estudio_academico as eac on eac.eaca_id = meu.eaca_id
                    INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online as cro on cro.ron_id = r.ron_id
                    INNER JOIN " . $con_academico->dbname . ".cancelacion_registro_online_item as cri on cri.cron_id = cro.cron_id
                    INNER JOIN " . $con_academico->dbname . ".registro_online_item as roi on roi.roi_id = cri.roi_id
                WHERE 
                    $str_search 
                    $condition
                    pe.pes_estado =1 and pe.pes_estado_logico =1 and
                    p.pla_estado =1 and p.pla_estado_logico =1 and
                    r.ron_estado =1 and r.ron_estado_logico =1 and
                    est.est_estado =1 and est.est_estado_logico =1 and
                    ecp.ecpr_estado =1 and ecp.ecpr_estado_logico =1 and
                    meu.meun_estado =1 and meu.meun_estado_logico =1 and
                    uac.uaca_estado =1 and uac.uaca_estado_logico =1 and
                    cro.cron_estado =1 and cro.cron_estado_logico =1 and
                    eac.eaca_estado =1 and eac.eaca_estado_logico =1";
        $comando = $con_academico->createCommand($sql);
        if(isset($search) && $search != "")  $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        if(isset($mod_id) && $mod_id != "" && $mod_id > 0)  $comando->bindParam(":mod_id",$mod_id, \PDO::PARAM_INT);
        if(isset($estado) && $estado != "" && $estado != "-1")  $comando->bindParam(":estado",$estado, \PDO::PARAM_INT);
        if(isset($periodo) && $periodo != "") $comando->bindParam(":periodo",$periodo, \PDO::PARAM_STR);
        if(isset($per_id) && $per_id > 0) $comando->bindParam(":per_id",$per_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Estudiante', 'Cedula',"Carrera","Modalidad","Periodo","Estado"],
                ],
            ]);

            return $dataProvider;
        }
        return $res;
    }
}
