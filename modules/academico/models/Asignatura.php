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
    public function getAsignatura_x_bloque_x_planif($pla_id, $jornada_id, $bloque) {
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
                FROM db_academico.planificacion_estudiante pe inner join db_academico.malla_academica_detalle md
                     on md.made_codigo_asignatura = pe.pes_mat_b2_h1_cod
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
     * Function getAsignaturaPosgrado
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar las asignaturas por planificación y modalidad).
     */
    public function getAsignaturaPosgrado($meun_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
            $sql = "SELECT c.asi_id id, c.asi_nombre name
                    FROM db_academico.malla_academica_detalle a inner join db_academico.malla_unidad_modalidad b
                        on b.maca_id = a.maca_id
                    INNER JOIN db_academico.asignatura c on c.asi_id = a.asi_id
                    WHERE b.meun_id = $meun_id
                          and b.mumo_estado = 1
                          and b.mumo_estado_logico = 1
                          and a.made_estado = 1
                          and a.made_estado_logico = 1
                    ORDER BY 2 asc";
           
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
        $comando->bindParam(":meun_id", $meun_id, \PDO::PARAM_INT);          
        $resultData = $comando->queryAll();
        return $resultData;
    } 
}
