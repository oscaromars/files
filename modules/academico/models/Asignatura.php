<?php

namespace app\modules\academico\models;
use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "asignatura".
 *
 * @property int $asi_id
 * @property int $scon_id
 * @property int $uaca_id
 * @property string $asi_nombre
 * @property string $asi_descripcion
 * @property string $asi_alias
 * @property int $asi_usuario_ingreso
 * @property int $asi_usuario_modifica
 * @property string $asi_estado
 * @property string $asi_fecha_creacion
 * @property string $asi_fecha_modificacion
 * @property string $asi_estado_logico
 *
 * @property SubareaConocimiento $scon
 * @property UnidadAcademica $uaca
 * @property Distributivo[] $distributivos
 * @property HorarioAsignaturaPeriodo[] $horarioAsignaturaPeriodos
 * @property MallaAcademicaDetalle[] $mallaAcademicaDetalles
 */
class Asignatura extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asignatura';
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
            [['scon_id', 'uaca_id', 'asi_nombre', 'asi_descripcion', 'asi_usuario_ingreso', 'asi_estado', 'asi_estado_logico'], 'required'],
            [['scon_id', 'uaca_id', 'asi_usuario_ingreso', 'asi_usuario_modifica'], 'integer'],
            [['asi_fecha_creacion', 'asi_fecha_modificacion'], 'safe'],
            [['asi_nombre', 'asi_alias'], 'string', 'max' => 300],
            [['asi_descripcion'], 'string', 'max' => 500],
            [['asi_estado', 'asi_estado_logico'], 'string', 'max' => 1],
            [['scon_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubareaConocimiento::className(), 'targetAttribute' => ['scon_id' => 'scon_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'asi_id' => 'Asi ID',
            'scon_id' => 'Scon ID',
            'uaca_id' => 'Uaca ID',
            'asi_nombre' => 'Asi Nombre',
            'asi_alias' => 'asi_alias',
            'asi_descripcion' => 'Asi Descripcion',
            'asi_usuario_ingreso' => 'Asi Usuario Ingreso',
            'asi_usuario_modifica' => 'Asi Usuario Modifica',
            'asi_estado' => 'Asi Estado',
            'asi_fecha_creacion' => 'Asi Fecha Creacion',
            'asi_fecha_modificacion' => 'Asi Fecha Modificacion',
            'asi_estado_logico' => 'Asi Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScon()
    {
        return $this->hasOne(SubareaConocimiento::className(), ['scon_id' => 'scon_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivos()
    {
        return $this->hasMany(Distributivo::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHorarioAsignaturaPeriodos()
    {
        return $this->hasMany(HorarioAsignaturaPeriodo::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaAcademicaDetalles()
    {
        return $this->hasMany(MallaAcademicaDetalle::className(), ['asi_id' => 'asi_id']);
    }
    
    function getAllAsignaturasGrid($search = NULL, $dataProvider = false){
        $iduser = Yii::$app->session->get('PB_iduser', FALSE);
        $search_cond = "%".$search."%";
        $str_search = "";
        if(isset($search)){
            $str_search = "(asi.asi_nombre like :search OR ";
            $str_search .= "scon.scon_nombre like :search OR ";
            $str_search .= "acon.acon_nombre like :search) AND";
        }
        $sql = "SELECT
                    asi.asi_id AS id,
                    a.uaca_nombre AS unidad,
                    asi.asi_nombre AS Nombre,
                    scon.scon_nombre AS SubAreaConocimiento,
                    acon.acon_nombre AS AreaConocimiento,
                    asi.asi_estado AS Estado
                FROM
                    asignatura as asi
                    INNER JOIN subarea_conocimiento as scon on scon.scon_id=asi.scon_id
                    INNER JOIN area_conocimiento as acon on acon.acon_id=scon.acon_id
                    INNER JOIN unidad_academica as a on a.uaca_id = asi.uaca_id
                WHERE
                    $str_search
                    asi.asi_estado_logico = 1
                    AND scon.scon_estado_logico = 1
                    AND acon.acon_estado_logico = 1
                    AND a.uaca_estado_logico = 1                    
                ORDER BY asi.asi_id;";
        $comando = Yii::$app->db_academico->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'asi_id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'SubAreaConocimiento', 'AreaConocimiento', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
    
    
    /**
     * Function getAsignatura_x_bloque_x_planif
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar las asignaturas por planificación y modalidad).
     */
    public function getAsignatura_x_bloque_x_planif_no_asignadas($pla_id, $bloque) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($bloque=="B1") {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                     on md.made_codigo_asignatura = pe.pes_mat_b1_h1_cod
                     inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b1_h2_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b1_h3_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b1_h4_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                 and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b1_h5_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1";
        } else {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h1_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b2_h2_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b2_h3_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b2_h4_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                 and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                    on md.made_codigo_asignatura = pe.pes_mat_b2_h5_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1";
        }        
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
        $comando->bindParam(":pla_id", $pla_id, \PDO::PARAM_INT);  
       // $comando->bindParam(":jornada_id", $jornada_id, \PDO::PARAM_STR);  
        $resultData = $comando->queryAll();
        return $resultData;
    } 
    
    
     /**
     * Function getAsignatura_x_bloque_x_planif
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar las asignaturas por planificación y modalidad).
     */
    public function getAsignaturaPara_asignar_paralelo($mod_id, $bloque,$paca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if ($bloque=="B1") {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM    db_academico.planificacion_estudiante pe 
                        inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                        inner join db_academico.malla_academica_detalle md     on md.made_codigo_asignatura = pe.pes_mat_b1_h1_cod
                        inner join db_academico.asignatura a on a.asi_id = md.asi_id
                        left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                     WHERE   pla_estado='1' 
                     and p.mod_id =:mod_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                     and mpp.asi_id is null
                    
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                     inner join db_academico.planificacion p on p.pla_id = pe.pla_id
                     inner join db_academico.malla_academica_detalle md      on md.made_codigo_asignatura = pe.pes_mat_b1_h2_cod
                     inner join db_academico.asignatura a on a.asi_id = md.asi_id
                     left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md        on md.made_codigo_asignatura = pe.pes_mat_b1_h3_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                   WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md          on md.made_codigo_asignatura = pe.pes_mat_b1_h4_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                    WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md   on md.made_codigo_asignatura = pe.pes_mat_b1_h5_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                    WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null";
        } else {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h1_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                    WHERE  pla_estado='1'
                     and p.mod_id =:mod_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                     and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md         on md.made_codigo_asignatura = pe.pes_mat_b2_h2_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                     inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md     on md.made_codigo_asignatura = pe.pes_mat_b2_h3_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id   
                WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                 inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                 inner join db_academico.malla_academica_detalle md    on md.made_codigo_asignatura = pe.pes_mat_b2_h4_cod
                 inner join db_academico.asignatura a on a.asi_id = md.asi_id
                left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                WHERE pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                 and pes_estado_logico = 1
                 and mpp.asi_id is null
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join  db_academico.planificacion p on p.pla_id = pe.pla_id
                    inner join db_academico.malla_academica_detalle md     on md.made_codigo_asignatura = pe.pes_mat_b2_h5_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                    left join  db_academico.materia_paralelo_periodo mpp on mpp.asi_id = a.asi_id and mpp.paca_id=:paca_id and mpp.mod_id=:mod_id
                    WHERE  pla_estado='1'
                    and p.mod_id =:mod_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                    and mpp.asi_id is null";
        }        
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
       $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);  
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_STR);  
        $resultData = $comando->queryAll();
        return $resultData;
    } 
    
    
    /**
     * Function getAsignatura_x_bloque_x_planif
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar las asignaturas por planificación y modalidad).
     */
    public function getAsignatura_x_bloque_x_planif($paca_id, $mod_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql ="select mpp.asi_id id,(select asi_nombre from db_academico.asignatura a where a.asi_id=mpp.asi_id) name 
                  from  db_academico.materia_paralelo_periodo mpp 
                 left join db_academico.distributivo_academico  da on da.mpp_id=mpp.mpp_id and da.mod_id=mpp.mod_id and da.asi_id=mpp.asi_id  and da.paca_id=mpp.paca_id
                 where da.mpp_id is null 
                 and mpp.mod_id=".$mod_id.
                 " and mpp.paca_id=".$paca_id.
                " group by id,name";
                
      /*  if ($bloque=="B1") {
            $sql = "SELECT * from ( 
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b1_h1_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md        on md.made_codigo_asignatura = pe.pes_mat_b1_h2_cod
                 inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md    on md.made_codigo_asignatura = pe.pes_mat_b1_h3_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md   on md.made_codigo_asignatura = pe.pes_mat_b1_h4_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                 and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md      on md.made_codigo_asignatura = pe.pes_mat_b1_h5_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1 ) b order by name";
        } else {
            $sql = "SELECT * from ( SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h1_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                     and pes_estado = 1
                     and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                    inner join db_academico.malla_academica_detalle md   on md.made_codigo_asignatura = pe.pes_mat_b2_h2_cod
                    inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md on md.made_codigo_asignatura = pe.pes_mat_b2_h3_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md       on md.made_codigo_asignatura = pe.pes_mat_b2_h4_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                 and pes_estado_logico = 1
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h5_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pla_id = :pla_id
                    and pes_estado = 1
                    and pes_estado_logico = 1 ) b order by name";
        } */      
        \app\models\Utilities::putMessageLogFile($sql);   
        $comando = $con->createCommand($sql);
       // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
       // $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);  
       // $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);  
       
        $resultData = $comando->queryAll();
        return $resultData;
    }   
    
    /**
     * Function getAsignaturaPosgrado
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar las asignaturas por planificación y modalidad).
     */
    public function getAsignaturaPosgrado($meun_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
            $sql = "SELECT c.asi_id id, c.asi_nombre name
                    FROM db_academico.malla_academica_detalle a 
                    inner join db_academico.malla_unidad_modalidad b  on b.maca_id = a.maca_id
                    INNER JOIN db_academico.asignatura c on c.asi_id = a.asi_id
                    WHERE b.meun_id = $meun_id
                          and b.mumo_estado = 1
                          and b.mumo_estado_logico = 1
                          and a.made_estado = 1
                          and a.made_estado_logico = 1
                    ORDER BY 2 asc";
           
        
        $comando = $con->createCommand($sql);
       // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
        $comando->bindParam(":meun_id", $meun_id, \PDO::PARAM_INT);          
        $resultData = $comando->queryAll();
        return $resultData;
    } 

    /**
     * Function Consultar id de asignatura por el alias.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsindxalias($asi_alias) {
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT 	
                        asi_id                    
                        
                FROM " . $con->dbname . ".asignatura              
                WHERE 
                uaca_id = 1 AND
                asi_alias = :asi_alias AND
                asi_estado = :estado AND
                asi_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":asi_alias", $asi_alias, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function Consultar id de asignatura.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsignaturasxuacaid($uaca_id) {
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT 	
                        asi_id as id,
                        asi_nombre as name               
                        
                FROM " . $con->dbname . ".asignatura              
                WHERE 
                uaca_id = :uaca_id AND               
                asi_estado = :estado AND
                asi_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Busca la asigantura por asi_id y por uaca_id
     * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAsignaturaConUnidad($asi_id, $uaca_id){
        $con = Yii::$app->db_academico;

        $sql = "SELECT asi_id, asi_nombre
                FROM " . $con->dbname . ".asignatura
                WHERE
                asi_id = :asi_id AND
                uaca_id = :uaca_id AND
                asi_estado = 1 AND
                asi_estado_logico = 1";

        $comando = $con->createCommand($sql);

        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

/**
     * Function consulta asiganturas ddel profesor, segun periodo, unidad, modalidad
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getAsignaturaRegistro($pro_id, $uaca_id, $mod_id, $paca_id){        
        $con_academico = \Yii::$app->db_academico;
        $estado = '1';

        //print_r($uaca_id);die();
        if($uaca_id == 0)
            $str_search = "";
        else
            $str_search = " and asig.uaca_id  = :uaca_id ";

        $sql = "SELECT distinct asig.asi_id as id, 
                       asig.asi_descripcion as name
                  FROM " . $con_academico->dbname . ".distributivo_academico AS daca
            INNER JOIN " . $con_academico->dbname . ".asignatura AS asig ON asig.asi_id = daca.asi_id
                 WHERE daca.pro_id  = :pro_id 
                   AND daca.paca_id = :paca_id 
                   AND asig.asi_estado = :estado 
                   AND asig.asi_estado_logico = :estado 
                   $str_search
              GROUP BY id, name
              ORDER BY name";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);

        if($uaca_id != 0)
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);    

        $res = $comando->queryAll();

        // \app\models\Utilities::putMessageLogFile('ASIGNATURAS gap: ' .$comando->getRawSql());
        return $res;
    }

    public function getCourseProfesor($pro_id,$paca_id,$asi_id){
        $con = \Yii::$app->db_academico;      
        $estado = 1;

        $sql = "SELECT
                    paralelo.par_id id, 
                    paralelo.par_nombre name 
                FROM
                        db_academico.distributivo_academico daca 
                    -- INNER JOIN db_academico.materias_paralelos_periodo_detalle mppd ON mppd.mppd_id = daca.mppd_id
                    INNER JOIN db_academico.paralelo paralelo ON paralelo.uaca_id = daca.uaca_id
                WHERE daca.pro_id = :pro_id  AND daca.paca_id = :paca_id AND  asi_id = :asi_id
                        AND daca.daca_estado = :estado  
                        AND daca.daca_estado_logico = 1
                        -- AND mppd.mppd_estado = 1 
                        -- AND mppd.mppd_estado_logico = 1 
                        AND paralelo.par_estado = 1 
                        AND paralelo.par_estado_logico = 1";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT); 
         $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT); 
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);          
        $resultData = $comando->queryAll();
        //\app\models\Utilities::putMessageLogFile('ASIGNATURAS gap: ' .$comando->getRawSql());
        return $resultData;
    } 


/**
     * Función para retornar todas las asignaturas, y filtrarlas por los IDs de profesor, unidad académica y período académico
     * @author  Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getAsignaturasBy($pro_id = NULL, $uaca_id = NULL, $paca_id = NULL){        
        $con_academico = Yii::$app->db_academico;

        $str_search = "";

        if($pro_id != 0 || $pro_id != NULL){
            $str_search .= " AND daca.pro_id  = " . $pro_id;
        }
        if($uaca_id != 0 || $uaca_id != NULL){
            $str_search .= " AND asi.uaca_id  = " . $uaca_id;
        }
        if($paca_id != 0 || $paca_id != NULL){
            $str_search .= " AND daca.paca_id = " . $paca_id;
        }

        $sql = "SELECT DISTINCT daca.paca_id, daca.uaca_id, daca.asi_id, asi.asi_descripcion
                FROM " . $con_academico->dbname . ".distributivo_academico AS daca
                INNER JOIN " . $con_academico->dbname . ".asignatura AS asi ON asi.asi_id = daca.asi_id
                WHERE
                daca.daca_estado = 1 AND daca.daca_estado_logico = 1 
                AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1 
                $str_search 
                ORDER BY asi_descripcion";

        $comando = $con_academico->createCommand($sql);   
        $res = $comando->queryAll();
        // \app\models\Utilities::putMessageLogFile($res);
        // \app\models\Utilities::putMessageLogFile('ASIGNATURAS gap: ' .$comando->getRawSql());
        return $res;
    }
    
    public function consultarAsignatura($asi_id){
        $con = Yii::$app->db_academico;      

        $sql = "SELECT * FROM db_academico.asignatura AS asi
                WHERE asi.asi_estado = 1 AND asi.asi_estado_logico = 1
                AND asi.asi_id = $asi_id";

        $comando = $con->createCommand($sql);         
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consulta asiganturas ddel profesor, segun periodo, unidad, modalidad para consulta  de calificaciones
     * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function getAsignaturaByProfesorDistributivo($paca_id,$pro_id,$uaca_id,$mod_id){
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        //inner join ". $con->dbname  .".unidad_academica uaca  on daca.uaca_id = uaca.uaca_id)
        $sql = "SELECT distinct 
                daca.asi_id id,
                asig.asi_descripcion  name
                FROM ". $con1->dbname  .".persona per inner join ".$con->dbname  .".profesor pro on per.per_id = pro.per_id
                inner join ". $con->dbname  .".distributivo_academico daca on daca.pro_id = pro.pro_id
                inner join ". $con->dbname  .".asignatura asig on asig.asi_id = daca.asi_id 
                WHERE 
                    per.per_estado = 1
                    and per.per_estado_logico = 1
                    
                    and pro.pro_estado = 1
                    and pro.pro_estado_logico = 1
                    
                    and daca.daca_estado = 1
                    and daca_estado_logico = 1
                    
                    and asig.asi_estado = 1
                    and asig.asi_estado_logico = 1

                    and pro.pro_id = :pro_id  
                    and daca.paca_id = :paca_id"; 
       //echo "Sentencia: ".$sql;
        $comando = $con->createCommand($sql);
        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
        $comando->bindParam(":pro_id", $pro_id, \PDO::PARAM_INT);
    
        $resultData = $comando->queryAll();
        return $resultData;
    }
}