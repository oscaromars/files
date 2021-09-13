<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\DistributivoAcademico;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DistributivoAcademicoSearch extends DistributivoAcademico {

    public function rules() {
        return [
            [['paca_id', 'tdis_id', 'asi_id', 'pro_id', 'mod_id', 'daho_id', 'daca_num_estudiantes_online', 'daca_usuario_ingreso', 'daca_usuario_modifica'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getListadoMateriaNoAsignada($params = null, $onlyData = false, $tipo = 1,$bloque,$paca_id) {
        $con_academico = \Yii::$app->db_academico;
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $mod_id = 0;
        $asi_id = 0;
        $uaca_id = '';
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                 if ($this->mod_id) {
                        $mod_id= $this->mod_id;
                }
                
                
            }
        }
        $sql ="select mpp.asi_id id,(select asi_nombre from db_academico.asignatura a where a.asi_id=mpp.asi_id) name , mpp_num_paralelo
from  db_academico.materia_paralelo_periodo mpp 
left join db_academico.distributivo_academico  da on da.mpp_id=mpp.mpp_id and da.mod_id=mpp.mod_id and da.asi_id=mpp.asi_id  and da.paca_id=mpp.paca_id
    where da.mpp_id is null and mpp.mod_id =:mod_id";
        
       /*$sql1 = " and a.asi_id  not in (
                select da.asi_id 
                  from " . $con_academico->dbname . ".distributivo_academico da
                  inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id= dc.dcab_id  and da.daca_estado='1'"
               . " and daca_estado_logico='1' and da.asi_id is not null  and da.paca_id= " . $paca_id."  and mod_id=".$mod_id . ")  ";
        
        
        if ($bloque=="B1") {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                   inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                   inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b1_h1_cod
                   inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE pes_estado = 1       and pes_estado_logico = 1 and mod_id =:mod_id  ".$sql1
                ." UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                   inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                  inner join db_academico.malla_academica_detalle md     on md.made_codigo_asignatura = pe.pes_mat_b1_h2_cod
                  inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id and pes_estado = 1  and pes_estado_logico = 1  ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                  inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                  inner join db_academico.malla_academica_detalle md     on md.made_codigo_asignatura = pe.pes_mat_b1_h3_cod
                  inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id  and pes_estado = 1  and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md on md.made_codigo_asignatura = pe.pes_mat_b1_h4_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id    and pes_estado = 1   and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md   on md.made_codigo_asignatura = pe.pes_mat_b1_h5_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id  and pes_estado = 1   and pes_estado_logico = 1" .$sql1 ;
        } else {
            $sql = "SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h1_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id   and pes_estado = 1    and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md    on md.made_codigo_asignatura = pe.pes_mat_b2_h2_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id  and pes_estado = 1 and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md      on md.made_codigo_asignatura = pe.pes_mat_b2_h3_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id  and pes_estado = 1 and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md  on md.made_codigo_asignatura = pe.pes_mat_b2_h4_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id  and pes_estado = 1   and pes_estado_logico = 1 ".$sql1."
                UNION
                SELECT distinct a.asi_id id, asi_nombre name
                FROM db_academico.planificacion_estudiante pe 
                inner join db_academico.planificacion p on p.pla_id=pe.pla_id
                inner join db_academico.malla_academica_detalle md   on md.made_codigo_asignatura = pe.pes_mat_b2_h5_cod
                inner join db_academico.asignatura a on a.asi_id = md.asi_id
                WHERE mod_id =:mod_id and pes_estado = 1 and pes_estado_logico = 1 " .$sql1;
        }        
        */
      //  $comando = $con->createCommand($sql);
       // $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);  
       
          Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT); 
       $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        return $dataProvider;
    }

    public function getListadoMateriaNoAsignadaexcel($arrFiltro = NULL, $onlyData = false, $mod_id) {
        \app\models\Utilities::putMessageLogFile('modalidad '. $mod_id);
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :mod_id AND ";
            } 
                     
        }

            $sql = "select mpp.asi_id id,(select asi_nombre from db_academico.asignatura a where a.asi_id=mpp.asi_id) name , mpp_num_paralelo
                from  db_academico.materia_paralelo_periodo mpp 
                left join db_academico.distributivo_academico  da on da.mpp_id=mpp.mpp_id and da.mod_id=mpp.mod_id and da.asi_id=mpp.asi_id  and da.paca_id=mpp.paca_id
                    where da.mpp_id is null and mpp.mod_id =:mod_id";
        
        
        $comando = $con_academico->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }

            
        }
        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getListadoDistributivoexcel: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'id', 
                    'name',
                    'mpp_num_paralelo'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    
    public function getListadoDistributivoPosgrados($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        

        $sql = "select 
                UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                per_cedula as no_cedula,
                IFNULL(UPPER(pi3.pins_titulo),'N/A') as  titulo_tercel_nivel,
                IFNULL(UPPER(pi4.pins_titulo),'N/A') as  titulo_cuarto_nivel,
                ( SELECT b.eaca_nombre name
                FROM db_academico.modalidad_estudio_unidad a
                inner join db_academico.estudio_academico b   on b.eaca_id = a.eaca_id
                WHERE a.uaca_id = da.uaca_id
                       and a.meun_id=da.meun_id
                      and a.mod_id = da.mod_id
                      and a.meun_estado = 1
                      and a.meun_estado_logico = 1) as maestria,
                 m.mod_nombre as modalidad,
                dah.daho_horario as hora,
                CONCAT( pp.ppro_grupo,'.', ppr.pppr_descripcion) as paralelo,
                IFNULL( CONCAT( da.daca_fecha_inicio_post,' al ' ,da.daca_fecha_fin_post ) ,'N/A')as dias,
                da.daca_num_estudiantes_online as num_est,
                'N/A' as aula,
                (select made_credito  
                    from db_academico.malla_academica_detalle mad
                    inner join db_academico.malla_unidad_modalidad mum on mad.maca_id=mum.maca_id
                     where mum.meun_id =da.meun_id and mad.asi_id =da.asi_id) as credito,
                     
                UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                IFNULL( asi_nombre,'N/A' )as materia,
                tdis_nombre,
                case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                 case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                inner join " . $con_academico->dbname . ".modalidad m on m.mod_id =  da.mod_id
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
                inner join " . $con_academico->dbname . ".paralelo_promocion_programa ppr on ppr.pppr_id = da.pppr_id
                inner join " . $con_academico->dbname . ".promocion_programa pp on pp.ppro_id = ppr.ppro_id         
                inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id  
                left join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id 
                left join " . $con_academico->dbname . ".profesor_instruccion pi3 on pi3.pro_id = profesor.pro_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                left join " . $con_academico->dbname . ".profesor_instruccion pi4 on pi4.pro_id = profesor.pro_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1    
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id 
                LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where  da.daca_estado='1' and daca_estado_logico='1'  and td.tdis_id <> 6 and da.uaca_id=2    and dc.dcab_estado_revision=2";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->mod_id) {
                    $sql = $sql . " and da.mod_id =" . $this->mod_id;
                }

                if ($this->tdis_id) {
                    $sql = $sql . " and da.tdis_id =" . $this->tdis_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['mod_id']) {
                $sql = $sql . " and da.mod_id =" . $params['mod_id'];
            }

            if ($params['paca_id']) {
                $sql = $sql . " and da.paca_id =" . $params['paca_id'];
            }
            if ($params['tdis_id']) {
                $sql = $sql . " and da.tdis_id =" . $params['tdis_id'];
            }
        }
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['docente', 'no_cedula', "titulo_tercel_nivel", "titulo_cuarto_nivel", "correo", "tiempo_dedicacion", "desempeno", 'materia', 'total_horas_dictar'],
            ],
        ]);

        return $dataProvider;
    }
    
    public function getListadoDistributivoPosgradosexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['tipo_asignacion'] != "" && $arrFiltro['tipo_asignacion'] > 0) {
                $str_search .= "daca.tdis_id = :tdis_id AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :mod_id AND ";
            } 
                     
        }

            $sql = "select  
                UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                IFNULL(UPPER(pi3.pins_titulo),'N/A') as  titulo_tercel_nivel,
                IFNULL(UPPER(pi4.pins_titulo),'N/A') as  titulo_cuarto_nivel,
                ( SELECT b.eaca_nombre name
                FROM db_academico.modalidad_estudio_unidad a
                inner join db_academico.estudio_academico b   on b.eaca_id = a.eaca_id
                WHERE a.uaca_id = da.uaca_id
                       and a.meun_id=da.meun_id
                      and a.mod_id = da.mod_id
                      and a.meun_estado = 1
                      and a.meun_estado_logico = 1) as maestria,
                CONCAT( 'G.',dhpa_grupo ,'.',dhpa_paralelo)  as paralelo,
                IFNULL( asi_nombre,'N/A' ) as materia,
                IFNULL( CONCAT( da.daca_fecha_inicio_post,' al ' ,da.daca_fecha_fin_post ) ,'N/A') as dias,
                dah.daho_horario as hora,
                da.daca_num_estudiantes_online as num_est,
                case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                m.mod_nombre as modalidad,
                'N/A' as aula,
                (select made_credito  
                    from db_academico.malla_academica_detalle mad
                    inner join db_academico.malla_unidad_modalidad mum on mad.maca_id=mum.maca_id
                     where mum.meun_id =da.meun_id and mad.asi_id =da.asi_id) as credito,
                per_cedula as no_cedula,
                UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                tdis_nombre as tdis_nombre,     
                case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                inner join " . $con_academico->dbname . ".modalidad m on m.mod_id =  da.mod_id
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
                inner join " . $con_academico->dbname . ".distributivo_horario_paralelo dhp on dhp.dhpa_id = da.dhpa_id
                inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id  
                left join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id 
                left join " . $con_academico->dbname . ".profesor_instruccion pi3 on pi3.pro_id = profesor.pro_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                left join " . $con_academico->dbname . ".profesor_instruccion pi4 on pi4.pro_id = profesor.pro_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1    
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id 
                LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where  da.daca_estado='1' and daca_estado_logico='1'  and td.tdis_id <> 6 and da.uaca_id=2    and dc.dcab_estado_revision=2";
        
        
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['tipo_asignacion'] != "" && $arrFiltro['tipo_asignacion'] > 0) {
                $tipo_asignacion = $arrFiltro["tipo_asignacion"];
                $comando->bindParam(":tdis_id", $tipo_asignacion, \PDO::PARAM_INT);
            } 
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }

            
        }
        $res = $comando->queryAll();

        //\app\models\Utilities::putMessageLogFile('getListadoDistributivoexcel: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['docente', 'titulo_tercel_nivel', 'titulo_cuarto_nivel', 'maestria', 'paralelo', 'materia', 'dias', 'hora', 'num_est', 'total_horas_dictar', 'modalidad', 'aula', 'credito'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }
    
    public function getListadoDistributivoBloqueDocente($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $paca_id = 0;

        $sql = "select (@row_number:=@row_number + 1) AS Id, IFNULL(CONCAT(ifnull(persona.per_pri_apellido,' '), ' ', ifnull(persona.per_seg_apellido,' '), ' ', ifnull(persona.per_pri_nombre,' '), ' ', ifnull(persona.per_seg_nombre, ' ')), '') as docente,
                per_cedula as no_cedula,
                IFNULL(UPPER(pi3.pins_titulo),'N/A') as  titulo_tercel_nivel,
                IFNULL(UPPER(pi4.pins_titulo),'N/A') as  titulo_cuarto_nivel,
                persona.per_correo as correo,
                -- UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                ifnull((select ifnull(dd.ddoc_nombre, '') from db_academico.dedicacion_docente dd 
                    where dd.ddoc_id = profesor.ddoc_id),' ') as tiempo_dedicacion,
                IFNULL( asi_nombre,'N/A' )as materia,
                tdis_nombre,
                case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                 case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname .        ".persona persona on profesor.per_id = persona.per_id 
               -- inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id  
                LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                left join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id 
                left join " . $con_academico->dbname . ".profesor_instruccion pi3 on pi3.pro_id = profesor.pro_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                left join " . $con_academico->dbname . ".profesor_instruccion pi4 on pi4.pro_id = profesor.pro_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1    
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id 
                LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where da.daca_estado='1' and daca_estado_logico='1'  and td.tdis_id <> 6 and dc.dcab_estado_revision=2";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                if ($this->paca_id) {
                    $sql = $sql . " and da.paca_id =" . $this->paca_id;
                } else {
                    $sql = $sql . " and da.paca_id =0";
                }
                if ($this->mod_id) {
                    $sql = $sql . " and da.mod_id =" . $this->mod_id;
                }

                if ($this->tdis_id) {
                    $sql = $sql . " and da.tdis_id =" . $this->tdis_id;
                }
            } else {
                $sql = $sql . " and da.paca_id =0";
            }
        }
        if ($tipo == 2) {

            if ($params['mod_id']) {
                $sql = $sql . " and da.mod_id =" . $params['mod_id'];
            }

            if ($params['paca_id']) {
                $sql = $sql . " and da.paca_id =" . $params['paca_id'];
            }
            if ($params['tdis_id']) {
                $sql = $sql . " and da.tdis_id =" . $params['tdis_id'];
            }
        }
       
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['docente', 'no_cedula', "titulo_tercel_nivel", "titulo_cuarto_nivel", "correo", "tiempo_dedicacion", "desempeno", 'materia', 'total_horas_dictar'],
            ],
        ]);

        return $dataProvider;
    }

    public function getListadoDistributivoexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "daca.uaca_id = :paca_id AND ";
            }
            if ($arrFiltro['tipo_asignacion'] != "" && $arrFiltro['tipo_asignacion'] > 0) {
                $str_search .= "daca.tdis_id = :tdis_id AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :mod_id AND ";
            } 
                     
        }

            $sql = "select 
                IFNULL(CONCAT(ifnull(persona.per_pri_apellido,' '), ' ', ifnull(persona.per_seg_apellido,' '), ' ', ifnull(persona.per_pri_nombre,' '), ' ', ifnull(persona.per_seg_nombre, ' ')), '') as docente,
                per_cedula as no_cedula,
                IFNULL(UPPER(pi3.pins_titulo),'N/A') as  titulo_tercel_nivel,
                IFNULL(UPPER(pi4.pins_titulo),'N/A') as  titulo_cuarto_nivel,
                persona.per_correo as correo,
                -- UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                ifnull((select ifnull(dd.ddoc_nombre, '') from db_academico.dedicacion_docente dd 
                    where dd.ddoc_id = profesor.ddoc_id),' ') as tiempo_dedicacion,
                tdis_nombre,
                IFNULL( asi_nombre,'N/A' )as materia,
                case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                 case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname .        ".persona persona on profesor.per_id = persona.per_id 
                -- inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id  
                LEFT JOIN " . $con_academico->dbname . ".modalidad AS m ON da.mod_id = m.mod_id
                left join " . $con_academico->dbname .  ".asignatura asi on asi.asi_id = da.asi_id 
                left join " . $con_academico->dbname .  ".profesor_instruccion pi3 on pi3.pro_id = profesor.pro_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                left join " . $con_academico->dbname .  ".profesor_instruccion pi4 on pi4.pro_id = profesor.pro_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1    
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id 
                LEFT JOIN " . $con_academico->dbname .  ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where  da.daca_estado='1' and daca_estado_logico='1'  and td.tdis_id <> 6 and dc.dcab_estado_revision=2";
        
        
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }

            if ($arrFiltro['tipo_asignacion'] != "" && $arrFiltro['tipo_asignacion'] > 0) {
                $tipo_asignacion = $arrFiltro["tipo_asignacion"];
                $comando->bindParam(":tdis_id", $tipo_asignacion, \PDO::PARAM_INT);
            } 
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }

            
        }
        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getListadoDistributivoexcel: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'docente', 
                    'no_cedula',
                    'titulo_tercel_nivel',
                    'titulo_cuarto_nivel',
                    'correo',
                    'tiempo_dedicacion',
                    'tdis_nombre',
                    'materia',
                    'total_horas_dictar',
                    'promedio'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    public function getReportemateriasparalelos($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $paca_id = 0;

        $sql = "select UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                ifnull(asi_nombre,'') as asignatura,
                dhpa_paralelo,
                daho_descripcion
                    from distributivo_academico da
		    inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                    inner join db_academico.profesor profesor on da.pro_id = profesor.pro_id 
                    inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
		    inner join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id 
                    inner join " . $con_academico->dbname . ".distributivo_horario_paralelo dhp on dhp.dhpa_id=da.dhpa_id
                    inner join " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id=da.daho_id
                    where  da.tdis_id in (1,7)  and da.daca_estado='1' and daca_estado_logico='1' ";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                if ($this->paca_id) {
                    $sql = $sql . " and da.paca_id =" . $this->paca_id;
                } else {
                    $sql = $sql . " and da.paca_id =0";
                }
                if ($this->uaca_id) {
                    $sql = $sql . " and profesor.ddoc_id =" . $this->uaca_id;
                }
            } else {
                $sql = $sql . " and da.paca_id =0";
            }
        }


        Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['docente', 'asignatura', 'dhpa_paralelo', 'daho_descripcion'],
            ],
        ]);

        return $dataProvider;
    }

    public function getReportemateriasparalelosexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "daca.paca_id = :paca_id AND ";
            }
                     
        }

            $sql = "select UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                ifnull(asi_nombre,'') as asignatura,
                dhpa_paralelo,
                daho_descripcion
                    from distributivo_academico da
            inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id
                    inner join db_academico.profesor profesor on da.pro_id = profesor.pro_id 
                    inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
            inner join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id 
                    inner join " . $con_academico->dbname . ".distributivo_horario_paralelo dhp on dhp.dhpa_id=da.dhpa_id
                    inner join " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id=da.daho_id
                    where  da.tdis_id in (1,7)  and da.daca_estado='1' and daca_estado_logico='1' ";
        
        
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            
        }
        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getListadoDistributivoexcel: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['docente', 'asignatura', 'dhpa_paralelo', 'daho_descripcion'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    public function getReportematrizdistributivo($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $paca_id = 0;

        $sql = "select '1050' as codigo_ies, IFNULL(CONCAT(ifnull(persona.per_pri_apellido,' '), ' ', ifnull(persona.per_seg_apellido,' '), ' ', ifnull(persona.per_pri_nombre,' '), ' ', ifnull(persona.per_seg_nombre, ' ')), '') as docente,
                case when per_cedula is null then per_pasaporte else per_cedula end as no_cedula,
                case when per_cedula is null then 'Pasaporte' else 'Cédula' end as tipo_identificacion,
                IFNULL(pro_num_contrato,'') as pro_num_contrato,
                 sum(case when td.tdis_id=1 or td.tdis_id=7  then  dah.daho_total_horas else 0     end) as hora_clase,
                sum(case when td.tdis_id=2 then  tdis_num_semanas else 0 end) as hora_tutorias,
        sum(case when td.tdis_id=3 then  tdis_num_semanas else 0 end) as hora_investigacion,
                sum(case when td.tdis_id=4 then  tdis_num_semanas else 0 end) as hora_vinculacion,
        sum(case when td.tdis_id=5 then  0  end) as hora_administrativa,
        sum(case when td.tdis_id=6 then  daca_horas_otras_actividades else 0 end) as hora_otras_actividades,
                ddoc_horas,
                 sum(case when da.uaca_id = 1  then  dah.daho_total_horas else 0     end) as tercel_nivel,
                 sum(case when da.uaca_id = 2  then  dah.daho_total_horas else 0     end) as cuarto_nivel,
                0 as comun
                -- case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                -- case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id     
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname .        ".persona persona on profesor.per_id = persona.per_id 
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id
                INNER JOIN " . $con_academico->dbname . ".dedicacion_docente  dd on dd.ddoc_id  = profesor.ddoc_id
                LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where  da.daca_estado='1' and daca_estado_logico='1' and dc.dcab_estado_revision=2";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                if ($this->paca_id) {
                    $sql = $sql . " and da.paca_id =" . $this->paca_id;
                } else {
                    $sql = $sql . " and da.paca_id =0";
                }
                if ($this->pro_id) {
                    $sql = $sql . " and profesor.ddoc_id =" . $this->pro_id;
                }
            } else {
                $sql = $sql . " and da.paca_id =0";
            }
        }
        if ($tipo == 2) {

            /*if ($params['mod_id']) {
                $sql = $sql . " and da.mod_id =" . $params['mod_id'];
            }*/

            if ($params['paca_id']) {
                $sql = $sql . " and da.paca_id =" . $params['paca_id'];
            }
            if ($params['pro_id']) {
                $sql = $sql . " and profesor.ddoc_id =" . $params['pro_id'];
            }
            /*if ($params['tdis_id']) {
                $sql = $sql . " and da.tdis_id =" . $params['tdis_id'];
            }*/
        }
        $sql = $sql . "  group by codigo_ies,docente,no_cedula,tipo_identificacion,pro_num_contrato,ddoc_horas";
        Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        return $dataProvider;
    }

    public function getReportematrizdistributivoexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "da.paca_id = :paca_id AND ";
            }
            if ($arrFiltro['dedicacion'] != "" && $arrFiltro['dedicacion'] > 0) {
                $str_search .= "profesor.ddoc_id = :pro_id AND ";
            }
                     
        }

            $sql = "select '1050' as codigo_ies, IFNULL(CONCAT(ifnull(persona.per_pri_apellido,' '), ' ', ifnull(persona.per_seg_apellido,' '), ' ', ifnull(persona.per_pri_nombre,' '), ' ', ifnull(persona.per_seg_nombre, ' ')), '') as docente,
                case when per_cedula is null then 'Pasaporte' else 'Cédula' end as tipo_identificacion,
                IFNULL(pro_num_contrato,'') as pro_num_contrato,
                case when per_cedula is null then per_pasaporte else per_cedula end as no_cedula,
                 sum(case when td.tdis_id=1 or td.tdis_id=7  then  dah.daho_total_horas else 0     end) as hora_clase,
                sum(case when td.tdis_id=2 then  tdis_num_semanas else 0 end) as hora_tutorias,
        sum(case when td.tdis_id=3 then  tdis_num_semanas else 0 end) as hora_investigacion,
                sum(case when td.tdis_id=4 then  tdis_num_semanas else 0 end) as hora_vinculacion,
        sum(case when td.tdis_id=5 then  0  end) as hora_administrativa,
        sum(case when td.tdis_id=6 then  daca_horas_otras_actividades else 0 end) as hora_otras_actividades,
                ddoc_horas,
                 sum(case when da.uaca_id = 1  then  dah.daho_total_horas else 0     end) as tercel_nivel,
                 sum(case when da.uaca_id = 2  then  dah.daho_total_horas else 0     end) as cuarto_nivel,
                0 as comun
                -- case when td.tdis_id=7 then tdis_num_semanas else (pc.paca_semanas_periodo * case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as total_horas_dictar,
                -- case when da.tdis_id=7 then round(tdis_num_semanas/paca_semanas_periodo) else ( case  when dah.daho_total_horas is null then tdis_num_semanas else dah.daho_total_horas end) end as promedio
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".distributivo_cabecera dc on da.dcab_id=dc.dcab_id     
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname .        ".persona persona on profesor.per_id = persona.per_id 
                INNER JOIN " . $con_academico->dbname . ".periodo_academico pc on da.paca_id  = pc.paca_id and  pc.paca_activo='A'
                INNER JOIN " . $con_academico->dbname . ".tipo_distributivo td on td.tdis_id  = da.tdis_id
                INNER JOIN " . $con_academico->dbname . ".dedicacion_docente  dd on dd.ddoc_id  = profesor.ddoc_id
                LEFT JOIN " . $con_academico->dbname . ".distributivo_academico_horario dah on dah.daho_id  = da.daho_id    
                where  da.daca_estado='1' and daca_estado_logico='1' and dc.dcab_estado_revision=2
                group by codigo_ies,docente,no_cedula,tipo_identificacion,pro_num_contrato,ddoc_horas";
        
        
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }

            if ($arrFiltro['dedicacion'] != "" && $arrFiltro['dedicacion'] > 0) {
                $dedicacion = $arrFiltro["dedicacion"];
                $comando->bindParam(":pro_id", $dedicacion, \PDO::PARAM_INT);
            } 
            
            
        }
        $res = $comando->queryAll();

        \app\models\Utilities::putMessageLogFile('getListadoDistributivoexcel: '.$comando->getRawSql());
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    public function getListadoDistributivoMateriaProfresor($params = null, $onlyData = false, $tipo = 1) {
        $this->load($params);

        $var = "";
        $totalColum = 7;
        $return = array();
        $gridColumns = array(array('class' => 'kartik\grid\SerialColumn',
                'pageSummary' => 'Total',
                'pageSummaryOptions' => ['colspan' => 7],
                'header' => '',
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'contentOptions' => ['class' => 'kartik-sheet-style'],
            ), 'docente',
            'cod_und',
            'unidad_academica',
            'asignatura',
            'horario',
            'periodo',);
        if ($this->validate()) {
            if ($this->paca_id) {
                $model = PeriodoAcademico::findOne($this->paca_id);
                $dias = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado');
                $mes = array('01' => 'ENE_', '02' => 'FEB_', '03' => 'MAR_', '04' => 'ABR_', '05' => 'MAY_', '06' => 'JUN_', '07' => 'JUL_', '08' => 'AGO_', '09' => 'SEPT_', '10' => 'OCT_', '11' => 'NOV_', '12' => 'DIC_');
                $fechaInicio = strtotime($model->paca_fecha_inicio);
                $fechaFin = strtotime($model->paca_fecha_fin);

                for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {

                    if ($dias[date('N', strtotime(date("d-m-Y", $i)))] == "Viernes") {
                        $totalColum = $totalColum + 1;
                        $var = $var . "(case tdis_id when 1 
									then case when (daca_num_estudiantes_online between 0 and 10) then 2  
										when (daca_num_estudiantes_online between 11 and 20) then 3
                                                                                when (daca_num_estudiantes_online between 21 and 30) then 4 
                                                                                when (daca_num_estudiantes_online between 31 and 40) then 5
                                                                                when (daca_num_estudiantes_online >40) then 7
                                                                                end
								when 2 
									then daca_num_estudiantes_online 
								when 3 
									then daca_num_estudiantes_online 
								when 4 
									then daca_num_estudiantes_online 
                                                                 when 7
									then daca_num_estudiantes_online    
								end  ) as " . $mes[date("m", strtotime(date("d-m-Y", $i)))] . "" . date('d', strtotime(date("d-m-Y", $i))) . ",";

                        $gridColumns[] = array('attribute' => $mes[date("m", strtotime(date("d-m-Y", $i)))] . "" . date('d', strtotime(date("d-m-Y", $i))), 'pageSummary' => true, 'hAlign' => 'right', 'vAlign' => 'middle',);
                    }
                }
            }
        }
        $gridColumns[] = array('attribute' => 'total', 'class' => 'kartik\grid\FormulaColumn',
            'header' => 'Total',
            'vAlign' => 'middle',
            'format' => 'html',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
            'value' => function ($model, $key, $index, $widget) use ($totalColum) {
                //echo(print_r($index) . "-");
                $p = compact('model', 'key', 'index');
                $suma = 0;
                for ($i = 7; $i < $totalColum; $i++) {
                    $suma = $suma + $widget->col($i, $p);
                }
                return "<h5><span  style='background-color: red'> </span>  <code>" . $suma . '</code> </h5>';
                //return $suma;
            },);

        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $sql = "select  daca_id as id ,UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
               ifnull( uaca_nomenclatura,'') as cod_und,
                ifnull(uaca_nombre,'') as unidad_academica,
                ifnull(asi_nombre,'') as asignatura  ,
                ifnull(daho_descripcion,'') as horario,
                  ifnull(CONCAT(blq.baca_anio,' (',blq.baca_nombre,'-',sem.saca_nombre,')'),blq.baca_anio) as periodo," . $var .
                "tdis_id as tipo_asi" .
                " from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
                inner join " . $con_academico->dbname . ".asignatura asi on asi.asi_id = da.asi_id            
                inner join " . $con_academico->dbname . ".unidad_academica uc on uc.uaca_id = da.uaca_id
                left  join " . $con_academico->dbname . ".distributivo_academico_horario dah on  dah.daho_id= da.daho_id
                left  join " . $con_academico->dbname . ".periodo_academico AS pa ON da.paca_id = pa.paca_id
	        LEFT JOIN " . $con_academico->dbname . ".semestre_academico sem  ON sem.saca_id = pa.saca_id 
		 LEFT JOIN " . $con_academico->dbname . ".bloque_academico blq ON blq.baca_id = pa.baca_id
                where 1=1  and tdis_id is not null  ";

        if ($this->validate()) {
            if ($this->paca_id) {
                $sql = $sql . " and da.paca_id =" . $this->paca_id;
            } else {
                $sql = $sql . " and da.paca_id =0";
            }
            if ($this->mod_id) {
                $sql = $sql . " and da.mod_id =" . $this->mod_id;
            }

            if ($this->tdis_id) {
                $sql = $sql . " and da.tdis_id =" . $this->tdis_id;
            }
        }

        $sql = $sql . " order by docente";
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();
        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
        ]);
        $return[] = $gridColumns;
        $return[] = $dataProvider;
        return $return;
    }

    function search($params) {
        $query = DistributivoAcademico::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10 // in case you want a default pagesize
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'daca_id' => $this->daca_id,
        ]);

        $query->andFilterWhere([
            'paca_id' => $this->paca_id,
            'tdis_id' => $this->tdis_id,
            'mod_id' => $this->mod_id,
            'pro_id' => $this->pro_id,
            'daho_id' => $this->daho_id,
        ]);

