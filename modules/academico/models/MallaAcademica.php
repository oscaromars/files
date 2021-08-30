<?php

namespace app\modules\academico\models;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use Yii;

/**
 * This is the model class for table "malla_academica".
 *
 * @property int $maca_id
 * @property string $maca_tipo
 * @property string $maca_codigo
 * @property string $maca_nombre
 * @property string $maca_fecha_vigencia_inicio
 * @property string $maca_fecha_vigencia_fin
 * @property int $maca_usuario_ingreso
 * @property int $maca_usuario_modifica
 * @property string $maca_estado
 * @property string $maca_fecha_creacion
 * @property string $maca_fecha_modificacion
 * @property string $maca_estado_logico
 *
 * @property MallaAcademicaDetalle[] $mallaAcademicaDetalles
 * @property MallaUnidadModalidad[] $mallaUnidadModalidads
 * @property PlanificacionAcademicaMalla[] $planificacionAcademicaMallas
 */
class MallaAcademica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'malla_academica';
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
            [['maca_nombre', 'maca_usuario_ingreso', 'maca_estado', 'maca_estado_logico'], 'required'],
            [['maca_fecha_vigencia_inicio', 'maca_fecha_vigencia_fin', 'maca_fecha_creacion', 'maca_fecha_modificacion'], 'safe'],
            [['maca_usuario_ingreso', 'maca_usuario_modifica'], 'integer'],
            [['maca_tipo', 'maca_estado', 'maca_estado_logico'], 'string', 'max' => 1],
            [['maca_codigo'], 'string', 'max' => 50],
            [['maca_nombre'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'maca_id' => 'Maca ID',
            'maca_tipo' => 'Maca Tipo',
            'maca_codigo' => 'Maca Codigo',
            'maca_nombre' => 'Maca Nombre',
            'maca_fecha_vigencia_inicio' => 'Maca Fecha Vigencia Inicio',
            'maca_fecha_vigencia_fin' => 'Maca Fecha Vigencia Fin',
            'maca_usuario_ingreso' => 'Maca Usuario Ingreso',
            'maca_usuario_modifica' => 'Maca Usuario Modifica',
            'maca_estado' => 'Maca Estado',
            'maca_fecha_creacion' => 'Maca Fecha Creacion',
            'maca_fecha_modificacion' => 'Maca Fecha Modificacion',
            'maca_estado_logico' => 'Maca Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaAcademicaDetalles()
    {
        return $this->hasMany(MallaAcademicaDetalle::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaUnidadModalidads()
    {
        return $this->hasMany(MallaUnidadModalidad::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionAcademicaMallas()
    {
        return $this->hasMany(PlanificacionAcademicaMalla::className(), ['maca_id' => 'maca_id']);
    }
    
    
     /**
     * Function consultar mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarMallas($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "m.maca_nombre like :malla AND ";                
            }            
        }
            
        $sql = "SELECT  maca_id, maca_codigo, maca_nombre, 
                        ifnull(maca_fecha_vigencia_inicio,'') as fechainicial, 
                        ifnull(maca_fecha_vigencia_fin,'') as fechafin
                FROM " . $con->dbname . ".malla_academica m
                WHERE $str_search
                      maca_estado = '1'
                      and maca_estado_logico = '1'";

        $comando = $con->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":malla", $search_cond, \PDO::PARAM_STR);
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
     * Function consultar detalle de mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarDetallemallaXid($maca_id, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
                     
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "(a.asi_nombre like :asignatura) AND ";                
            }            
        }
        $sql = "SELECT  d.made_codigo_asignatura,    
                        a.asi_nombre, 
                        d.made_semestre,
                        d.made_credito,
                        u.uest_nombre,       
                        f.fmac_nombre,
                        ifnull(asi.asi_nombre,'') as materia_requisito
                FROM " . $con->dbname . ".malla_academica m inner join " . $con->dbname . ".malla_academica_detalle d on d.maca_id = m.maca_id
                    inner join " . $con->dbname . ".asignatura a on a.asi_id = d.asi_id
                    inner join " . $con->dbname . ".unidad_estudio u on u.uest_id = d.uest_id
                    inner join " . $con->dbname . ".nivel_estudio n on n.nest_id = d.nest_id
                    inner join " . $con->dbname . ".formacion_malla_academica f on f.fmac_id = d.fmac_id
                    left join " . $con->dbname . ".asignatura asi on asi.asi_id = d.made_asi_requisito
                WHERE $str_search
                    m.maca_id = :malla
                    and m.maca_estado = '1'
                    and m.maca_estado_logico = '1'
                    and d.made_estado = '1'
                    and d.made_estado = '1'";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":malla", $maca_id, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":asignatura", $search_cond, \PDO::PARAM_STR);
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
     * Function consultar cabecera de mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarCabeceraMalla($maca_id) {
        $con = \Yii::$app->db_academico;
        $estado = '1';                 
        $sql = "SELECT m.maca_id, m.maca_nombre malla, ua.uaca_descripcion unidad, mo.mod_descripcion modalidad, e.eaca_descripcion carrera_programa
                FROM " . $con->dbname . ".malla_academica m inner join " . $con->dbname . ".malla_unidad_modalidad u on u.maca_id = m.maca_id
                    inner join " . $con->dbname . ".modalidad_estudio_unidad mu on mu.meun_id = u.meun_id
                    inner join " . $con->dbname . ".estudio_academico e on e.eaca_id = mu.eaca_id
                    inner join " . $con->dbname . ".unidad_academica ua on ua.uaca_id = mu.uaca_id
                    inner join " . $con->dbname . ".modalidad mo on mo.mod_id = mu.mod_id
                WHERE u.maca_id = :maca_id
                    and m.maca_estado = :estado
                    and m.maca_estado_logico = :estado
                    and u.mumo_estado = :estado
                    and u.mumo_estado_logico = :estado
                    and mu.meun_estado = :estado
                    and mu.meun_estado_logico = :estado
                    and e.eaca_estado = :estado
                    and e.eaca_estado_logico = :estado
                    and ua.uaca_estado = :estado
                    and ua.uaca_estado_logico = :estado
                    and mo.mod_estado = :estado
                    and mo.mod_estado_logico = :estado";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
       
        $resultData = $comando->queryAll();               
        return $resultData;        
    }  

     /**
     * Function obtener consultarmalla por unidad, modalidad, carrera
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property       
     * @return  
     */
    public function consultarmallasxcarrera($uaca_id, $mod_id, $eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    mac.maca_id AS id,
                    concat(mac.maca_codigo,' - ',mac.maca_nombre) AS name  
               FROM " . $con->dbname . ".modalidad_estudio_unidad meu  
               INNER JOIN " . $con->dbname . ".malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
               INNER JOIN " . $con->dbname . ".malla_academica mac ON mac.maca_id = mum.maca_id 
               WHERE  meu.meun_estado_logico = :estado AND
                      meu.meun_estado = :estado AND
                      mum.mumo_estado_logico = :estado AND
                      mum.mumo_estado = :estado AND
                      mac.maca_estado_logico = :estado AND
                      mac.maca_estado = :estado AND
                      meu.uaca_id = :uaca_id AND
                      meu.mod_id = :mod_id AND
                      meu.eaca_id = :eaca_id";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener consultarasignaturaxmalla 
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property       
     * @return  
     */
    public function consultarasignaturaxmalla($maca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT mad.asi_id as id, concat(mad.made_codigo_asignatura, ' - ', asi.asi_nombre) as name
                    FROM " . $con->dbname . ".malla_academica_detalle mad
                    INNER JOIN " . $con->dbname . ".asignatura asi ON asi.asi_id = mad.asi_id
                    WHERE   mad.maca_id = :maca_id AND
                            mad.made_estado = :estado AND
                            mad.made_estado_logico = :estado AND
                            asi.asi_estado = :estado AND
                            asi.asi_estado_logico = :estado
                    ORDER BY name";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function consultarasignaturaxmallaaut($per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT mad.asi_id as 'id', concat(mad.made_codigo_asignatura, ' - ', asi.asi_nombre) as 'name'
                        FROM " . $con->dbname . ".malla_academica_detalle mad
                        INNER JOIN " . $con->dbname . ".asignatura asi ON asi.asi_id = mad.asi_id
                        WHERE   mad.maca_id = :maca_id AND
                                mad.made_estado = :estado AND
                                mad.made_estado_logico = :estado AND
                                asi.asi_estado = :estado AND
                                asi.asi_estado_logico = :estado and 
                                mad.made_codigo_asignatura not in 
                (SELECT mad.made_codigo_asignatura
                        FROM " . $con->dbname . ".malla_academica_detalle mad
                        INNER JOIN " . $con->dbname . ".asignatura asi ON asi.asi_id = mad.asi_id
                        INNER JOIN " . $con->dbname . ".planificacion_estudiante ples on mad.made_codigo_asignatura in (ples.pes_mat_b1_h1_cod,ples.pes_mat_b1_h2_cod,ples.pes_mat_b1_h3_cod,ples.pes_mat_b1_h4_cod,ples.pes_mat_b1_h5_cod,ples.pes_mat_b1_h6_cod,
                                                                ples.pes_mat_b2_h1_cod,ples.pes_mat_b2_h2_cod,ples.pes_mat_b2_h3_cod,ples.pes_mat_b2_h4_cod,ples.pes_mat_b2_h5_cod,ples.pes_mat_b2_h6_cod)
                        INNER JOIN " . $con->dbname . ".planificacion pla on pla.pla_id = ples.pla_id                     
                        WHERE   mad.maca_id = :maca_id AND
                                mad.made_estado = :estado AND
                                mad.made_estado_logico = :estado AND
                                asi.asi_estado = :estado AND
                                asi.asi_estado_logico = :estado and
                                pla.pla_estado_logico = :estado and
                                pla.pla_estado = :estado and
                                mad.made_codigo_asignatura in (ples.pes_mat_b1_h1_cod,ples.pes_mat_b1_h2_cod,ples.pes_mat_b1_h3_cod,ples.pes_mat_b1_h4_cod,ples.pes_mat_b1_h5_cod,ples.pes_mat_b1_h6_cod,
                                                                ples.pes_mat_b2_h1_cod,ples.pes_mat_b2_h2_cod,ples.pes_mat_b2_h3_cod,ples.pes_mat_b2_h4_cod,ples.pes_mat_b2_h5_cod,ples.pes_mat_b2_h6_cod)
)
                    ORDER BY name";
        $sql2="SELECT distinct(maes.asi_id) as 'id',
        concat(made.made_codigo_asignatura,' - ',a.asi_nombre) as 'name',
        made.made_asi_requisito as 'materia previa',
        (select b.asi_nombre from " . $con->dbname . ".asignatura b where b.asi_id = maes.asi_id) as 'requisito'
        from " . $con->dbname . ".malla_academico_estudiante maes
        inner join " . $con->dbname . ".promedio_malla_academico pmac on pmac.maes_id = maes.maes_id
        inner join " . $con->dbname . ".asignatura a on a.asi_id = maes.asi_id
        inner join " . $con->dbname . ".malla_academica_detalle made on made.made_id = maes.made_id,
        " . $con->dbname . ".planificacion_estudiante ples  
        where maes.per_id = $per_id and pmac.enac_id not in (1,4) and
        made.made_codigo_asignatura not in
        (select mad.made_codigo_asignatura from " . $con->dbname . ".planificacion_estudiante ple,
        " . $con->dbname . ".malla_academica_detalle mad
        where mad.made_codigo_asignatura in (
        ple.pes_mat_b1_h1_cod,ple.pes_mat_b1_h2_cod,ple.pes_mat_b1_h3_cod,ple.pes_mat_b1_h4_cod,ple.pes_mat_b1_h5_cod,ple.pes_mat_b1_h6_cod,
        ple.pes_mat_b2_h1_cod,ple.pes_mat_b2_h2_cod,ple.pes_mat_b2_h3_cod,ple.pes_mat_b2_h4_cod,ple.pes_mat_b2_h5_cod,ple.pes_mat_b2_h6_cod)
        and per_id = $per_id)";    
        $comando = $con->createCommand($sql2);
        if($per_id == null){
            $resultData = [];
        }else{
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            //$comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        return $resultData;
    }

    /**
     * Function Consultar malla segun la carrera del estudiante autenticado.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarMallaEstudiante($per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT  
                    mac.maca_id as idmalla,
                    mac.maca_codigo as codmalla        
                FROM " . $con->dbname . ".estudiante est
                INNER JOIN " . $con->dbname . ".estudiante_carrera_programa ecp  ON ecp.est_id = est.est_id
                INNER JOIN " . $con->dbname . ".malla_unidad_modalidad mum  ON mum.meun_id = ecp.meun_id
                INNER JOIN " . $con->dbname . ".malla_academica mac  ON mac.maca_id = mum.maca_id
                WHERE   est.per_id = :per_id                        
                AND est.est_estado = :estado
                AND est.est_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    
    function consultarAsignaturas($rows,$gest,$semestre,$modalidad) {
    $con = \Yii::$app->db_academico;
    $activo="A"; // $rows["mod_id"]
    $per_id = $rows["per_id"];

       $presql="
       SELECT distinct
min(md.made_semestre) as semestre,
per.per_id
from db_academico.malla_academico_estudiante pa
inner join db_asgard.persona per on per.per_id=pa.per_id
inner join db_academico.estudiante es on es.per_id=per.per_id
inner join db_academico.estudiante_carrera_programa est on es.est_id=est.est_id
inner join db_academico.modalidad_estudio_unidad me on me.meun_id=est.meun_id
inner join db_academico.malla_academica_detalle md on md.made_id=pa.made_id
inner join db_academico.malla_unidad_modalidad mu on mu.maca_id=pa.maca_id
inner join db_academico.malla_academica ma on ma.maca_id=pa.maca_id
inner join db_academico.asignatura a on pa.asi_id=a.asi_id
inner join db_academico.estudio_academico ea on ea.eaca_id=me.eaca_id
-- inner join db_academico.historico_siga_prueba h on h.per_id=pa.per_id
inner join db_academico.modalidad mo on mo.mod_id=me.mod_id
inner join db_academico.promedio_malla_academico n on pa.maes_id=n.maes_id
inner join db_academico.estado_nota_academico e on e.enac_id=n.enac_id
-- left join db_academico.planificar_periodo_academico pp on pp.maes_id=pa.maes_id
left join db_academico.periodo_academico pe on n.paca_id=pe.paca_id
left join db_academico.semestre_academico s on s.saca_id=pe.saca_id
left join db_academico.bloque_academico b on b.baca_id=pe.baca_id
WHERE
per.per_id = pa.per_id
AND per.per_id = :per_id
AND e.enac_id = 3;
       ";

        $comando = $con->createCommand($presql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $currenter = $comando->queryOne();
      /*  if ($currenter["semestre"] == Null){ 
         $student_semester= 1;
         $last_semester= 1;
     } else {

         if ($currenter["semestre"] < 9){  
        $student_semester= $currenter["semestre"]+2;
        $last_semester= $currenter["semestre"];
            } else {
        $student_semester= $currenter["semestre"];
        $last_semester= $currenter["semestre"];
            }

    }*/

    $student_semester= $currenter["semestre"];
    if ($currenter["semestre"] == Null){ 
         $student_semester= 11;
     }
       


        $sql = "select distinct  a.maca_id, a.asi_id, a.made_semestre, a.uest_id, a.nest_id, a.fmac_id, 
a.made_codigo_asignatura, a.made_asi_requisito, a.made_credito, c.uaca_id,
c.mod_id, c.eaca_id, d.asi_nombre
from db_academico.malla_academica_detalle a
inner join db_academico.malla_unidad_modalidad b on b.maca_id = a.maca_id 
inner join db_academico.modalidad_estudio_unidad c on c.meun_id = b.meun_id
 inner join db_academico.asignatura d on d.asi_id = a.asi_id
                       where c.eaca_id =  " . $rows["eaca_id"] . "   
                      and   c.mod_id =  " . $modalidad . "   
                      and a.maca_id =  " . $rows["maca_id"] . "  
                      and c.uaca_id = 1
                            and a.made_estado = 1
                            and a.made_estado_logico = 1
                            and b.mumo_estado = 1
                            and b.mumo_estado_logico = 1
                            and c.meun_estado = 1
                            and c.meun_estado_logico = 1
                            and d.asi_estado = 1
                            and d.asi_estado_logico = 1
                     ORDER BY a.maca_id, a.made_semestre, a.made_asi_requisito ASC
                        ";
  

   $comando = $con->createCommand($sql);
         // $comando->bindParam(":activo", $activo, \PDO::PARAM_STR);
         // $comando->bindParam(":paca_id", $gest, \PDO::PARAM_INT);
         // $comando->bindParam(":semester", $student_semester, \PDO::PARAM_INT);
        //  $comando->bindParam(":lastsemester", $last_semester, \PDO::PARAM_INT);
               $rows_in = $comando->queryAll();


        $sql4="
                        SELECT distinct
 md.maca_id, md.asi_id, md.made_semestre, md.uest_id, md.nest_id, md.fmac_id, 
md.made_codigo_asignatura, md.made_asi_requisito, md.made_credito,
me.uaca_id,me.mod_id, me.eaca_id, a.asi_nombre,
CONCAT(per.per_cedula,' - ',per.per_pri_nombre,' ',per.per_pri_apellido) estudiante,
ma.maca_nombre as carrera,
md.made_codigo_asignatura,
a.asi_nombre,
mo.mod_nombre,
CONCAT(md.made_semestre,'°Semestre') semestre,
n.pmac_nota,
e.enac_asig_estado,
CONCAT(s.saca_nombre,' - ', s.saca_anio) periodo,
b.baca_nombre,
per.per_id,
es.est_matricula
from db_academico.malla_academico_estudiante pa
inner join db_asgard.persona per on per.per_id=pa.per_id
inner join db_academico.estudiante es on es.per_id=per.per_id
inner join db_academico.estudiante_carrera_programa est on es.est_id=est.est_id
inner join db_academico.modalidad_estudio_unidad me on me.meun_id=est.meun_id
inner join db_academico.malla_academica_detalle md on md.made_id=pa.made_id
inner join db_academico.malla_unidad_modalidad mu on mu.maca_id=pa.maca_id
inner join db_academico.malla_academica ma on ma.maca_id=pa.maca_id
inner join db_academico.asignatura a on pa.asi_id=a.asi_id
inner join db_academico.estudio_academico ea on ea.eaca_id=me.eaca_id
-- inner join db_academico.historico_siga_prueba h on h.per_id=pa.per_id
inner join db_academico.modalidad mo on mo.mod_id=me.mod_id
inner join db_academico.promedio_malla_academico n on pa.maes_id=n.maes_id
inner join db_academico.estado_nota_academico e on e.enac_id=n.enac_id
-- left join db_academico.planificar_periodo_academico pp on pp.maes_id=pa.maes_id
left join db_academico.periodo_academico pe on n.paca_id=pe.paca_id
left join db_academico.semestre_academico s on s.saca_id=pe.saca_id
left join db_academico.bloque_academico b on b.baca_id=pe.baca_id
WHERE
per.per_id = pa.per_id
 and md.maca_id =  " . $rows["maca_id"] . "  
AND per.per_id = :per_id
AND md.made_semestre >= :semester
ORDER BY semestre;

        ";
                        
              /*  $comando = $con->createCommand($sql);
          $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
          $comando->bindParam(":semester", $student_semester, \PDO::PARAM_INT);
               $rows_in = $comando->queryAll(); */

             $per_id =   $rows["per_id"];
             $est_id =   $rows["est_id"];
             $matricula =   $rows["matricula"];
             $creacion =   $rows["est_fecha_creacion"];
             $categoria =   $rows["est_categoria"];
             $uaca_id =   1;
             $mod_id =   $modalidad;
             $eaca_id =   $rows["eaca_id"];
             $uaca_nombre =   $rows["uaca_nombre"];
             $teac_id =   $rows["teac_id"];
             $eaca_nombre =   $rows["eaca_nombre"];
             $eaca_codigo =   $rows["eaca_codigo"];
             $cedula =   $rows["per_cedula"];
             $estudiante =   $rows["estudiante"];
              $estado = '1';
            
            //   1 . 1 GET 


             $geth="
             SELECT hose_id from db_academico.horarios_semestre
             WHERE saca_id = :semestre
             AND mod_id = :mod_id
             AND uaca_id = :uaca_id 
                   "
                 ;
            
            $comando = $con->createCommand($geth);
            $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $hid = $comando->queryOne();
            $horario =$hid["hose_id"];

            
            //   1  .  2  SET 


             if ($hid["hose_id"] == Null){


             $puth = "
             INSERT INTO db_academico.horarios_semestre
             (saca_id, mod_id, uaca_id,hose_usuario_ingreso, hose_estado, hose_estado_logico)
            VALUES ('" . $gest . "','" . $modalidad . "','" . $uaca_id . "',1, '" . $estado . "', '" . $estado . "')"
            ;
            
               $comando = $con->createCommand($puth); 
                     $puttedh = $comando->execute();

            
             $geth="
             SELECT hose_id from db_academico.horarios_semestre
             WHERE saca_id = :semestre
             AND mod_id = :mod_id
             AND uaca_id = :uaca_id 
                   "
                 ;
            
            $comando = $con->createCommand($geth);
            $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $hid = $comando->queryOne();
            $horario =$hid["hose_id"];

            
            }

             \app\models\Utilities::putMessageLogFile('hose id:'.$hid["hose_id"]);

        if (count($rows_in) > 0) {   
          $subjects= array();
        
         $sql = "select plac.paca_fecha_inicio, plac.paca_fecha_fin
from db_academico.periodo_academico plac 
                    where plac.paca_id= :paca_id                  
                        ";

         $comando = $con->createCommand($sql);
         $comando->bindParam(":paca_id", $gest, \PDO::PARAM_INT);
               $paca_in = $comando->queryAll();

                $estacion = $gest;
                $paca_id = $gest;
          for ($i = 0; $i < count($rows_in); $i++) {    
          
           $mod_id = $rows_in[$i]["mod_id"];
                 $asignatura = $rows_in[$i]["asi_id"];
                 $requisito = $rows_in[$i]["made_asi_requisito"]; 
                 $fecha_inicio = $paca_in[0]["paca_fecha_inicio"];
                 $fecha_fin = $paca_in[0]["paca_fecha_fin"];
                             
                  $sql = "
                 select  a.asi_id, c.enac_id, a.maes_id, a.per_id
 from db_academico.malla_academico_estudiante a
 left join db_academico.promedio_malla_academico b on a.maes_id = b.maes_id
   left join db_academico.estado_nota_academico c on c.enac_id = b.enac_id   
   inner join db_academico.asignatura d on a.asi_id = d.asi_id
   where a.per_id = :per_id
   and a.asi_id = :asignatura
                       and a.maes_estado = 1
                    and a.maes_estado_logico = 1
                    -- and b.pmac_estado = 1
                    -- and b.pmac_estado_logico = 1
                    -- and c.enac_estado = 1
                    -- and c.enac_estado_logico = 1
                     

                ";
                     
                     $comando = $con->createCommand($sql);
                     $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
                     $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
                     $statusasi = $comando->queryOne();
                 
                 if ($requisito !=Null) {    
                 $sql = "
                 select  a.asi_id, c.enac_id, a.maes_id, a.per_id
 from db_academico.malla_academico_estudiante a
 left join db_academico.promedio_malla_academico b on a.maes_id = b.maes_id
   left join db_academico.estado_nota_academico c on c.enac_id = b.enac_id   
   inner join db_academico.asignatura d on a.asi_id = d.asi_id
   where a.per_id = :per_id
   and a.asi_id = :requisito
                       and a.maes_estado = 1
                    and a.maes_estado_logico = 1
                    -- and b.pmac_estado = 1
                    -- and b.pmac_estado_logico = 1
                    -- and c.enac_estado = 1
                    -- and c.enac_estado_logico = 1
                     

                ";
                
                     $comando = $con->createCommand($sql);
                     $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
                     $comando->bindParam(":requisito", $requisito, \PDO::PARAM_INT);
                     $statuspre = $comando->queryOne();


                      if ($statuspre["enac_id"]==1 or $statuspre["enac_id"]==4 ){           
                    $sstatuspre = True; 
                    }    Else {     $sstatuspre = False;     }
                            
                     } Else {
                       $sstatuspre = True;  

                     }
                    
         

                
                    
         if ($statusasi["enac_id"]==3 or $statusasi["enac_id"]==2 or $statusasi["enac_id"]== Null ){ 
                      
                       
                      
                        if ($sstatuspre = True){                     
                         

                     if ($subjects[1][0] == Null)  {                   
                   $subjects[1][0] = $rows_in[$i]["made_codigo_asignatura"];
                   $subjects[1][1] = $rows_in[$i]["asi_nombre"];    
                   $subjects[1][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[1][3] = $rows_in[$i]["made_credito"];    }  
                elseif ($subjects[2][0]== Null)  { 
                    $subjects[2][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[2][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[2][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[2][3] = $rows_in[$i]["made_credito"];   }      
                 elseif ($subjects[3][0]== Null)  { 
                    $subjects[3][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[3][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[3][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[3][3] = $rows_in[$i]["made_credito"];   }  
                 elseif ($subjects[4][0]== Null)  { 
                    $subjects[4][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[4][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[4][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[4][3] = $rows_in[$i]["made_credito"];   } 
                 elseif ($subjects[5][0]== Null)  { 
                    $subjects[5][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[5][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[5][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[5][3] = $rows_in[$i]["made_credito"];   }  
                 elseif ($subjects[6][0]== Null)  { 
                    $subjects[6][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[6][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[6][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[6][3] = $rows_in[$i]["made_credito"];   } 
                 elseif ($subjects[7][0]== Null)  { 
                    $subjects[7][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[7][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[7][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[7][3] = $rows_in[$i]["made_credito"];   } 
                 elseif ($subjects[8][0]== Null)  { 
                    $subjects[8][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[8][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[8][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[8][3] = $rows_in[$i]["made_credito"];   }  

                               
                    }
                            
                         }         
                         

                        }  
       
                     $sql = "select pla_id from db_academico.planificacion 
                      where mod_id = " . $modalidad . " 
                      and pla_estado = 1 and pla_estado_logico = 1 and saca_id = :periodo";  
                
                   $comando = $con->createCommand($sql);
                    $comando->bindParam(":periodo", $estacion, \PDO::PARAM_STR);
                     $rows_pla = $comando->queryOne();
                     
                     $estado=1;
                     
                               if ($rows_pla["pla_id"] == 0)  {
                                 
                
                $sql = "INSERT INTO db_academico.planificacion (mod_id, per_id, pla_fecha_inicio, pla_fecha_fin, pla_periodo_academico, pla_estado, pla_estado_logico,saca_id)
                        VALUES ('" . $modalidad . "', 1, '" . $fecha_inicio . "', '" . $fecha_fin . "', '" . $semestre . "', '" . $estado . "', '" . $estado . "','" . $estacion . "');";
                 $comando = $con->createCommand($sql); 
                     $rows_pla = $comando->execute();
              
                  } 
                 $sql = "select pla_id from db_academico.planificacion where mod_id = " . $modalidad . " and pla_estado = 1 and pla_estado_logico = 1 and saca_id = :periodo ";
                  $comando = $con->createCommand($sql);
                   $comando->bindParam(":periodo", $estacion, \PDO::PARAM_STR);
                   $rows_pla = $comando->queryOne();
           
         
                     
                          if ($subjects[1][0] != Null)  { 
                                       

                     switch ($modalidad) {
                        case 1:
                      $jornadas = "Nocturna";
                        break;
                        case 2:
                      $jornadas = "Nocturna";
                         break;
                         case 3:
                     $jornadas = "Semi-Presencial";
                     break;
                       case 4:
                     $jornadas = "Distancia";
                     break;
                    }
                    
                    if ($modalidad > 2){
                    
                    
            //   1   .    3    SCHEDULE 
    

                     $lasth="
                    SELECT max(dahd.hosd_grupo) as g 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                     ";

                   $comando = $con->createCommand($lasth);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getlastg = $comando->queryOne();

                   
                    $again="SELECT  max(dahd.hosd_bloque) as b  
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_grupo = :grupo
                     ";

                   $comando = $con->createCommand($again);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":grupo", $getlastg["g"], \PDO::PARAM_INT);
                   $getlastb = $comando->queryOne();   


                     $andagain="SELECT max(dahd.hosd_hora) as h 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_grupo = :grupo
                    and dahd.hosd_bloque = :bloque
                     ";

                   $comando = $con->createCommand($andagain);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":grupo", $getlastg["g"], \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $getlastb["b"], \PDO::PARAM_INT);
                   $getlasth = $comando->queryOne();


                    $gactual= $getlastg["g"];   
                    $bactual= $getlastb["b"];    
                    $hactual= $getlasth["h"];   

                     if ($gactual == Null)   
                        { $gactual = 1 ; }      
                    if ($bactual == Null)   
                        { $bactual = 1 ; }      
                    if ($hactual == Null)   
                        { $hactual = 0 ; }       
                    
                                  
                    
                     for ($iter = 1; $iter <= 8; ++$iter){

                    $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2];
                   $cred = $subjects[$iter][3]; 


                     $searcher = "
                    SELECT dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahd.hosd_asi_id = :iddd
                    AND dahs.saca_id = :saca_id
                    AND dahs.mod_id = :mod_id
                    AND dahs.uaca_id = :uaca_id
                    ";

                   $comando = $con->createCommand($searcher);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getifasi = $comando->queryOne();


                       If ($subjects[$iter][2] !="" && $subjects[$iter][2]!=Null){
                            if ($getifasi["hose_id"] == Null){


                    if ($hactual == 3){  
                         if ($bactual == 2){  $gactual++ ;$bactual = 1; $hactual = 1;}
                            Else  { $bactual = 2; $hactual = 1;  }
                     } Else {
                    $hactual++ ;
                     }   


                     $addsch = "
            INSERT INTO db_academico.horarios_semestre_detalle (hose_id, hosd_grupo, hosd_bloque, hosd_hora ,hosd_asi_id,hosd_usuario_ingreso,hosd_estado,hosd_estado_logico)
            VALUES ('" . $hid["hose_id"] . "','" . $gactual . "','" . $bactual . "','" . $hactual . "','" . $subjects[$iter][2] . "',1,'1','1')
                        ";
                  $comando = $con->createCommand($addsch); 
                  $rows_tosch = $comando->execute();

                   //GET hosd_id   - ADD WITH  paal_cantidad 1 


               $searchhosd = "
                   SELECT dahd.hosd_id, dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                     WHERE 
                    dahd.hosd_asi_id = :iddd AND
                    dahd.hosd_grupo = :hosd_grupo AND
                    dahd.hosd_bloque = :hosd_bloque AND
                     dahd.hosd_hora = :hosd_hora AND
                    dahs.hose_id = :hose_id
                    ";              

                $comando = $con->createCommand($searchhosd);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_grupo", $gactual, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_bloque", $bactual, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_hora", $hactual, \PDO::PARAM_INT);
                     $comando->bindParam(":hose_id", $hid["hose_id"], \PDO::PARAM_INT);
                   $rows_tosch = $comando->queryOne();

                     $addschpar = "
            INSERT INTO db_academico.paralelos_alumno (hosd_id, paal_cantidad,paal_usuario_ingreso,paal_estado,paal_estado_logico)
            VALUES ('" . $rows_tosch["hosd_id"] . "',0,1,'1','1')
                        ";
                $comando = $con->createCommand($addschpar); 
                  $rows_tosch = $comando->execute();


                     }  }
                 }  //END ITER



                  //   1   .    4     PES
               $mpph1 = 0; $mpph2 = 0; $mpph3 = 0; $mpph4 = 0;
                $mpph5 = 0; $mpph6 = 0; $mpph7 = 0; $mpph8 = 0;
                  $mpph9 = 0; $mpph10 = 0; $mpph11 = 0; $mpph12 = 0;
                
              for ($iter = 1;$iter <= 8; ++$iter){

                   $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 
                   $horario= $hid["hose_id"];
                 
                   
                 $searcher = "
                    SELECT dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahd.hosd_asi_id = :iddd
                    AND dahs.saca_id = :saca_id
                    AND dahs.mod_id = :mod_id
                    AND dahs.uaca_id = :uaca_id
                    ";

                   $comando = $con->createCommand($searcher);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getifasi = $comando->queryOne();
                   

                   if ($getifasi["hose_id"] != Null){

 
                    $getpaal= "select paal.paal_id, paal.hosd_id, paal.paal_cantidad from db_academico.paralelos_alumno AS paal
                    inner join db_academico.horarios_semestre_detalle AS hosd
                    on paal.hosd_id = hosd.hosd_id
                    inner join db_academico.horarios_semestre AS hose
                    on hose.hose_id = hosd.hose_id
                    where hosd.hosd_asi_id = :iddd
                    AND hose.mod_id = :mod_id
                    AND hose.saca_id = :saca_id 
                    AND hose.uaca_id = :uaca_id"
                    ;
                    $comando = $con->createCommand($getpaal);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getpaal = $comando->queryOne();


                     // paal_id, hosd_id, paal_cantidad
                                      
                    $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 
                    \app\models\Utilities::putMessageLogFile('crude:'.$getpaal["paal_cantidad"]);
                    \app\models\Utilities::putMessageLogFile('floor:'.$num_par);

                  // by  $getifasi["hosd_hora"] and $getifasi["hosd_bloque"] ==> daho_id=============>
                  
                    if ($getifasi["hosd_bloque"]== 1){
                     $block='B1'; }Else {   $block='B2'; }

                        $getpaca = "
                        select paca.paca_id from db_academico.semestre_academico as saca
                            inner join db_academico.periodo_academico as paca
                            on saca.saca_id = paca.saca_id
                            inner join db_academico.bloque_academico as baca
                            on paca.baca_id = baca.baca_id
                            where baca.baca_nombre = :baca_id
                            and saca.saca_id = :saca_id
                            ";
                         $comando = $con->createCommand($getpaca);
                   $comando->bindParam(":baca_id", $block, \PDO::PARAM_STR);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $getpaca = $comando->queryOne();
                    $paca_id = $getpaca["paca_id"];
                   

                    if($getpaal["paal_cantidad"] == 0){
                    
                  $num_par = 1;

                  // get daho by
                  //$getifasi["hosd_bloque"] 
                  // $getifasi["hosd_hora"]) 
                  //mod_id
                  // add field daho to insert

                $setmpar= "
                INSERT INTO db_academico.materia_paralelo_periodo (asi_id, mod_id, paca_id, mpp_num_paralelo, mpp_usuario_ingreso, mpp_estado, mpp_estado_logico)
                 VALUES ('" . $iddd . "','" . $mod_id . "','" . $paca_id . "','" . $num_par . "', '1', '1', '1') ";

                    
                $comando = $con->createCommand($setmpar); 
                $setmpar = $comando->execute();


                    } else {
          
                      if(intval($getpaal["paal_cantidad"]/50) == $getpaal["paal_cantidad"] / 50){
                        $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 
                     
                 $setmpar= "
                INSERT INTO db_academico.materia_paralelo_periodo (asi_id, mod_id, paca_id, mpp_num_paralelo, mpp_usuario_ingreso, mpp_estado, mpp_estado_logico)
                 VALUES ('" . $iddd . "','" . $mod_id . "','" . $paca_id . "','" . $num_par . "', '1', '1', '1') ";

                    
                $comando = $con->createCommand($setmpar); 
                $setmpar = $comando->execute();
                        
              
                    } else {

                              $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 
                    }


                    } 


                $getmpar = "
                select mpp_id, asi_id, mod_id, paca_id, daho_id, mpp_num_paralelo
                from db_academico.materia_paralelo_periodo
                where
                asi_id= :asi_id AND
                    mod_id = :mod_id AND
                paca_id = :paca_id AND
                mpp_num_paralelo = :paar"
                  ;

                 $comando = $con->createCommand($getmpar);
                   $comando->bindParam(":asi_id", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":paar", $num_par, \PDO::PARAM_INT);
                   $getmpar = $comando->queryOne();

                    // update paal_cantidad
                 
                 $cantidadal = $getpaal["paal_cantidad"] + 1;  
                $paal_id = $getpaal["paal_id"] ;  
                $updatepaalcantidad = "
                UPDATE db_academico.paralelos_alumno SET paal_cantidad = $cantidadal 
                WHERE paal_id = $paal_id";
                $comando = $con->createCommand($updatepaalcantidad);
                $result = $comando->execute();

                  if ($getifasi["hosd_bloque"] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                         $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                         $mpph1 =  $getmpar["mpp_id"];  // daho_id = Null {{mod, daho_id, update par}} 
                        break;
                        case 2:
                    $asih2 = $subjects[$iter][0];$noasih2 = $subjects[$iter][1];
                     $mpph2 =  $getmpar["mpp_id"] ; // daho_id = Null {{mod, daho_id, update par}}
                         break;      
                        case 3:
                    $asih3 = $subjects[$iter][0];$noasih3 =$subjects[$iter][1];
                     $mpph3 =  $getmpar["mpp_id"] ; // daho_id = Null {{mod, daho_id, update par}}
                         break;                                    
                    }            
                   
                    }  



                  if ($getifasi["hosd_bloque"] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    $asih4 = $subjects[$iter][0]; $noasih4 = $subjects[$iter][1];
                     $mpph4 =  $getmpar["mpp_id"] ; // daho_id = Null {{mod, daho_id, update par}}
                        break;
                        case 2:
                   $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                    $mpph5 =  $getmpar["mpp_id"] ; // daho_id = Null {{mod, daho_id, update par}}
                         break;      
                        case 3:
                    $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                    $mpph6 =  $getmpar["mpp_id"] ; // daho_id = Null {{mod, daho_id, update par}}
                         break;                                    
                    }        }          
                   
                    }  



                    

                     }//endfor       

                     $already = "
                        SELECT pes_id
                        FROM db_academico.planificacion_estudiante 
                        WHERE per_id = ".$rows["per_id"]."
                         and pla_id = ".$rows_pla["pla_id"]." 
                                   ";

                        $comando = $con->createCommand($already);
                        $isin = $comando->queryOne();

                           

                        if ($isin["pes_id"] == Null){

                     $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_semestre, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4,pes_mat_b1_h1_mpp, pes_mat_b1_h2_mpp, pes_mat_b1_h3_mpp, pes_mat_b1_h4_mpp, pes_mat_b2_h1_mpp,
                     pes_mat_b2_h2_mpp,pes_mat_b2_h3_mpp,pes_mat_b2_h4_mpp, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "','" . $student_semester . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asih1 . "', '" . $asih2 . "', '" . $asih3 . "',Null, '" . $asih4 . "', '" . $asih5 . "', '" . $asih6 . "',Null, '" . $noasih1 . "', '" . $noasih2 . "', '" . $noasih3 . "',Null, '" . $noasih4 . "', '" . $noasih5 . "', '" . $noasih6 . "',Null,". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "','" . $mpph1 . "','" . $mpph2 . "','" . $mpph3 . "',Null,'" . $mpph4 . "','" . $mpph5 . "','" . $mpph6 . "',Null, '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute(); 

                      }  


                    } Else {


                               
            //   1   .    3   SCHEDULE     

                     $lasth="
                    SELECT max(dahd.hosd_grupo) as g 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                     ";

                   $comando = $con->createCommand($lasth);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getlastg = $comando->queryOne();

                   
                    $again="SELECT  max(dahd.hosd_bloque) as b  
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_grupo = :grupo
                     ";

                   $comando = $con->createCommand($again);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":grupo", $getlastg["g"], \PDO::PARAM_INT);
                   $getlastb = $comando->queryOne();   


                     $andagain="SELECT max(dahd.hosd_hora) as h 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_grupo = :grupo
                    and dahd.hosd_bloque = :bloque
                     ";

                   $comando = $con->createCommand($andagain);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":grupo", $getlastg["g"], \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $getlastb["b"], \PDO::PARAM_INT);
                   $getlasth = $comando->queryOne();


                    $gactual= $getlastg["g"];   
                    $bactual= $getlastb["b"];    
                    $hactual= $getlasth["h"];   

                     if ($gactual == Null)   
                        { $gactual = 1 ; }      
                    if ($bactual == Null)   
                        { $bactual = 1 ; }      
                    if ($hactual == Null)   
                        { $hactual = 0 ; }       
                     
                    
                                  
                    
                     for ($iter = 1; $iter <= 8; ++$iter){

                    $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 


                     $searcher = "
                    SELECT dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahd.hosd_asi_id = :iddd
                    AND dahs.saca_id = :saca_id
                    AND dahs.mod_id = :mod_id
                    AND dahs.uaca_id = :uaca_id
                    ";

                   $comando = $con->createCommand($searcher);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getifasi = $comando->queryOne();


                       If ($subjects[$iter][2] !="" && $subjects[$iter][2]!=Null){
                            if ($getifasi["hose_id"] == Null){


                    if ($hactual == 4){  
                         if ($bactual == 2){  $gactual++ ;$bactual = 1; $hactual = 1;}
                            Else  { $bactual = 2; $hactual = 1;  }
                     } Else {
                    $hactual++ ;
                     }   
                  

                     $addsch = "
            INSERT INTO db_academico.horarios_semestre_detalle (hose_id, hosd_grupo, hosd_bloque, hosd_hora ,hosd_asi_id,hosd_usuario_ingreso,hosd_estado,hosd_estado_logico)
            VALUES ('" . $hid["hose_id"] . "','" . $gactual . "','" . $bactual . "','" . $hactual . "','" . $subjects[$iter][2] . "',1,'1','1')
                        ";
                  $comando = $con->createCommand($addsch); 
                  $rows_tosch = $comando->execute();



             //GET hosd_id   - ADD WITH  paal_cantidad 1 


               $searchhosd = "
                   SELECT dahd.hosd_id, dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                     WHERE 
                    dahd.hosd_asi_id = :iddd AND
                    dahd.hosd_grupo = :hosd_grupo AND
                    dahd.hosd_bloque = :hosd_bloque AND
                     dahd.hosd_hora = :hosd_hora AND
                    dahs.hose_id = :hose_id
                    ";              

                $comando = $con->createCommand($searchhosd);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_grupo", $gactual, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_bloque", $bactual, \PDO::PARAM_INT);
                   $comando->bindParam(":hosd_hora", $hactual, \PDO::PARAM_INT);
                     $comando->bindParam(":hose_id", $hid["hose_id"], \PDO::PARAM_INT);
                   $rows_tosch = $comando->queryOne();

                     $addschpar = "
            INSERT INTO db_academico.paralelos_alumno (hosd_id, paal_cantidad,paal_usuario_ingreso,paal_estado,paal_estado_logico)
            VALUES ('" . $rows_tosch["hosd_id"] . "',0,1,'1','1')
                        ";
                $comando = $con->createCommand($addschpar); 
                  $rows_tosch = $comando->execute();



                     }  }
                 }  //END ITER

                  //   1   .    4    PES

                 $mpph1 = 0; $mpph2 = 0; $mpph3 = 0; $mpph4 = 0;
                $mpph5 = 0; $mpph6 = 0; $mpph7 = 0; $mpph8 = 0;
                $mpph9 = 0; $mpph10 = 0; $mpph11 = 0; $mpph12 = 0;
                
              for ($iter = 1;$iter <= 8; ++$iter){

                   $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 
                   $horario= $hid["hose_id"];
                 
                   
                 $searcher = "
                    SELECT dahs.hose_id,dahd.hosd_grupo, dahd.hosd_bloque, dahd.hosd_hora, dahd.hosd_asi_id 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahd.hosd_asi_id = :iddd
                    AND dahs.saca_id = :saca_id
                    AND dahs.mod_id = :mod_id
                    AND dahs.uaca_id = :uaca_id
                    ";

                   $comando = $con->createCommand($searcher);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getifasi = $comando->queryOne();
                   

                   if ($getifasi["hose_id"] != Null){

 
                    $getpaal= "select paal.paal_id, paal.hosd_id, paal.paal_cantidad from db_academico.paralelos_alumno AS paal
                    inner join db_academico.horarios_semestre_detalle AS hosd
                    on paal.hosd_id = hosd.hosd_id
                    inner join db_academico.horarios_semestre AS hose
                    on hose.hose_id = hosd.hose_id
                    where hosd.hosd_asi_id = :iddd
                    AND hose.mod_id = :mod_id
                    AND hose.saca_id = :saca_id 
                    AND hose.uaca_id = :uaca_id"
                    ;
                    $comando = $con->createCommand($getpaal);
                   $comando->bindParam(":iddd", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $getpaal = $comando->queryOne();


                     // paal_id, hosd_id, paal_cantidad
                                      
                     $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 
                    \app\models\Utilities::putMessageLogFile('crude:'.$getpaal["paal_cantidad"]);
                    \app\models\Utilities::putMessageLogFile('floor:'.$num_par);

                  // by  $getifasi["hosd_hora"] and $getifasi["hosd_bloque"] ==> daho_id=============>
                  
                    if ($getifasi["hosd_bloque"]== 1){
                     $block='B1'; } else {   $block='B2'; }

                        $getpaca = "
                        select paca.paca_id from db_academico.semestre_academico as saca
                            inner join db_academico.periodo_academico as paca
                            on saca.saca_id = paca.saca_id
                            inner join db_academico.bloque_academico as baca
                            on paca.baca_id = baca.baca_id
                            where baca.baca_nombre = :baca_id
                            and saca.saca_id = :saca_id
                            ";
                         $comando = $con->createCommand($getpaca);
                   $comando->bindParam(":baca_id", $block, \PDO::PARAM_STR);
                   $comando->bindParam(":saca_id", $gest, \PDO::PARAM_INT);
                   $getpaca = $comando->queryOne();
                    $paca_id = $getpaca["paca_id"];
                   

                    if($getpaal["paal_cantidad"] == 0){
                    
                  $num_par = 1;

                  // get daho by
                  //$getifasi["hosd_bloque"] 
                  // $getifasi["hosd_hora"]) 
                  //mod_id
                  // add field daho to insert


                  if ($modalidad == 1){
                    
                  switch ($getifasi["hosd_hora"]) {
                        case 1:
                        $daho_id = 18; 
                        break;
                        case 2:
                      $daho_id = 23; 
                         break;      
                        case 3:
                      $daho_id = 28 ; 
                         break;    
                        case 4:
                     $daho_id = 33; 
                         break;                                       
                    }    


                 } Else {
                      
                                 $daho_id = 0 ; 

                       }


                $setmpar= "
                INSERT INTO db_academico.materia_paralelo_periodo (asi_id, mod_id, paca_id,daho_id, mpp_num_paralelo, mpp_usuario_ingreso, mpp_estado, mpp_estado_logico)
                 VALUES ('" . $iddd . "','" . $mod_id . "','" . $paca_id . "','" . $daho_id . "','" . $num_par . "', '1', '1', '1') ";

                    
                $comando = $con->createCommand($setmpar); 
                $setmpar = $comando->execute();


                    } else {
          
                      if(intval($getpaal["paal_cantidad"]/50) == $getpaal["paal_cantidad"] / 50){
                       $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 


                 /*  
                      
                   // $isprime= gmp_prob_prime($num_par); 1, 5, 7, 11, 13, 17, 19

                    if ($modalidad == 1){

                     if (($num_par % 2 ) == 0){              

                 switch ($getifasi["hosd_hora"]) {
                        case 1:
                        $daho_id = 19; 
                        break;
                        case 2:
                      $daho_id = 24; 
                         break;      
                        case 3:
                      $daho_id = 29; 
                         break;    
                        case 4:
                     $daho_id = 34; 
                         break;                                 
                    }            

                 }

                    if (($num_par % 3) == 0){
                

                 switch ($getifasi["hosd_hora"]) {
                         case 1:
                        $daho_id = 20; 
                        break;
                        case 2:
                      $daho_id = 25; 
                         break;      
                        case 3:
                      $daho_id = 30 ; 
                         break;    
                        case 4:
                     $daho_id = 35; 
                         break; 
                                                        
                    }            


                }

                 if (($num_par % 3) != 0 && ($num_par % 2) != 0){
                    switch ($getifasi["hosd_hora"]) {
                         case 1:
                        $daho_id = 18; 
                        break;
                        case 2:
                      $daho_id = 23; 
                         break;      
                        case 3:
                      $daho_id = 28; 
                         break;    
                        case 4:
                      $daho_id = 33; 
                         break;                                           
                    }     

                     }







                       } Else {
                      
                                 $daho_id = 0 ; 

                       } */



        // $isprime= gmp_prob_prime($num_par); 1, 5, 7, 11, 13, 17, 19

                    if ($modalidad == 1){

                     if ((($num_par % 2 ) == 0) && $num_par > 1){

                 switch ($getifasi["hosd_hora"]) {
                        case 1:
                        $daho_id = 19; 
                        break;
                        case 2:
                      $daho_id = 24; 
                         break;      
                        case 3:
                      $daho_id = 29; 
                         break;    
                        case 4:
                     $daho_id = 34; 
                         break;                                 
                    }            

                 }

                    if ((($num_par % 3) == 0) && $num_par > 2){
                

                 switch ($getifasi["hosd_hora"]) {
                         case 1:
                        $daho_id = 20; 
                        break;
                        case 2:
                      $daho_id = 25; 
                         break;      
                        case 3:
                      $daho_id = 30 ; 
                         break;    
                        case 4:
                     $daho_id = 35; 
                         break; 
                                                        
                    }            


                }

                 if (($num_par == 1) or (($num_par % 3) != 0 and ($num_par % 2) != 0) ){
                    switch ($getifasi["hosd_hora"]) {
                         case 1:
                        $daho_id = 18; 
                        break;
                        case 2:
                      $daho_id = 23; 
                         break;      
                        case 3:
                      $daho_id = 28; 
                         break;    
                        case 4:
                      $daho_id = 33; 
                         break;                                           
                    }     

                     }







                       } Else {
                      
                                 $daho_id = 0 ; 

                       }


                     
                 $setmpar= "
                INSERT INTO db_academico.materia_paralelo_periodo (asi_id, mod_id, paca_id,daho_id, mpp_num_paralelo, mpp_usuario_ingreso, mpp_estado, mpp_estado_logico)
                 VALUES ('" . $iddd . "','" . $mod_id . "','" . $paca_id . "','" . $daho_id . "','" . $num_par . "', '1', '1', '1') ";

                    
                $comando = $con->createCommand($setmpar); 
                $setmpar = $comando->execute();
                        
              
                    } else {

                              $num_par =floor(floatval($getpaal["paal_cantidad"]/50+1)); 
                    }


                    } 


                $getmpar = "
                select mpp_id, asi_id, mod_id, paca_id, daho_id, mpp_num_paralelo
                from db_academico.materia_paralelo_periodo
                where
                asi_id= :asi_id AND
                    mod_id = :mod_id AND
                paca_id = :paca_id AND
                mpp_num_paralelo = :paar"
                  ;

                 $comando = $con->createCommand($getmpar);
                   $comando->bindParam(":asi_id", $iddd, \PDO::PARAM_INT);
                   $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":paar", $num_par, \PDO::PARAM_INT);
                   $getmpar = $comando->queryOne();

                    // update paal_cantidad
                
                 $cantidadal = $getpaal["paal_cantidad"] + 1;  
                $paal_id = $getpaal["paal_id"] ;  
                $updatepaalcantidad = "
                UPDATE db_academico.paralelos_alumno SET paal_cantidad = $cantidadal 
                WHERE paal_id = $paal_id";
                $comando = $con->createCommand($updatepaalcantidad);
                $result = $comando->execute();

                // TO FIX CALCULATE STUDENT BY PAR
                // TO ADD TIMESTAMP TO MPP 


                  if ($getifasi["hosd_bloque"] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    if ($asih1==Null){ 
                         $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                         $mpph1 =  $getmpar["mpp_id"];   
                         } Else{        
                   if ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   

                    }                     
                        }
                    break;
                        case 2:
                    if ($asih2==Null){ 
                         $asih2 = $subjects[$iter][0]; $noasih2 = $subjects[$iter][1];
                         $mpph2 =  $getmpar["mpp_id"];   
                         } Else{            

                    if ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   

                    } 
                                            }; 
                         break; 
                        case 3:
                     if ($asih3==Null){ 
                         $asih3 = $subjects[$iter][0]; $noasih3 = $subjects[$iter][1];
                         $mpph3 =  $getmpar["mpp_id"];   
                         } Else{ 

                   if ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   

                    } 


                        }
                         break;   
                        case 4:
                    if ($asih4==Null){ 
                         $asih4 = $subjects[$iter][0]; $noasih4 = $subjects[$iter][1];
                         $mpph4 =  $getmpar["mpp_id"];   
                         } Else{ 

                                  
                     if ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   

                    } 

                        }
                         break;                         
                    }            
                   
                    }  


                

                





                  if ($getifasi["hosd_bloque"] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    if ($asih5==Null){ 
                         $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                         $mpph5 =  $getmpar["mpp_id"];   
                         } Else{ 
                     if ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   

                    }
                        }; 
                         break; 
                        case 2:
                    if ($asih6==Null){ 
                         $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                         $mpph6 =  $getmpar["mpp_id"];   
                         } Else{ 
                     if ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   

                    }
                        }; 
                         break; 
                        case 3:
                     if ($asih7==Null){ 
                         $asih7 = $subjects[$iter][0]; $noasih7 = $subjects[$iter][1];
                         $mpph7 =  $getmpar["mpp_id"];   
                         } Else{ 
                     if ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   

                    }
                        }; 
                         break; 
                       case 4:
                    if ($asih8==Null){ 
                         $asih8 = $subjects[$iter][0]; $noasih8 = $subjects[$iter][1];
                         $mpph8 =  $getmpar["mpp_id"];   
                         } Else{ 
                     if ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"];   
                 }
                    Else {
                 $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   

                    }
                        }; 
                         break;                          
                    }        }          
                   
                    }  



                    

                     }//endfor       



                            $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_semestre, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4, pes_mat_b1_h1_mpp, pes_mat_b1_h2_mpp, pes_mat_b1_h3_mpp, pes_mat_b1_h4_mpp, pes_mat_b2_h1_mpp,
                     pes_mat_b2_h2_mpp,pes_mat_b2_h3_mpp,pes_mat_b2_h4_mpp, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "','" . $student_semester . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asih1 . "', '" . $asih2 . "', '" . $asih3 . "', '" . $asih7 . "', '" . $asih4 . "', '" . $asih5 . "', '" . $asih6 . "', '" . $asih8 . "', '" . $noasih1 . "', '" . $noasih2 . "', '" . $noasih3 . "', '" . $noasih7 . "', '" . $noasih4 . "', '" . $noasih5 . "', '" . $noasih6 . "', '" . $noasih8 . "',". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "','" . $mpph1 . "','" . $mpph2 . "','" . $mpph3 . "','" . $mpph7 . "','" . $mpph4 . "','" . $mpph5 . "','" . $mpph6 . "','" . $mpph8 . "', '" . $estado . "', '" . $estado ."')"; 

                
                        $already = "
                        SELECT pes_id
                        FROM db_academico.planificacion_estudiante 
                        WHERE per_id = ".$rows["per_id"]."
                         and pla_id = ".$rows_pla["pla_id"]." 
                                   ";

                        $comando = $con->createCommand($already);
                        $isin = $comando->queryOne();

                           

                        if ($isin["pes_id"] == Null){



                             $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_semestre, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod,pes_mat_b1_h5_cod,pes_mat_b1_h6_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod,pes_mat_b2_h5_cod,pes_mat_b2_h6_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre,pes_mat_b1_h5_nombre,pes_mat_b1_h6_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre,pes_mat_b2_h5_nombre,pes_mat_b2_h6_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,pes_mod_b1_h5,pes_mod_b1_h6,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4,pes_mod_b2_h5,pes_mod_b2_h6, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,pes_jor_b1_h5,pes_jor_b1_h6,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4,pes_jor_b2_h5, pes_jor_b2_h6, pes_mat_b1_h1_mpp, pes_mat_b1_h2_mpp, pes_mat_b1_h3_mpp, pes_mat_b1_h4_mpp,pes_mat_b1_h5_mpp, pes_mat_b1_h6_mpp, pes_mat_b2_h1_mpp,
                     pes_mat_b2_h2_mpp,pes_mat_b2_h3_mpp,pes_mat_b2_h4_mpp,pes_mat_b2_h5_mpp,pes_mat_b2_h6_mpp, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "','" . $student_semester . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asih1 . "', '" . $asih2 . "', '" . $asih3 . "', '" . $asih4 . "','" . $asih9 . "','" . $asih10 . "', '" . $asih5 . "', '" . $asih6 . "', '" . $asih7 . "', '" . $asih8 . "','" . $asih11 . "','" . $asih12 . "', '" . $noasih1 . "', '" . $noasih2 . "', '" . $noasih3 . "', '" . $noasih4 . "','" . $noasih9 . "','" . $noasih10 . "', '" . $noasih5 . "', '" . $noasih6 . "', '" . $noasih7 . "', '" . $noasih8 . "','" . $noasih11 . "','" . $noasih12 . "',". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "','" . $jornadas . "','" . $jornadas . "','" . $jornadas . "','" . $jornadas . "','" . $mpph1 . "','" . $mpph2 . "','" . $mpph3 . "','" . $mpph4 . "','" . $mpph9 . "','" . $mpph10 . "','" . $mpph5 . "','" . $mpph6 . "','" . $mpph7 . "','" . $mpph8 . "','" . $mpph11 . "','" . $mpph12 . "', '" . $estado . "', '" . $estado ."')"; 


                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute(); 

                     }
                    

                     }
                     
                      }
                      
                  
                  }
         
         $sql = "select pla_id from db_academico.planificacion where mod_id = " . $mod_id . " and pla_estado = 1 and pla_estado_logico = 1 and saca_id = :periodo ";
                  $comando = $con->createCommand($sql);
                   $comando->bindParam(":periodo", $estacion, \PDO::PARAM_STR);
                   $rows_plav = $comando->queryOne();
              
              if ($rows_plav) { $ok=1; 
               \app\models\Utilities::putMessageLogFile("Returning ".$ok); 
               
              return true; 
               }    
    
}   

public function consultaParalelosxMateria($asi_id,$saca_id,$mod_id) {
    $con = \Yii::$app->db_academico;
    $estado = 1;
    $sql = "SELECT mpp.mpp_id as id, concat('Paralelo ',mpp.mpp_num_paralelo) as nombre 
            from db_academico.materia_paralelo_periodo mpp 
            inner join db_academico.semestre_academico saca on saca.saca_id = $saca_id
            inner join db_academico.periodo_academico paca on mpp.paca_id = paca.paca_id 
            and paca.saca_id = saca.saca_id
            inner join db_academico.planificacion pla on pla.saca_id = saca.saca_id
            and pla.mod_id = mpp.mod_id
            where mpp.asi_id = $asi_id and mpp.mod_id = $mod_id;";
    $comando = $con->createCommand($sql);
    \app\models\Utilities::putMessageLogFile('Consultar Paralelos: '.$comando->getRawSql());
    $resultData = $comando->queryAll();
    //\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: '.$comando->getRawSql());
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
    \app\models\Utilities::putMessageLogFile('Consultar Paralelos N: '.implode(",", $resultData));
    return $dataProvider;
}
public function consultaHorarioxParalelo($mpp_id) {
    $con = \Yii::$app->db_academico;
    $estado = 1;
    $sql = "SELECT ifnull(mpp.mpp_id,'0') as id,
            ifnull(daho.daho_descripcion,'Seleccionar') as nombre , mpp.*
            from db_academico.materia_paralelo_periodo mpp 
            inner join db_academico.distributivo_academico_horario daho on daho.daho_id = mpp.daho_id
            where mpp.mpp_id = $mpp_id;";
    $comando = $con->createCommand($sql);
    \app\models\Utilities::putMessageLogFile('Consultar Paralelos: '.$comando->getRawSql());
    $resultData = $comando->queryAll();
    //\app\modules\academico\controllers\RegistroController::putMessageLogFileCartera('consultarModalidad: '.$comando->getRawSql());
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
    \app\models\Utilities::putMessageLogFile('Consultar Paralelos N: '.implode(",", $resultData));
    return $dataProvider;
}

}
