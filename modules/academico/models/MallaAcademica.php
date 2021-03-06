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
     * Function consultar mallas acad??micas
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
     * Function consultar detalle de mallas acad??micas
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
     * Function consultar cabecera de mallas acad??micas
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

    public function consultarasignaturaxmallaaut($per_id, $op=null) {
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
        (select mad.made_codigo_asignatura from " . $con->dbname . ".planificacion as pla, " . $con->dbname . ".planificacion_estudiante ple,
        " . $con->dbname . ".malla_academica_detalle mad
        where mad.made_codigo_asignatura in (
        ple.pes_mat_b1_h1_cod,ple.pes_mat_b1_h2_cod,ple.pes_mat_b1_h3_cod,ple.pes_mat_b1_h4_cod,ple.pes_mat_b1_h5_cod,ple.pes_mat_b1_h6_cod,
        ple.pes_mat_b2_h1_cod,ple.pes_mat_b2_h2_cod,ple.pes_mat_b2_h3_cod,ple.pes_mat_b2_h4_cod,ple.pes_mat_b2_h5_cod,ple.pes_mat_b2_h6_cod)
        and pla.pla_id = ple.pla_id and ple.per_id = $per_id and pla_estado = 1 and pla_estado_logico =1 and pes_estado = 1 and pes_estado_logico = 1)";
        $comando = $con->createCommand($sql2);
        if($per_id == null){
            $resultData = [];
        }else{
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            //$comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();

            if ($op==1){
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
                    return $dataProvider;
            }
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


public function traerActivas($periodo, $modalidad) {
        $con = \Yii::$app->db_academico;
        $mactivas = "
            select mpmo.mpmo_id
 from db_academico.estudiante e
 inner join db_academico.estudiante_carrera_programa c on c.est_id = e.est_id
  inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id = c.meun_id
  inner join db_academico.malla_unidad_modalidad mumo on mumo.meun_id = meu.meun_id
   inner join db_academico.malla_academica maca on maca.maca_id = mumo.maca_id
   inner join db_academico.unidad_academica u on u.uaca_id = meu.uaca_id
   inner join db_academico.estudio_academico ea on ea.eaca_id = meu.eaca_id
   inner join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = meu.eaca_id and mpmo.mod_id = meu.mod_id 
   inner join db_asgard.persona per on per.per_id = e.per_id
   left join db_academico.malla_academico_estudiante malle on per.per_id = malle.per_id
     where  malle.maca_id = maca.maca_id  AND
         meu.mod_id = :modalidad and meu.uaca_id = 1 and mpmo.saca_id = :periodo and mpmo.mpmo_activo = 'A'
          and mpmo.mpmo_procesado is Null
    AND  mpmo.mpmo_estado = 1 AND mpmo.mpmo_estado_logico = 1
    AND  e.est_estado = 1 AND e.est_estado_logico = 1
    AND  c.ecpr_estado = 1 AND c.ecpr_estado_logico = 1
    AND  meu.meun_estado = 1 AND meu.meun_estado_logico = 1
    AND  mumo.mumo_estado = 1 AND mumo.mumo_estado_logico = 1
    AND  maca.maca_estado = 1 AND maca.maca_estado_logico = 1
    AND  u.uaca_estado = 1 AND u.uaca_estado_logico = 1
     AND  ea.eaca_estado = 1
    AND  per.per_estado = 1 AND per.per_estado_logico = 1
    AND  malle.maes_estado = 1 AND malle.maes_estado_logico = 1
     AND
((e.per_id in (select b.per_id from db_academico.planificacion_estudiante b where
b.pla_id= ( select max(dap.pla_id) from db_academico.planificacion dap
 where dap.mod_id = :modalidad ))) or
((e.per_id in (
select distinct a.per_id from db_asgard.persona as a
inner join db_academico.estudiante bas on a.per_id = bas.per_id
where DATEDIFF(NOW(),bas.est_fecha_creacion) <=1800 or
DATEDIFF(NOW(),a.per_fecha_creacion) <=1800 )))
 )
order by maca.maca_id DESC , ea.eaca_codigo, e.est_fecha_creacion ASC;
                ";

        $comando = $con->createCommand($mactivas);
        $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
        $comando->bindParam(":periodo", $periodo, \PDO::PARAM_INT);
        $resultActivas = $comando->queryAll();

        return $resultActivas;

    }
    
         function marcarAsignaturas($mpmo_id,$procesado) {
    $con = \Yii::$app->db_academico;
    $marcasi= "UPDATE db_academico.materias_periodo_modalidad SET mpmo_procesado = $procesado 
    WHERE mpmo_id = $mpmo_id";
    $comando = $con->createCommand($marcasi);
    $result = $comando->execute();  
     return true;


}

         function cargarAsignaturas($rows,$modalidad,$periodo) {
    $con = \Yii::$app->db_academico;
     $activo="A";

     $sql = "select distinct  
a.asi_id, a.made_credito, c.uaca_id,c.mod_id, c.eaca_id, a.maca_id,a.made_semestre
from db_academico.malla_academica_detalle a
inner join db_academico.malla_unidad_modalidad b on b.maca_id = a.maca_id 
inner join db_academico.modalidad_estudio_unidad c on c.meun_id = b.meun_id
inner join db_academico.asignatura d on d.asi_id = a.asi_id
inner join db_academico.malla_academico_estudiante maes on maes.per_id = " . $rows["per_id"] . " 
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
                            and maes.maes_estado = 1
                            and maes.maes_estado_logico = 1
                     ORDER BY a.made_semestre,a.asi_id ASC
                        ";
   $comando = $con->createCommand($sql);
   $rows_asi = $comando->queryAll();




  

    if (count($rows_asi) > 0) {    $cn= 0;
        
     for ($im = 0; $im < count($rows_asi); $im++) {  

         $asignatura = $rows_asi[$im]["asi_id"]; 
         $malla      = $rows["maca_id"];
         $persona      = $rows["per_id"];
         
      $sqlpr = "
        select a.made_id, a.maca_id, a.asi_id,b.asi_requisito from db_academico.malla_academica_detalle a
        inner join db_academico.malla_academica_requisito b on a.made_id = b.made_id 
        where a.maca_id= :maca_id and a.asi_id = :asi_id                
                ";
                     $comando = $con->createCommand($sqlpr);
                     $comando->bindParam(":maca_id", $malla, \PDO::PARAM_INT);
                     $comando->bindParam(":asi_id", $asignatura, \PDO::PARAM_INT);
                     $prereq = $comando->queryAll();

        $valider = -1;  $refer = -1;

         if (count($prereq) > 0) {   
                    
                     for ($kl = 0; $kl < count($prereq); $kl++) {  

                      $requisitos = $prereq[$kl]["asi_requisito"];

                        $sqlloop = "
                 select  a.asi_id, c.enac_id, a.maes_id, a.per_id
 from db_academico.malla_academico_estudiante a
 left join db_academico.promedio_malla_academico b on a.maes_id = b.maes_id
   left join db_academico.estado_nota_academico c on c.enac_id = b.enac_id   
   inner join db_academico.asignatura d on a.asi_id = d.asi_id
   where a.per_id = :per_id
   and a.asi_id = :requisitos and ( c.enac_id = 1)
                       and a.maes_estado = 1
                    and a.maes_estado_logico = 1
                     and b.pmac_estado = 1
                     and b.pmac_estado_logico = 1
                     and c.enac_estado = 1
                     and c.enac_estado_logico = 1
                ";

                $comando = $con->createCommand($sqlloop);
                $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
                $comando->bindParam(":requisitos", $requisitos, \PDO::PARAM_INT);
                $statuspre = $comando->queryOne();

            if ($statuspre["enac_id"]==1 /*or $statuspre["enac_id"]==4*/){ 

                          $valider++;
                      }   $refer++;
        } 

     if ($valider == $refer ){ $sstatuspre = True;   } else {  $sstatuspre = False;  }

    } else {  $sstatuspre = True;  }

     $sqlch = "
                 select  a.asi_id, c.enac_id, a.maes_id, a.per_id
 from db_academico.malla_academico_estudiante a
 left join db_academico.promedio_malla_academico b on a.maes_id = b.maes_id
   left join db_academico.estado_nota_academico c on c.enac_id = b.enac_id   
   inner join db_academico.asignatura d on a.asi_id = d.asi_id
   where a.per_id = :per_id
   and a.asi_id = :asignatura
                       and a.maes_estado = 1
                    and a.maes_estado_logico = 1
                    and b.pmac_estado = 1
                    and b.pmac_estado_logico = 1
                    and c.enac_estado = 1
                    and c.enac_estado_logico = 1
                     

                ";
                     
                     $comando = $con->createCommand($sqlch);
                     $comando->bindParam(":per_id", $persona, \PDO::PARAM_INT);
                     $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
                     $statusasi = $comando->queryOne();

            if ($statusasi["enac_id"] == 2 OR $statusasi["enac_id"] == 3) {

                $sstatusasi= True;
            } else {  $sstatusasi= False;  }
         
       if ($sstatuspre == True AND $sstatusasi == True) {  $cn++;

           if ($cn < 7) {
        
         $asiid = $rows_asi[$im]["asi_id"];
         $modid = $rows_asi[$im]["mod_id"];
         $eacaid = $rows_asi[$im]["eaca_id"];
         $macaid = $rows_asi[$im]["maca_id"];

         $sacaid = $periodo ; $estado = 1;

         $sql = "select mpmo_id, mpmo_nestudiantes from db_academico.materias_periodo_modalidad
          where asi_id =:asiid  and eaca_id =:eacaid  and saca_id =:sacaid and mod_id = :modid;                 
                        ";

         $comando = $con->createCommand($sql);
         $comando->bindParam(":asiid", $asiid, \PDO::PARAM_INT);    
         $comando->bindParam(":eacaid", $eacaid, \PDO::PARAM_INT);
         $comando->bindParam(":sacaid", $periodo, \PDO::PARAM_INT);
         $comando->bindParam(":modid", $modid, \PDO::PARAM_INT);
               $ismat_in = $comando->queryOne();

          if ($ismat_in['mpmo_id'] == Null) {  


           $sqladd = "
             INSERT INTO db_academico.materias_periodo_modalidad
             (saca_id, mod_id, asi_id, mpmo_nestudiantes, eaca_id, mpmo_usuario_ingreso, mpmo_estado, mpmo_estado_logico)
            VALUES ('" . $sacaid . "','" . $modid . "','" . $asiid . "',1,'" . $eacaid . "',1, '" . $estado . "', '" . $estado . "')"
            ;
            
               $comando = $con->createCommand($sqladd); 
                     $putasig = $comando->execute();

                                                 } else  {   
                $allst=  $ismat_in['mpmo_nestudiantes'] + 1 ;
                $mpmo_id=  $ismat_in['mpmo_id'];
                $updt= "UPDATE db_academico.materias_periodo_modalidad SET mpmo_nestudiantes = $allst 
                WHERE mpmo_id = $mpmo_id";
                $comando = $con->createCommand($updt);
                $result = $comando->execute();  
                                                  }

           }}}
        }
              return true;
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
a.made_codigo_asignatura, a.made_credito, c.uaca_id,
c.mod_id, c.eaca_id, d.asi_nombre,  mpmo.mpmo_bloque
from db_academico.malla_academica_detalle a
inner join db_academico.malla_unidad_modalidad b on b.maca_id = a.maca_id 
inner join db_academico.modalidad_estudio_unidad c on c.meun_id = b.meun_id
inner join db_academico.asignatura d on d.asi_id = a.asi_id
inner join db_academico.materias_periodo_modalidad mpmo on mpmo.eaca_id = c.eaca_id and mpmo.mod_id = c.mod_id and mpmo.saca_id = :periodo
inner join db_academico.malla_academico_estudiante maes on maes.per_id = " . $rows["per_id"] . " 
                       where c.eaca_id =  " . $rows["eaca_id"] . "   
                      and   c.mod_id =  " . $modalidad . "   
                      and a.maca_id =  " . $rows["maca_id"] . "  
                      and   a.asi_id = mpmo.asi_id 
                      and c.uaca_id = 1
                      and mpmo_activo = 'A'
                      and mpmo.mpmo_bloque is not null
                            and a.made_estado = 1
                            and a.made_estado_logico = 1
                            and b.mumo_estado = 1
                            and b.mumo_estado_logico = 1
                            and c.meun_estado = 1
                            and c.meun_estado_logico = 1
                            and d.asi_estado = 1
                            and d.asi_estado_logico = 1
                            and mpmo.mpmo_estado = 1
                            and mpmo.mpmo_estado_logico = 1
                     ORDER BY a.made_semestre,a.asi_id  ASC
                        ";
  

   $comando = $con->createCommand($sql);
         // $comando->bindParam(":activo", $activo, \PDO::PARAM_STR);
          $comando->bindParam(":periodo", $gest, \PDO::PARAM_INT);
         // $comando->bindParam(":semester", $student_semester, \PDO::PARAM_INT);
        //  $comando->bindParam(":lastsemester", $last_semester, \PDO::PARAM_INT);
               $rows_in = $comando->queryAll();

             $per_id =   $rows["per_id"];
           //  $est_id =   $rows["est_id"];
           //  $matricula =   $rows["matricula"];
            // $creacion =   $rows["est_fecha_creacion"];
           //  $categoria =   $rows["est_categoria"];
             $uaca_id =   1;
             $mod_id =   $modalidad;
             $eaca_id =   $rows["eaca_id"];
           //  $uaca_nombre =   $rows["uaca_nombre"];
            // $teac_id =   $rows["teac_id"];
            // $eaca_nombre =   $rows["eaca_nombre"];
            // $eaca_codigo =   $rows["eaca_codigo"];
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

           //  \app\models\Utilities::putMessageLogFile('hose id:'.$hid["hose_id"]);

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
                    and b.pmac_estado = 1
                    and b.pmac_estado_logico = 1
                    and c.enac_estado = 1
                    and c.enac_estado_logico = 1
                     

                ";
                     
                     $comando = $con->createCommand($sql);
                     $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
                     $comando->bindParam(":asignatura", $asignatura, \PDO::PARAM_INT);
                     $statusasi = $comando->queryOne();
                    
                    

                     // GET REQUIREMENTS   
                      $asignatura = $rows_in[$i]["asi_id"]; // already exist
                      $malla      = $rows["maca_id"];

                  $sql = "
  select a.made_id, a.maca_id, a.asi_id,b.asi_requisito from db_academico.malla_academica_detalle a
inner join db_academico.malla_academica_requisito b on a.made_id = b.made_id 
where a.maca_id= :maca_id and a.asi_id = :asi_id                
                ";
                     $comando = $con->createCommand($sql);
                     $comando->bindParam(":maca_id", $malla, \PDO::PARAM_INT);
                     $comando->bindParam(":asi_id", $asignatura, \PDO::PARAM_INT);
                     $prereq = $comando->queryAll();
                    
                    $valider = -1; $refer = -1;
                      if (count($prereq) > 0) {   
                    
                     for ($k = 0; $k < count($prereq); $k++) {    
          
                          $requisitos = $prereq[$k]["asi_requisito"];
                     
                     $sqlloop = "
                 select  a.asi_id, c.enac_id, a.maes_id, a.per_id
 from db_academico.malla_academico_estudiante a
 left join db_academico.promedio_malla_academico b on a.maes_id = b.maes_id
   left join db_academico.estado_nota_academico c on c.enac_id = b.enac_id   
   inner join db_academico.asignatura d on a.asi_id = d.asi_id
   where a.per_id = :per_id
   and a.asi_id = :requisitos and ( c.enac_id = 1)
                    and a.maes_estado = 1
                    and a.maes_estado_logico = 1
                    and b.pmac_estado = 1
                    and b.pmac_estado_logico = 1
                    and c.enac_estado = 1
                    and c.enac_estado_logico = 1
                ";
                
                     $comando = $con->createCommand($sqlloop);
                     $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
                     $comando->bindParam(":requisitos", $requisitos, \PDO::PARAM_INT);
                     $statuspre = $comando->queryOne();

                         if ($statuspre["enac_id"]==1 /*or $statuspre["enac_id"]==4*/){ 

                          $valider++;
                      }   $refer++;
                      
          

                      }
                     
                if ($valider == $refer ){ $sstatuspre = True;   } else {  $sstatuspre = False;  }

                      }  else {  $sstatuspre = True;  }

                       // line 652 - 676  -- GET STATUS REQUIREMENTS


                   
                    
         if ($statusasi["enac_id"]==3 or $statusasi["enac_id"]==2 /*or $statusasi["enac_id"]== Null*/ ){ 
                      
                       
                      
                        if ($sstatuspre == True){                     
                         

                     if ($subjects[1][0] == Null)  {                   
                   $subjects[1][0] = $rows_in[$i]["made_codigo_asignatura"];
                   $subjects[1][1] = $rows_in[$i]["asi_nombre"];    
                   $subjects[1][2] = $rows_in[$i]["asi_id"]; 
                   $subjects[1][3] = $rows_in[$i]["made_credito"];   
                   $subjects[1][4] = $rows_in[$i]["mpmo_bloque"]; }  
                elseif ($subjects[2][0]== Null)  { 
                    $subjects[2][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[2][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[2][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[2][3] = $rows_in[$i]["made_credito"];   
                    $subjects[2][4] = $rows_in[$i]["mpmo_bloque"]; }      
                 elseif ($subjects[3][0]== Null)  { 
                    $subjects[3][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[3][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[3][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[3][3] = $rows_in[$i]["made_credito"]; 
                    $subjects[3][4] = $rows_in[$i]["mpmo_bloque"];  }  
                 elseif ($subjects[4][0]== Null)  { 
                    $subjects[4][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[4][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[4][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[4][3] = $rows_in[$i]["made_credito"];
                    $subjects[4][4] = $rows_in[$i]["mpmo_bloque"];   } 
                 elseif ($subjects[5][0]== Null)  { 
                    $subjects[5][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[5][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[5][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[5][3] = $rows_in[$i]["made_credito"];  
                    $subjects[5][4] = $rows_in[$i]["mpmo_bloque"]; }  
                 elseif ($subjects[6][0]== Null)  { 
                    $subjects[6][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[6][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[6][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[6][3] = $rows_in[$i]["made_credito"]; 
                    $subjects[6][4] = $rows_in[$i]["mpmo_bloque"];  } 
                /* elseif ($subjects[7][0]== Null)  { 
                    $subjects[7][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[7][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[7][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[7][3] = $rows_in[$i]["made_credito"];
                    $subjects[7][4] = $rows_in[$i]["mpmo_bloque"];   } 
                 elseif ($subjects[8][0]== Null)  { 
                    $subjects[8][0] = $rows_in[$i]["made_codigo_asignatura"];
                    $subjects[8][1]  = $rows_in[$i]["asi_nombre"];   
                    $subjects[8][2] = $rows_in[$i]["asi_id"]; 
                    $subjects[8][3] = $rows_in[$i]["made_credito"];
                    $subjects[8][4] = $rows_in[$i]["mpmo_bloque"];   }  */

                               
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
                                 
                
                $sql = "INSERT INTO db_academico.planificacion (mod_id, per_id, pla_fecha_inicio, pla_fecha_fin, pla_periodo_academico,pla_path, pla_estado, pla_estado_logico,saca_id)
                        VALUES ('" . $modalidad . "', 1, '" . $fecha_inicio . "', '" . $fecha_fin . "', '" . $semestre . "',1, '" . $estado . "', '" . $estado . "','" . $estacion . "');";
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
                         
                     for ($iter = 1; $iter <= 6; ++$iter){

                    $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2];
                   $cred = $subjects[$iter][3]; 
                   $blck = $subjects[$iter][4]; 


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

//===================================================================================>>

            if ($blck != Null) {    

                         $lasthb="
                    SELECT max(dahd.hosd_grupo) as g 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_bloque = :bloque
                     ";

                   $comando = $con->createCommand($lasthb);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $blck, \PDO::PARAM_INT);
                   $getlastgb = $comando->queryOne();

                    $gettg = $getlastgb["g"];
                    $gettb = $blck;
             }

//===================================================================================>>

                   else {   

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


                    $gettg = $getlastg["g"];
                    $gettb = $getlastb["b"];

 }


                    

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
                   $comando->bindParam(":grupo", $gettg, \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $gettb, \PDO::PARAM_INT);
                   $getlasth = $comando->queryOne();
 
                     $gactual=  $gettg;
                     $gmax = $getlastg["g"];
                     $bactual=  $gettb;   
                     $hactual= $getlasth["h"];   


                     if ($gactual == Null)   
                        { $gactual = 1 ; }      
                    if ($bactual == Null)   
                        { $bactual = 1 ; }      
                    if ($hactual == Null)   
                        { $hactual = 0 ; }  



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
                         if ($bactual == 2){ $gactual = $gmax + 1; $bactual = 2; $hactual = 1;}
                         if ($bactual == 1){ $gactual = $gmax + 1; $bactual = 1; $hactual = 1;}                     } Else {
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
                 $asih1 = Null; $asih2 = Null; $asih3 = Null; $asih4 = Null;
                 $asih5 = Null; $asih6 = Null; $asih7 = Null; $asih8 = Null;
                 $asih9 = Null; $asih10 = Null; $asih11 = Null; $asih12 = Null;
                
              for ($iter = 1;$iter <= 6; ++$iter){

                   $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 
                   $blck = $subjects[$iter][4]; 
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
                  //  \app\models\Utilities::putMessageLogFile('crude:'.$getpaal["paal_cantidad"]);
                  //  \app\models\Utilities::putMessageLogFile('floor:'.$num_par);

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


                   if ($subjects[$iter][4] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                        if ($asih1==Null){ 
                         $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                         $mpph1 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break;             

                        case 2:
                      if ($asih2==Null){ 
                         $asih2 = $subjects[$iter][0]; $noasih2 = $subjects[$iter][1];
                         $mpph2 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break;    


                        case 3:
                      if ($asih3==Null){ 
                         $asih3 = $subjects[$iter][0]; $noasih3 = $subjects[$iter][1];
                         $mpph3 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break;                                     
                    }            
                   
                    }  



                   if ($subjects[$iter][4] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                   if ($asih4==Null){ 
                         $asih4 = $subjects[$iter][0]; $noasih4 = $subjects[$iter][1];
                         $mpph4 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break; 
                        case 2:
                    if ($asih5==Null){ 
                         $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                         $mpph5 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break;      
                        case 3:
                     if ($asih6==Null){ 
                         $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                         $mpph6 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }          
                    Else{   
                     $flagger = 2;
                        }
                    break;                                    
                    }        }   

                    // update paal_cantidad

                   if ($flagger ==1)   {
                 
                 $cantidadal = $getpaal["paal_cantidad"] + 1;  
                $paal_id = $getpaal["paal_id"] ;  
                $updatepaalcantidad = "
                UPDATE db_academico.paralelos_alumno SET paal_cantidad = $cantidadal 
                WHERE paal_id = $paal_id";
                $comando = $con->createCommand($updatepaalcantidad);
                $result = $comando->execute();  

            
                  $pla_api = $rows_pla["pla_id"];
                  $asi_api = $subjects[$iter][2];
                  $mod_api = $rows["mod_id"];
                  $maca_api = $rows["maca_id"];
                  $uaca_api = $rows["uaca_id"];
                  $bloq_api = $getifasi["hosd_bloque"];
                  $mpp_api =  $getmpar["mpp_id"]; 
                  $mpp_num =  $getmpar["mpp_num_paralelo"]; 
/*
                $searchparsiiga = "
                    SELECT  pasi_id,pasi_cantidad  from db_academico.paralelos_siiga
                    WHERE pla_id = :pla_id 
                    AND asi_id = :asi_id
                    AND mod_id = :mod_id
                    AND maca_id = :maca_id
                    AND uaca_id = :uaca_id
                    AND bloq_id = :bloq_id
                    AND mpp_id = :mpp_id
                    ";

                   $comando = $con->createCommand($searchparsiiga);
                   $comando->bindParam(":pla_id",   $pla_api, \PDO::PARAM_INT);
                   $comando->bindParam(":asi_id",   $asi_api, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id",   $mod_api, \PDO::PARAM_INT);
                   $comando->bindParam(":maca_id", $maca_api, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_api, \PDO::PARAM_INT);
                   $comando->bindParam(":bloq_id", $bloq_api, \PDO::PARAM_INT);
                   $comando->bindParam(":mpp_id",   $mpp_api, \PDO::PARAM_INT);
                   $existparsiiga = $comando->queryOne();
                

                  if ($existparsiiga["pasi_id"] == Null) { 

                $createpartemp =
                "
                 INSERT INTO db_academico.paralelos_siiga
    (pla_id,asi_id,mod_id,maca_id,uaca_id,bloq_id,mpp_id,siiga_paralelo,pasi_cantidad,pasi_usuario_ingreso,pasi_estado,pasi_fecha_creacion,pasi_estado_logico)

                VALUES 
    ('" . $pla_api . "','" . $asi_api . "','" . $mod_api . "','" . $maca_api . "','" . $uaca_api . "','" . $bloq_api . "','" . $mpp_api . "','" . $mpp_num . "','1','1', '1',
 '2021-08-30 17:10:53', '1')
                ";

                $comando = $con->createCommand($createpartemp);
                     $fillpars = $comando->execute(); 


                  } Else {                              

                $cantpasi = $existparsiiga["pasi_cantidad"] + 1;  
                $pasi_id = $existparsiiga["pasi_id"] ;  
                $updatepasicant = "
                UPDATE db_academico.paralelos_siiga SET pasi_cantidad = $cantpasi 
                WHERE pasi_id = $pasi_id";
                $comando = $con->createCommand($updatepasicant);
                $result = $comando->execute();  
                   

                                                      } */

                 }            
                   
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
                                        
                     for ($iter = 1; $iter <= 6; ++$iter){

                    $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 
                   $blck = $subjects[$iter][4]; 

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

//===================================================================================>>

            if ($blck != Null) {    

                         $lasthb="
                    SELECT max(dahd.hosd_grupo) as g 
                    FROM db_academico.horarios_semestre dahs
                    INNER JOIN db_academico.horarios_semestre_detalle dahd
                    ON dahs.hose_id = dahd.hose_id
                    WHERE dahs.saca_id = :semestre 
                    and dahs.mod_id = :mod_id
                    and dahs.uaca_id = :uaca_id
                    and dahd.hosd_bloque = :bloque
                     ";

                   $comando = $con->createCommand($lasthb);
                   $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $blck, \PDO::PARAM_INT);
                   $getlastgb = $comando->queryOne();

                    $gettg = $getlastgb["g"];
                    $gettb = $blck;
             }

//===================================================================================>>

                   else {   

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


                    $gettg = $getlastg["g"];
                    $gettb = $getlastb["b"];

 }


                    

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
                   $comando->bindParam(":grupo", $gettg, \PDO::PARAM_INT);
                   $comando->bindParam(":bloque", $gettb, \PDO::PARAM_INT);
                   $getlasth = $comando->queryOne();
 
                     $gactual=  $gettg;
                     $gmax = $getlastg["g"];
                     $bactual=  $gettb;   
                     $hactual= $getlasth["h"];   


                     if ($gactual == Null)   
                        { $gactual = 1 ; }      
                    if ($bactual == Null)   
                        { $bactual = 1 ; }      
                    if ($hactual == Null)   
                        { $hactual = 0 ; }  




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


                     if ($hactual == 3){  
                         if ($bactual == 2){ $gactual = $gmax + 1; $bactual = 2; $hactual = 1;}
                         if ($bactual == 1){ $gactual = $gmax + 1; $bactual = 1; $hactual = 1;}                     } Else {
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
                $asih1 = Null; $asih2 = Null; $asih3 = Null; $asih4 = Null;
                $asih5 = Null; $asih6 = Null; $asih7 = Null; $asih8 = Null;
                $asih9 = Null; $asih10 = Null; $asih11 = Null; $asih12 = Null;
                
              for ($iter = 1;$iter <= 6; ++$iter){

                   $codd = $subjects[$iter][0]; 
                   $nomm = $subjects[$iter][1]; 
                   $iddd = $subjects[$iter][2]; 
                   $cred = $subjects[$iter][3]; 
                   $blck = $subjects[$iter][4]; 
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
                //    \app\models\Utilities::putMessageLogFile('crude:'.$getpaal["paal_cantidad"]);
               //     \app\models\Utilities::putMessageLogFile('floor:'.$num_par);

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

          

                // TO FIX CALCULATE STUDENT BY PAR
                // TO ADD TIMESTAMP TO MPP 


                   if ($subjects[$iter][4] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    if ($asih1==Null){ 
                    $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                    $mpph1 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                         }        
                    Elseif ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih10==Null){ 
                    $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;


                        case 2:
                    if ($asih2==Null){ 
                    $asih2 = $subjects[$iter][0]; $noasih2 = $subjects[$iter][1];
                    $mpph2 =  $getmpar["mpp_id"]; 
                    $flagger = 1;    
                         }            
                     Elseif ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih10==Null){ 
                    $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;



                        case 3:
                     if ($asih3==Null){ 
                        $asih3 = $subjects[$iter][0]; $noasih3 = $subjects[$iter][1];
                        $mpph3 =  $getmpar["mpp_id"];   
                        $flagger = 1;  
                         }
                     Elseif ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih10==Null){ 
                    $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break; 


                    /*    case 4:
                    if ($asih4==Null){ 
                         $asih4 = $subjects[$iter][0]; $noasih4 = $subjects[$iter][1];
                         $mpph4 =  $getmpar["mpp_id"];   
                        $flagger = 1;  
                         }
                    Elseif ($asih9==Null){ 
                    $asih9 = $subjects[$iter][0]; $noasih9 = $subjects[$iter][1];
                    $mpph9 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih10==Null){ 
                    $asih10 = $subjects[$iter][0]; $noasih10 = $subjects[$iter][1];
                    $mpph10 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;*/
   


                    }            
                   
                    }  


                   if ($subjects[$iter][4] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    if ($asih5==Null){ 
                         $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                         $mpph5 =  $getmpar["mpp_id"];   
                        $flagger = 1;
                         }        
                    Elseif ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih12=Null){ 
                    $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;


                        case 2:
                    if ($asih6==Null){ 
                         $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                         $mpph6 =  $getmpar["mpp_id"];   
                            $flagger = 1;
                         }        
                    Elseif ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih12=Null){ 
                    $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;


                        case 3:
                     if ($asih7==Null){ 
                         $asih7 = $subjects[$iter][0]; $noasih7 = $subjects[$iter][1];
                         $mpph7 =  $getmpar["mpp_id"];   
                            $flagger = 1;
                         }        
                    Elseif ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih12=Null){ 
                    $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;

                    /*   case 4:
                    if ($asih8==Null){ 
                         $asih8 = $subjects[$iter][0]; $noasih8 = $subjects[$iter][1];
                         $mpph8 =  $getmpar["mpp_id"];   
                            $flagger = 1;
                         }        
                    Elseif ($asih11==Null){ 
                    $asih11 = $subjects[$iter][0]; $noasih11 = $subjects[$iter][1];
                    $mpph11 =  $getmpar["mpp_id"]; 
                    $flagger = 1;  
                 }
                    Elseif ($asih12=Null){ 
                    $asih12 = $subjects[$iter][0]; $noasih12 = $subjects[$iter][1];
                    $mpph12 =  $getmpar["mpp_id"];   
                    $flagger = 1;
                    }     
                    Else{   

                     $flagger = 2;
                        }
                    break;     */                  
                    }        }    

                // update paal_cantidad

                if ($flagger ==1)   {
                 $cantidadal = $getpaal["paal_cantidad"] + 1;  
                $paal_id = $getpaal["paal_id"] ;  
                $updatepaalcantidad = "
                UPDATE db_academico.paralelos_alumno SET paal_cantidad = $cantidadal 
                WHERE paal_id = $paal_id";
                $comando = $con->createCommand($updatepaalcantidad);
                $result = $comando->execute();


                  $pla_api = $rows_pla["pla_id"];
                  $asi_api = $subjects[$iter][2];
                  $mod_api = $rows["mod_id"];
                  $maca_api = $rows["maca_id"];
                  $uaca_api = $rows["uaca_id"];
                  $bloq_api = $getifasi["hosd_bloque"];
                  $mpp_api =  $getmpar["mpp_id"]; 
                  $mpp_num =  $getmpar["mpp_num_paralelo"]; 
/*
                $searchparsiiga = "
                    SELECT  pasi_id,pasi_cantidad  from db_academico.paralelos_siiga
                    WHERE pla_id = :pla_id 
                    AND asi_id = :asi_id
                    AND mod_id = :mod_id
                    AND maca_id = :maca_id
                    AND uaca_id = :uaca_id
                    AND bloq_id = :bloq_id
                    AND mpp_id = :mpp_id
                    ";

                   $comando = $con->createCommand($searchparsiiga);
                   $comando->bindParam(":pla_id",   $pla_api, \PDO::PARAM_INT);
                   $comando->bindParam(":asi_id",   $asi_api, \PDO::PARAM_INT);
                   $comando->bindParam(":mod_id",   $mod_api, \PDO::PARAM_INT);
                   $comando->bindParam(":maca_id", $maca_api, \PDO::PARAM_INT);
                   $comando->bindParam(":uaca_id", $uaca_api, \PDO::PARAM_INT);
                   $comando->bindParam(":bloq_id", $bloq_api, \PDO::PARAM_INT);
                   $comando->bindParam(":mpp_id",   $mpp_api, \PDO::PARAM_INT);
                   $existparsiiga = $comando->queryOne();
                

                  if ($existparsiiga["pasi_id"] == Null) { 

                $createpartemp =
                "
                 INSERT INTO db_academico.paralelos_siiga
    (pla_id,asi_id,mod_id,maca_id,uaca_id,bloq_id,mpp_id,siiga_paralelo,pasi_cantidad,pasi_usuario_ingreso,pasi_estado,pasi_fecha_creacion,pasi_estado_logico)

                VALUES 
    ('" . $pla_api . "','" . $asi_api . "','" . $mod_api . "','" . $maca_api . "','" . $uaca_api . "','" . $bloq_api . "','" . $mpp_api . "','" . $mpp_num . "','1','1', '1',
 '2021-08-30 17:10:53', '1')
                ";
    
                 $comando = $con->createCommand($createpartemp);
                 $fillpars = $comando->execute(); 

                  } Else {                              

                $cantpasi = $existparsiiga["pasi_cantidad"] + 1;  
                $pasi_id = $existparsiiga["pasi_id"] ;  
                $updatepasicant = "
                UPDATE db_academico.paralelos_siiga SET pasi_cantidad = $cantpasi 
                WHERE pasi_id = $pasi_id";
                $comando = $con->createCommand($updatepasicant);
                $result = $comando->execute();  
                      
                   

                                                      }*/

                     }   

                   
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
            //   \app\models\Utilities::putMessageLogFile("Returning ".$ok); 
               
              return true; 
               }    
    
}   

public function consultaParalelosxMateria($asi_id,$saca_id,$mod_id) {
    $con = \Yii::$app->db_academico;
    $estado = 1;
    $sql = "SELECT mpp.mpp_id as id, concat('Paralelo ',mpp.mpp_num_paralelo) as nombre 
            FROM db_academico.materia_paralelo_periodo mpp 
            INNER JOIN db_academico.semestre_academico saca ON saca.saca_id = $saca_id
            INNER JOIN db_academico.periodo_academico paca ON mpp.paca_id = paca.paca_id AND paca.saca_id = saca.saca_id
            INNER JOIN db_academico.planificacion pla ON pla.saca_id = saca.saca_id AND pla.mod_id = mpp.mod_id
            WHERE mpp.daho_id IS NOT NULL AND
                  mpp.asi_id = $asi_id AND mpp.mod_id = $mod_id AND
                  mpp.mpp_estado = 1 AND mpp.mpp_estado_logico = 1 AND
                  saca.saca_estado = 1 AND saca.saca_estado_logico = 1 AND
                  paca.paca_estado = 1 AND paca.paca_estado_logico = 1 AND
                  pla.pla_estado = 1 AND pla.pla_estado_logico = 1;";
    $comando = $con->createCommand($sql);
    //\app\models\Utilities::putMessageLogFile('Consultar Paralelos: '.$comando->getRawSql());
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
    //\app\models\Utilities::putMessageLogFile('Consultar Paralelos N: '.implode(",", $resultData));
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

/**
     * Function Obtiene malla academica para centro de idiomas, segun per_id del estudiante y modalidad 
     * @author  Julio Lopez <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar los datos).
     */
    public function selectAsignaturaPorMallaAutCentroIdioma($per_id, $mod_id, $op) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        if (isset($mod_id) && $mod_id!="") {
            $str_search .= "mu.mod_id = :mod_id AND ";                
        }

        $sql ="SELECT 
                   distinct(a.asi_id) as 'id',
                   concat(made.made_codigo_asignatura,' - ',a.asi_nombre) as 'name',
                   made.made_asi_requisito as 'materia previa'
            from 
            " . $con->dbname . ".malla_academica_detalle as made 
            inner join " . $con->dbname . ".malla_academica as maca on maca.maca_id = made.maca_id
            inner join " . $con->dbname . ".asignatura as a on a.asi_id = made.asi_id
            inner join " . $con->dbname . ".malla_unidad_modalidad mumo on mumo.maca_id = maca.maca_id
            inner join " . $con->dbname . ".modalidad_estudio_unidad mu on mu.meun_id = mumo.meun_id 
            where $str_search
                made.maca_id in (97, 101) and
                made.made_codigo_asignatura not in
                                (select mad.made_codigo_asignatura from db_academico.planificacion_estudiante ple,
                                db_academico.malla_academica_detalle mad
                                where mad.made_codigo_asignatura in (
                                ple.pes_mat_b1_h1_cod,ple.pes_mat_b1_h2_cod,ple.pes_mat_b1_h3_cod,ple.pes_mat_b1_h4_cod,ple.pes_mat_b1_h5_cod,ple.pes_mat_b1_h6_cod,
                                ple.pes_mat_b2_h1_cod,ple.pes_mat_b2_h2_cod,ple.pes_mat_b2_h3_cod,ple.pes_mat_b2_h4_cod,ple.pes_mat_b2_h5_cod,ple.pes_mat_b2_h6_cod)
                                and per_id = $per_id) and
                made.made_estado = :estado and made.made_estado_logico = :estado and
                maca.maca_estado = :estado and maca.maca_estado_logico = :estado and
                a.asi_estado = :estado and a.asi_estado_logico = :estado and
                mumo.mumo_estado =:estado and mumo.mumo_estado_logico=:estado and
                mu.meun_estado = :estado and mu.meun_estado_logico=:estado;";

        $comando = $con->createCommand($sql);
        if (isset($mod_id) && $mod_id!="") {
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_STR);
        }

        if($per_id == null){
            $resultData = [];
        }else{
            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            //$comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
            \app\models\Utilities::putMessageLogFile('selectAsignaturaPorMallaAutCentroIdioma: '.$comando->getRawSql());

            if ($op==1){
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
                    return $dataProvider;
            }

        }
        return $resultData;
    }

}