//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

    public function getListadoMatriculadosporMateria($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        
        $sql = "select  distinct est.est_id, 
                ifnull(CONCAT(ifnull(per.per_pri_apellido,''), ' ', ifnull(per.per_seg_apellido,''), ' ', ifnull(per.per_pri_nombre,'')), '') as estudiante,
                per.per_cedula as cedula, 
                CONCAT(baca.baca_nombre, ' ', saca.saca_nombre, ' ', saca.saca_anio) as periodo,
                asi.asi_descripcion as materia,
                moda.mod_descripcion as modalidad,
                uaca.uaca_descripcion as unidad,
                pes.pes_carrera as carrera,
                ifnull(est.est_matricula,'') as n_matricula                
                from db_academico.registro_online as ron
                inner join db_academico.registro_online_item as roi on roi.ron_id=ron.ron_id 
                inner join db_asgard.persona as per on per.per_id = ron.per_id
                inner join db_academico.estudiante as est on est.per_id=per.per_id
                inner join db_academico.registro_pago_matricula as pm on ron.ron_id=pm.ron_id                 
                inner join db_academico.planificacion_estudiante as pes on pes.pes_id=ron.pes_id
                inner join db_academico.planificacion as pla on pla.pla_id = pes.pla_id
                inner join db_academico.malla_academica_detalle as mad on  mad.made_codigo_asignatura=roi.roi_materia_cod
                inner join db_academico.asignatura as asi on asi.asi_id = mad.asi_id               
                inner join db_academico.estudiante_carrera_programa ec on ec.est_id = est.est_id
                inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id = ec.meun_id                
                Inner Join db_academico.modalidad moda on moda.mod_id = meu.mod_id 
                Inner Join db_academico.unidad_academica uaca on uaca.uaca_id = meu.uaca_id               
                Inner Join db_academico.semestre_academico saca on saca.saca_id = pla.saca_id
                Inner Join db_academico.periodo_academico paca on paca.saca_id = saca.saca_id and paca.paca_id = pla.paca_id
                Inner Join db_academico.bloque_academico baca on (baca.baca_id = paca.baca_id and baca.baca_nombre = roi.roi_bloque)
                 where per.per_estado = 1 and per.per_estado_logico = 1
                    and est.est_estado = 1 and est.est_estado_logico = 1
                    and ron.ron_estado = 1 and ron.ron_estado_logico = 1
                    and roi.roi_estado = 1 and roi.roi_estado_logico = 1
                    and pla.paca_id = paca.paca_id and pm.rpm_estado_aprobacion = 1 
                    and pm.rpm_estado = 1 and pm.rpm_estado_logico = 1                  
                    and pes.pes_estado = 1 and pes.pes_estado_logico = 1
                    and asi.asi_estado = 1 and asi.asi_estado_logico = 1
                    and ec.ecpr_estado = 1 and ec.ecpr_estado_logico = 1
                    and meu.meun_estado = 1 and meu.meun_estado_logico = 1
                    and pla.pla_estado = 1 and pla.pla_estado_logico = 1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->paca_id) {
                    $sql = $sql . " and pla.paca_id =" . $this->paca_id;
                }

                if ($this->mod_id) {
                    $sql = $sql . " and meu.mod_id =" . $this->mod_id;
                }

                if ($this->asi_id) {
                    $sql = $sql . " and mad.asi_id =" . $this->asi_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['paca_id']) {
                $sql = $sql . " and pla.paca_id =" . $params['paca_id'];
            }

            if ($params['mod_id']) {
                $sql = $sql . " and meu.mod_id =" . $params['mod_id'];
            }

            
            if ($params['asi_id']) {
                $sql = $sql . " and mad.asi_id =" . $params['asi_id'];
            }
        }
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        return $dataProvider;
    }

    public function getListadoMatripormateriaexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "daca.uaca_id = :paca_id AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "daca.mod_id = :mod_id AND ";
            } 
            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $str_search .= "daca.asi_id = :asi_id AND ";
            }         
        }

            $sql = "select  distinct est.est_id, 
                ifnull(CONCAT(ifnull(per.per_pri_apellido,''), ' ', ifnull(per.per_seg_apellido,''), ' ', ifnull(per.per_pri_nombre,'')), '') as estudiante,
                per.per_cedula as cedula, 
                CONCAT(baca.baca_nombre, ' ', saca.saca_nombre, ' ', saca.saca_anio) as periodo,
                asi.asi_descripcion as materia,
                moda.mod_descripcion as modalidad,
                uaca.uaca_descripcion as unidad,
                pes.pes_carrera as carrera,
                ifnull(est.est_matricula,'') as n_matricula                
                from db_academico.registro_online as ron
                inner join db_academico.registro_online_item as roi on roi.ron_id=ron.ron_id 
                inner join db_asgard.persona as per on per.per_id = ron.per_id
                inner join db_academico.estudiante as est on est.per_id=per.per_id
                inner join db_academico.registro_pago_matricula as pm on ron.ron_id=pm.ron_id                 
                inner join db_academico.planificacion_estudiante as pes on pes.pes_id=ron.pes_id
                inner join db_academico.planificacion as pla on pla.pla_id = pes.pla_id
                inner join db_academico.malla_academica_detalle as mad on  mad.made_codigo_asignatura=roi.roi_materia_cod
                inner join db_academico.asignatura as asi on asi.asi_id = mad.asi_id               
                inner join db_academico.estudiante_carrera_programa ec on ec.est_id = est.est_id
                inner join db_academico.modalidad_estudio_unidad meu on meu.meun_id = ec.meun_id                
                Inner Join db_academico.modalidad moda on moda.mod_id = meu.mod_id 
                Inner Join db_academico.unidad_academica uaca on uaca.uaca_id = meu.uaca_id               
                Inner Join db_academico.semestre_academico saca on saca.saca_id = pla.saca_id
                Inner Join db_academico.periodo_academico paca on paca.saca_id = saca.saca_id and paca.paca_id = pla.paca_id
                Inner Join db_academico.bloque_academico baca on (baca.baca_id = paca.baca_id and baca.baca_nombre = roi.roi_bloque)
                 where per.per_estado = 1 and per.per_estado_logico = 1
                    and est.est_estado = 1 and est.est_estado_logico = 1
                    and ron.ron_estado = 1 and ron.ron_estado_logico = 1
                    and roi.roi_estado = 1 and roi.roi_estado_logico = 1
                    and pla.paca_id = paca.paca_id and pm.rpm_estado_aprobacion = 1 
                    and pm.rpm_estado = 1 and pm.rpm_estado_logico = 1                  
                    and pes.pes_estado = 1 and pes.pes_estado_logico = 1
                    and asi.asi_estado = 1 and asi.asi_estado_logico = 1
                    and ec.ecpr_estado = 1 and ec.ecpr_estado_logico = 1
                    and meu.meun_estado = 1 and meu.meun_estado_logico = 1
                    and pla.pla_estado = 1 and pla.pla_estado_logico = 1";
        
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }

            if ($arrFiltro['asignatura'] != "" && $arrFiltro['asignatura'] > 0) {
                $asignatura = $arrFiltro["asignatura"];
                $comando->bindParam(":asi_id", $asignatura, \PDO::PARAM_INT);
            } 
        }
        $res = $comando->queryAll();


        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'periodo', 
                    'estudiante',
                    'cedula',
                    'materia',
                    'unidad',
                    'modalidad',
                    'matricula',
                    'carrera'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    public function getListadoreportInscriptos($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $paca_id = 0;

        $sql = "select 
                CONCAT(per.per_pri_apellido,' ' ,per.per_pri_nombre) as nombres,
                per.per_cedula as cedula,
                per.per_correo as correo,
                per.per_celular as telefono,
                est.est_matricula as matricula,
                uaca.uaca_descripcion as unidad,
                moda.mod_descripcion as modalidad,
                eaca.eaca_descripcion as carrera
                FROM db_captacion.admitido adm 
                inner join db_captacion.interesado inte ON inte.int_id = adm.int_id
                Inner join db_asgard.persona per ON per.per_id = inte.per_id
                Inner join db_academico.estudiante est ON est.per_id = per.per_id
                Inner join db_academico.estudiante_carrera_programa ecpr ON ecpr.est_id = est.est_id
                Inner Join db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id
                Inner join db_academico.estudio_academico eaca ON eaca.eaca_id = meun.eaca_id
                Inner Join db_academico.modalidad moda on moda.mod_id = meun.mod_id
                inner join db_academico.distributivo_academico daca on daca.mod_id = moda.mod_id
                Inner Join db_academico.unidad_academica uaca on uaca.uaca_id = daca.uaca_id
                Inner Join db_academico.periodo_academico paca on paca.paca_id = daca.paca_id
                Inner Join db_academico.semestre_academico saca on saca.saca_id = paca.saca_id
                Inner Join db_academico.bloque_academico baca on baca.baca_id = paca.saca_id
                where adm.adm_fecha_creacion between '2018-07-08 05:00:00' and '2019-09-22 04:59:59'
                and daca.daca_estado = 1 and daca.daca_estado_logico = 1
                and adm.adm_estado = 1 and adm.adm_estado_logico = 1
                and inte.int_estado = 1 and inte.int_estado_logico = 1
                and est.est_estado = 1 and est.est_estado_logico = 1
                and paca.paca_estado = 1 and paca.paca_estado_logico = 1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                if ($this->paca_id) {
                    $sql = $sql . " and daca.paca_id =" . $this->paca_id;
                } 
                if ($this->mod_id) {
                    $sql = $sql . " and daca.mod_id =" . $this->mod_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['paca_id']) {
                $sql = $sql . " and daca.paca_id =" . $params['paca_id'];
            }
            if ($params['mod_id']) {
                $sql = $sql . " and daca.mod_id =" . $params['mod_id'];
            }
        }
       
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['
                '],
            ],
        ]);

        return $dataProvider;
    }

    public function getListadoReporteinscritosexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $str_search .= "pla.paca_id = :paca_id AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "pla.mod_id = :mod_id AND ";
            }         
        }

            $sql = "select 
                CONCAT(per.per_pri_apellido,' ' ,per.per_pri_nombre) as nombres,
                per.per_cedula as cedula,
                per.per_correo as correo,
                per.per_celular as telefono,
                est.est_matricula as matricula,
                uaca.uaca_descripcion as unidad,
                moda.mod_descripcion as modalidad,
                eaca.eaca_descripcion as carrera
                FROM db_captacion.admitido adm 
                inner join db_captacion.interesado inte ON inte.int_id = adm.int_id
                Inner join db_asgard.persona per ON per.per_id = inte.per_id
                Inner join db_academico.estudiante est ON est.per_id = per.per_id
                Inner join db_academico.estudiante_carrera_programa ecpr ON ecpr.est_id = est.est_id
                Inner Join db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id
                Inner join db_academico.estudio_academico eaca ON eaca.eaca_id = meun.eaca_id
                Inner Join db_academico.modalidad moda on moda.mod_id = meun.mod_id
                inner join db_academico.distributivo_academico daca on daca.mod_id = moda.mod_id
                Inner Join db_academico.unidad_academica uaca on uaca.uaca_id = daca.uaca_id
                Inner Join db_academico.periodo_academico paca on paca.paca_id = daca.paca_id
                Inner Join db_academico.semestre_academico saca on saca.saca_id = paca.saca_id
                Inner Join db_academico.bloque_academico baca on baca.baca_id = paca.saca_id
                where adm.adm_fecha_creacion between '2018-07-08 05:00:00' and '2019-09-22 04:59:59'
                and daca.daca_estado = 1 and daca.daca_estado_logico = 1
                and adm.adm_estado = 1 and adm.adm_estado_logico = 1
                and inte.int_estado = 1 and inte.int_estado_logico = 1
                and est.est_estado = 1 and est.est_estado_logico = 1
                and paca.paca_estado = 1 and paca.paca_estado_logico = 1";
        
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['periodo'] != "" && $arrFiltro['periodo'] > 0) {
                $periodo = $arrFiltro["periodo"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }
                    
        }
        $res = $comando->queryAll();


        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'nombres', 
                    'cedula',
                    'correo',
                    'telefono',
                    'matricula',
                    'unidad',
                    'modalidad',
                    'carrera'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

}