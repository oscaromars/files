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
    
    
    function consultarAsignaturas($rows,$gest,$semestre) {
    $con = \Yii::$app->db_academico;
    $activo="A";


        $sql = "select distinct  a.maca_id, a.asi_id, a.made_semestre, a.uest_id, a.nest_id, a.fmac_id, 
a.made_codigo_asignatura, a.made_asi_requisito, a.made_credito, c.uaca_id,
c.mod_id, c.eaca_id, d.asi_nombre
from db_academico.malla_academica_detalle a
inner join db_academico.malla_unidad_modalidad b on b.maca_id = a.maca_id 
inner join db_academico.modalidad_estudio_unidad c on c.meun_id = b.meun_id
 inner join db_academico.asignatura d on d.asi_id = a.asi_id
                       where c.eaca_id =  " . $rows["eaca_id"] . "   
                      and   c.mod_id =  " . $rows["mod_id"] . "   
                      and a.maca_id =  " . $rows["maca_id"] . "  
                            and a.made_estado = 1
                            and a.made_estado_logico = 1
                            and b.mumo_estado = 1
                            and b.mumo_estado_logico = 1
                            and c.meun_estado = 1
                            and c.meun_estado_logico = 1
                            and d.asi_estado = 1
                            and d.asi_estado_logico = 1
                     ORDER BY a.made_semestre, a.made_asi_requisito ASC
                     
                     
                        ";
                        
                        $comando = $con->createCommand($sql);
          $comando->bindParam(":activo", $activo, \PDO::PARAM_STR);
          $comando->bindParam(":paca_id", $gest, \PDO::PARAM_INT);
               $rows_in = $comando->queryAll();

             $per_id =   $rows["per_id"];
             $est_id =   $rows["est_id"];
             $matricula =   $rows["matricula"];
             $creacion =   $rows["est_fecha_creacion"];
             $categoria =   $rows["est_categoria"];
             $uaca_id =   $rows["uaca_id"];
             $mod_id =   $rows["mod_id"];
             $eaca_id =   $rows["eaca_id"];
             $uaca_id =   $rows["uaca_id"];
             $uaca_nombre =   $rows["uaca_nombre"];
             $teac_id =   $rows["teac_id"];
             $eaca_nombre =   $rows["eaca_nombre"];
             $eaca_codigo =   $rows["eaca_codigo"];
             $cedula =   $rows["per_cedula"];
             $estudiante =   $rows["estudiante"];
              $estado = '1';

            /*
             $geth="
             SELECT max(hosd_grupo) as grupo from db_academico.horarios_semestre
             WHERE saca_id = :semestre
             AND mod_id = :mod_id
             AND uaca_id = :uaca_id "
                 ;
            
            $comando = $con->createCommand($sql);
            $comando->bindParam(":saca_id", $semestre, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $gettedh = $comando->queryOne();

             if ($gettedh == Null){

             $puth = "
             INSERT INTO db_academico.horarios_semestre
             (saca_id, mod_id, uaca_id, hosd_grupo,hose_usuario_ingreso, hose_estado, hose_estado_logico)
            VALUES ('" . $semestre . "', ". $rows["mod_id"] .",". $rows["uaca_id"] .",1,1, '" . $estado . "', '" . $estado . "')"
            ;
            
               $comando = $con->createCommand($sql); 
                     $puttedh = $comando->execute();
            }
            
               
            $grupo =$gettedh["grupo"];
            if ($grupo == Null) { $grupo=1;} */

            
            //   1 . 1 GET CABECERA


             $geth="
             SELECT hose_id from db_academico.horarios_semestre
             WHERE saca_id = :semestre
             AND mod_id = :mod_id
             AND uaca_id = :uaca_id 
                   "
                 ;
            
            $comando = $con->createCommand($geth);
            $comando->bindParam(":semestre", $gest, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
            $hid = $comando->queryOne();
            $horario =$hid["hose_id"];

            
            //   1  .  2  SET CABECERA


             if ($hid["hose_id"] == Null){


             $puth = "
             INSERT INTO db_academico.horarios_semestre
             (saca_id, mod_id, uaca_id,hose_usuario_ingreso, hose_estado, hose_estado_logico)
            VALUES ('" . $gest . "', ". $rows["mod_id"] .",". $rows["uaca_id"] .",1, '" . $estado . "', '" . $estado . "')"
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
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
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

        // $fecha_inicio = $rows_in[0]["paca_fecha_inicio"];
          //      $fecha_fin = $rows_in[0]["paca_fecha_fin"];
                $estacion = $gest;
                $paca_id = $gest;
          for ($i = 0; $i < count($rows_in); $i++) {    
          
           $mod_id = $rows_in[$i]["mod_id"];
                 $asignatura = $rows_in[$i]["asi_id"];
                 $requisito = $rows_in[$i]["made_asi_requisito"]; 
                 $fecha_inicio = $paca_in[0]["paca_fecha_inicio"];
                 $fecha_fin = $paca_in[0]["paca_fecha_fin"];
                 //$fecha_inicio = "2021-06-20 23:59:59";
                 //$fecha_fin = "2021-09-20 23:59:59";
                 
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
                   
                   
                          
                 
                 //    \app\models\Utilities::putMessageLogFile('automa statusasi '.$statusasi["enac_id"]);
              
                   //  \app\models\Utilities::putMessageLogFile('automa asiasi '.$statusasi["asi_id"]);
                 
                   //  \app\models\Utilities::putMessageLogFile('automa maessasi '.$statusasi["maes_id"]);
                    
                   //  \app\models\Utilities::putMessageLogFile('automa fullasi '.$statusasi);
                     
                 
                 
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
                     
                            
               //   \app\models\Utilities::putMessageLogFile('automa statuspre '.$statuspre["enac_id"]);
                  
                //      \app\models\Utilities::putMessageLogFile('automa asipre '.$statuspre["asi_id"]);
                  
                 //     \app\models\Utilities::putMessageLogFile('automa maesspre '.$statuspre["maes_id"]);
                   
                   //      \app\models\Utilities::putMessageLogFile('automa fullpre '.$statuspre);
            
                     
                   
                     }
                     
                     
                   // DATOS TEMPORALES
            
                     
                  // if ($statusasi["enac_id"]== 3)  {  
                  // $mensaje = "statuspre ".$statuspre["enac_id"]." statusasi ".$statusasi["enac_id"];
                  // mail('oscaromars@hotmail.com', 'enac_estado', $mensaje);
                  //  }
                  
                   
                    // if ($statusasi["maes_id"]){ 
                    
                       if ($statusasi["enac_id"]==3 /*or $statusasi["enac_id"]==2 or $statusasi["enac_id"]==4 or $statusasi["enac_id"]==1 or $statusasi["enac_id"]==Null*/){ 
                      $sstatusasi= $statusasi["enac_id"];  
                      
                      
                        if ($requisito !=Null){                  
                      $sstatuspre= $statuspre["enac_id"]; 
                      
                      
                        if ($statuspre["enac_id"]==1 /* or $statuspre["enac_id"]==2 or $statuspre["enac_id"]==3 or $statuspre["enac_id"]==4 or $statuspre["enac_id"]==Null*/){ 
                        
                        /*    \app\models\Utilities::putMessageLogFile('CON PR ');
                        \app\models\Utilities::putMessageLogFile('TIENE STATUS '.$sstatusasi);
                         \app\models\Utilities::putMessageLogFile('TIENE MAES '.$statusasi["maes_id"]);
                          \app\models\Utilities::putMessageLogFile('CODIGO '.$rows_in[$i]["made_codigo_asignatura"]);
                           \app\models\Utilities::putMessageLogFile('NOMBRE '.$rows_in[$i]["asi_nombre"]);
                               \app\models\Utilities::putMessageLogFile('======================================================= '); */
                        
                          if ($asi1 == Null)  { 
                  
                   $asi1 = $rows_in[$i]["made_codigo_asignatura"];
                     $noasi1 = $rows_in[$i]["asi_nombre"];      }  
                elseif ($asi2 == Null)  { 
                    $asi2 = $rows_in[$i]["made_codigo_asignatura"];
                     $noasi2 = $rows_in[$i]["asi_nombre"];       }                    
                elseif ($asi3 == Null)  { 
                    $asi3 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi3 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi4 == Null)  { 
                    $asi4 = $rows_in[$i]["made_codigo_asignatura"]; 
                     $noasi4 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi5 == Null)  { 
                    $asi5 = $rows_in[$i]["made_codigo_asignatura"]; 
                     $noasi5 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi6 == Null)  { 
                    $asi6 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi6 = $rows_in[$i]["asi_nombre"];     } 
                
                elseif ($asi7 == Null)  { 
                    $asi7 = $rows_in[$i]["made_codigo_asignatura"]; 
                     $noasi7 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi8 == Null)  { 
                    $asi8 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi8 = $rows_in[$i]["asi_nombre"];     }    


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
                         
                       
                           }else {      
                         
                         
                         
                       /*   \app\models\Utilities::putMessageLogFile('SIN PR ');
                        \app\models\Utilities::putMessageLogFile('TIENE STATUS '.$sstatusasi);
                         \app\models\Utilities::putMessageLogFile('TIENE MAES '.$statusasi["maes_id"]);
                          \app\models\Utilities::putMessageLogFile('CODIGO '.$rows_in[$i]["made_codigo_asignatura"]);
                           \app\models\Utilities::putMessageLogFile('NOMBRE '.$rows_in[$i]["asi_nombre"]);
                          \app\models\Utilities::putMessageLogFile('======================================================= '); */
                        
                        
                          if ($asi1 == Null)  { 
                  
                   $asi1 = $rows_in[$i]["made_codigo_asignatura"];
                     $noasi1 = $rows_in[$i]["asi_nombre"];      }  
                elseif ($asi2 == Null)  { 
                    $asi2 = $rows_in[$i]["made_codigo_asignatura"];
                     $noasi2 = $rows_in[$i]["asi_nombre"];       }                    
                elseif ($asi3 == Null)  { 
                    $asi3 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi3 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi4 == Null)  { 
                    $asi4 = $rows_in[$i]["made_codigo_asignatura"]; 
                     $noasi4 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi5 == Null)  { 
                    $asi5 = $rows_in[$i]["made_codigo_asignatura"]; 
                     $noasi5 = $rows_in[$i]["asi_nombre"];     }  
                elseif ($asi6 == Null)  { 
                    $asi6 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi6 = $rows_in[$i]["asi_nombre"];     } 
                elseif ($asi7 == Null)  { 
                    $asi7 = $rows_in[$i]["made_codigo_asignatura"];  
                     $noasi7 = $rows_in[$i]["asi_nombre"];     } 
                elseif ($asi8 == Null)  { 
                    $asi8 = $rows_in[$i]["made_codigo_asignatura"];  
                    $noasi8 = $rows_in[$i]["asi_nombre"];     } 



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
                    
                    
           
                       
                         }       // }  
                         
                         
                   
                       
                       
                       
                   
               
              
               
                   
                     
                        }  
       
                     $sql = "select pla_id from db_academico.planificacion 
                      where mod_id = " . $mod_id . " 
                      and pla_estado = 1 and pla_estado_logico = 1 and saca_id = :periodo";  
                
                   $comando = $con->createCommand($sql);
                    $comando->bindParam(":periodo", $estacion, \PDO::PARAM_STR);
                     $rows_pla = $comando->queryOne();
                     
                     $estado=1;
                     
                                 //if (count($rows_pla) == 0 ) {
                               if ($rows_pla["pla_id"] == 0)  {
                                 
                
                $sql = "INSERT INTO db_academico.planificacion (mod_id, per_id, pla_fecha_inicio, pla_fecha_fin, pla_periodo_academico, pla_estado, pla_estado_logico,saca_id)
                        VALUES (". $rows["mod_id"] .", 1, '" . $fecha_inicio . "', '" . $fecha_fin . "', '" . $semestre . "', '" . $estado . "', '" . $estado . "','" . $estacion . "');";
                 $comando = $con->createCommand($sql); 
                     $rows_pla = $comando->execute();
              
                  } 
                 $sql = "select pla_id from db_academico.planificacion where mod_id = " . $mod_id . " and pla_estado = 1 and pla_estado_logico = 1 and saca_id = :periodo ";
                  $comando = $con->createCommand($sql);
                   $comando->bindParam(":periodo", $estacion, \PDO::PARAM_STR);
                   $rows_pla = $comando->queryOne();
           
         
                     
                          if ($asi1 != Null)  { 
                      /*
                      $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada, pes_carrera, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b1_h5_cod,
                     pes_mat_b1_h6_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b1_h5_nombre,
                     pes_mat_b1_h6_nombre, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "', '" . $rows["eaca_nombre"] . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asi1 . "', '" . $asi2 . "', '" . $asi3 . "', '" . $asi4 . "', '" . $asi5 . "', '" . $asi6 . "', '" . $noasi1 . "', '" . $noasi2 . "', '" . $noasi3 . "', '" . $noasi4 . "', '" . $noasi5 . "', '" . $noasi6 . "', '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute();
                     
       
                     
                        */
                    



                     switch ($rows["mod_id"]) {
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
                    
                    if ($rows["mod_id"] > 2){
                    
                    
            //   1   .    3     ADD SUBJECTS TO SCHEDULE BY GROUP


            \app\models\Utilities::putMessageLogFile('hose id again: '.$hid["hose_id"]); 
            \app\models\Utilities::putMessageLogFile('made_codigo_asignatura: '.$subjects[1][0]);
            \app\models\Utilities::putMessageLogFile('asi_nombre: '.$subjects[1][1]);
            \app\models\Utilities::putMessageLogFile('asi_id: '.$subjects[1][2]);
            \app\models\Utilities::putMessageLogFile('made_credito: '.$subjects[1][3]);
    

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

                    $codd = $subjects[$iter][0]; // ["made_codigo_asignatura"]
                   $nomm = $subjects[$iter][1]; // ["asi_nombre"] 
                   $iddd = $subjects[$iter][2]; // ["asi_id"]
                   $cred = $subjects[$iter][3]; // ["made_credito"] 


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

        


                     }  }
                 }  //END ITER



 


                  //   1   .    4     GET SUBJECTS FOR PES

              for ($iter = 1;$iter <= 8; ++$iter){

                   $codd = $subjects[$iter][0]; // ["made_codigo_asignatura"]
                   $nomm = $subjects[$iter][1]; // ["asi_nombre"] 
                   $iddd = $subjects[$iter][2]; // ["asi_id"]
                   $cred = $subjects[$iter][3]; // ["made_credito"] 
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


            \app\models\Utilities::putMessageLogFile('GRUPO: '.$getifasi["hosd_grupo"]);  
            \app\models\Utilities::putMessageLogFile('BLOQUE: '.$getifasi["hosd_bloque"]);  
            \app\models\Utilities::putMessageLogFile('HORA: '.$getifasi["hosd_hora"]);  
                   
                   /*
                   $getifasi["hosd_bloque"]; 
                   $getifasi["hosd_hora"]; */

                   if ($getifasi["hose_id"] != Null){

                  if ($getifasi["hosd_bloque"] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                         $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                        break;
                        case 2:
                    $asih2 = $subjects[$iter][0];$noasih2 = $subjects[$iter][1];
                         break;      
                        case 3:
                    $asih3 = $subjects[$iter][0];$noasih3 =$subjects[$iter][1];
                         break;                                    
                    }            
                   
                    }  



                  if ($getifasi["hosd_bloque"] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    $asih4 = $subjects[$iter][0]; $noasih4 = $subjects[$iter][1];
                        break;
                        case 2:
                   $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                         break;      
                        case 3:
                    $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                         break;                                    
                    }        }          
                   
                    }  



                     }//endfor       

            \app\models\Utilities::putMessageLogFile('asih1: '.$asih1);    
            \app\models\Utilities::putMessageLogFile('asih2: '.$asih2);  
            \app\models\Utilities::putMessageLogFile('asih3: '.$asih3);  
            \app\models\Utilities::putMessageLogFile('asih4: '.$asih4);  
            \app\models\Utilities::putMessageLogFile('asih5: '.$asih5);  
            \app\models\Utilities::putMessageLogFile('asih6: '.$asih6);            

                    

                     $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asih1 . "', '" . $asih2 . "', '" . $asih3 . "',Null, '" . $asih4 . "', '" . $asih5 . "', '" . $asih6 . "',Null, '" . $noasih1 . "', '" . $noasih2 . "', '" . $noasih3 . "',Null, '" . $noasih4 . "', '" . $noasih5 . "', '" . $noasih6 . "',Null,". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "', '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute(); 

                    /*


                      $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asi1 . "', '" . $asi2 . "', '" . $asi3 . "',Null, '" . $asi4 . "', '" . $asi5 . "', '" . $asi6 . "',Null, '" . $noasi1 . "', '" . $noasi2 . "', '" . $noasi3 . "',Null, '" . $noasi4 . "', '" . $noasi5 . "', '" . $noasi6 . "',Null,". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "', '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute(); 
                    
                     */

                    } Else {


                               
            //   1   .    3     ADD SUBJECTS TO SCHEDULE BY GROUP

                    \app\models\Utilities::putMessageLogFile('hose id again: '.$hid["hose_id"]); 
            \app\models\Utilities::putMessageLogFile('made_codigo_asignatura: '.$subjects[1][0]);
            \app\models\Utilities::putMessageLogFile('asi_nombre: '.$subjects[1][1]);
            \app\models\Utilities::putMessageLogFile('asi_id: '.$subjects[1][2]);
            \app\models\Utilities::putMessageLogFile('made_credito: '.$subjects[1][3]);
    

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

                    $codd = $subjects[$iter][0]; // ["made_codigo_asignatura"]
                   $nomm = $subjects[$iter][1]; // ["asi_nombre"] 
                   $iddd = $subjects[$iter][2]; // ["asi_id"]
                   $cred = $subjects[$iter][3]; // ["made_credito"] 


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

        


                     }  }
                 }  //END ITER



 


                  //   1   .    4     GET SUBJECTS FOR PES

              for ($iter = 1;$iter <= 8; ++$iter){

                   $codd = $subjects[$iter][0]; // ["made_codigo_asignatura"]
                   $nomm = $subjects[$iter][1]; // ["asi_nombre"] 
                   $iddd = $subjects[$iter][2]; // ["asi_id"]
                   $cred = $subjects[$iter][3]; // ["made_credito"] 
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


            \app\models\Utilities::putMessageLogFile('GRUPO: '.$getifasi["hosd_grupo"]);  
            \app\models\Utilities::putMessageLogFile('BLOQUE: '.$getifasi["hosd_bloque"]);  
            \app\models\Utilities::putMessageLogFile('HORA: '.$getifasi["hosd_hora"]);  
                   
                   /*
                   $getifasi["hosd_bloque"]; 
                   $getifasi["hosd_hora"]; */

                   if ($getifasi["hose_id"] != Null){

                  if ($getifasi["hosd_bloque"] == 1){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                         $asih1 = $subjects[$iter][0]; $noasih1 = $subjects[$iter][1];
                        break;
                        case 2:
                    $asih2 = $subjects[$iter][0];$noasih2 = $subjects[$iter][1];
                         break;      
                        case 3:
                    $asih3 = $subjects[$iter][0];$noasih3 =$subjects[$iter][1];
                         break;              
                        case 4:
                    $asih4 = $subjects[$iter][0];$noasih4 =$subjects[$iter][1];
                         break;                         
                    }            
                   
                    }  



                  if ($getifasi["hosd_bloque"] == 2){
                    switch ($getifasi["hosd_hora"]) {
                        case 1:
                    $asih5 = $subjects[$iter][0]; $noasih5 = $subjects[$iter][1];
                        break;
                        case 2:
                   $asih6 = $subjects[$iter][0]; $noasih6 = $subjects[$iter][1];
                         break;      
                        case 3:
                    $asih7 = $subjects[$iter][0]; $noasih7 = $subjects[$iter][1];
                         break;    
                        case 4:
                    $asih8 = $subjects[$iter][0]; $noasih8 = $subjects[$iter][1];
                         break;  

                    }        }          
                   
                    }  



                     }//endfor    


                            $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asih5 . "', '" . $asih5 . "', '" . $asih3 . "', '" . $asih4 . "', '" . $asih1 . "', '" . $asih2 . "', '" . $asih7 . "', '" . $asih8 . "', '" . $noasih5 . "', '" . $noasih6 . "', '" . $noasih3 . "', '" . $noasih4 . "', '" . $noasih1 . "', '" . $noasih2 . "', '" . $noasih7 . "', '" . $noasih8 . "',". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "', '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute(); 

                    

                    /*
                     $sql = "INSERT INTO db_academico.planificacion_estudiante
                    (pla_id, per_id, pes_jornada,pes_cod_carrera, pes_carrera, pes_dni, pes_nombres,pes_mat_b1_h1_cod, pes_mat_b1_h2_cod, pes_mat_b1_h3_cod, pes_mat_b1_h4_cod, pes_mat_b2_h1_cod,
                     pes_mat_b2_h2_cod,pes_mat_b2_h3_cod,pes_mat_b2_h4_cod, pes_mat_b1_h1_nombre, pes_mat_b1_h2_nombre, pes_mat_b1_h3_nombre, pes_mat_b1_h4_nombre, pes_mat_b2_h1_nombre,  pes_mat_b2_h2_nombre, pes_mat_b2_h3_nombre, pes_mat_b2_h4_nombre, pes_mod_b1_h1,  pes_mod_b1_h2,  pes_mod_b1_h3,  pes_mod_b1_h4,  pes_mod_b2_h1,  pes_mod_b2_h2,  pes_mod_b2_h3, 
                        pes_mod_b2_h4, pes_jor_b1_h1,  pes_jor_b1_h2,  pes_jor_b1_h3,  pes_jor_b1_h4,  pes_jor_b2_h1,  pes_jor_b2_h2,  pes_jor_b2_h3, 
                        pes_jor_b2_h4, pes_estado, pes_estado_logico)
                    values (" . $rows_pla["pla_id"] ."," . $rows["per_id"] . ", '" . $rows["uaca_id"] . "','" . $rows["maca_codigo"] . "', '" . $rows["maca_nombre"] . "', '" . $rows["per_cedula"] . "', '" . $rows["estudiante"] . "', '" . $asi1 . "', '" . $asi2 . "', '" . $asi3 . "', '" . $asi4 . "', '" . $asi5 . "', '" . $asi6 . "', '" . $asi7 . "', '" . $asi8 . "', '" . $noasi1 . "', '" . $noasi2 . "', '" . $noasi3 . "', '" . $noasi4 . "', '" . $noasi5 . "', '" . $noasi6 . "', '" . $noasi7 . "', '" . $noasi8 . "',". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",". $rows["mod_id"] .",  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "',  '" . $jornadas . "', '" . $estado . "', '" . $estado ."')"; 
                     $comando = $con->createCommand($sql);
                     $rows_pes = $comando->execute();  */
                    
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
                       
     
    
    
     
    
    
}   }