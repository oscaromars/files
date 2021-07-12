<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
/**
 * Description of Reporte
 *
 * @author root
 */
class Reporte extends \yii\db\ActiveRecord {

    /**
     * Function consulta el nombre de modalidad
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarActividadporOportunidad($data) {
        $con = \Yii::$app->db_crm;  
        
        $sql = "SELECT LPAD(B.opo_id,9,'0') opo_id,DATE(A.bact_fecha_registro) Fecha,G.emp_razon_social,
            CONCAT(C.pges_pri_nombre, ' ', ifnull(C.pges_seg_nombre,' ')) Nombres,
            CONCAT(C.pges_pri_apellido, ' ', ifnull(C.pges_seg_apellido,' ')) Apellidos, 
            H.uaca_nombre,F.eopo_nombre,E.oact_nombre,A.bact_descripcion 
                        FROM " . $con->dbname . ".bitacora_actividades A
                                INNER JOIN (" . $con->dbname . ".oportunidad B
                                                INNER JOIN " . $con->dbname . ".persona_gestion C
                                                        ON B.pges_id=C.pges_id
                                                INNER JOIN " . yii::$app->db_asgard->dbname . ".empresa G
							ON G.emp_id=B.emp_id
                                                INNER JOIN " . yii::$app->db_academico->dbname . ".unidad_academica H
                                                        ON H.uaca_id=B.uaca_id)
                                        ON A.opo_id=B.opo_id
                                INNER JOIN " . $con->dbname . ".observacion_actividades E						
                                                        ON E.oact_id=A.oact_id
                                INNER JOIN " . $con->dbname . ".estado_oportunidad F
                                                        ON F.eopo_id=A.eopo_id
                WHERE A.bact_estado=1  ";
        $sql .= ($data['f_ini'] <> '' && $data['f_fin'] <> '' ) ? "AND DATE(A.bact_fecha_registro) BETWEEN :f_ini AND :f_fin " : " ";
        $sql .= " ORDER BY A.bact_fecha_registro; "; //#AND B.opo_id=52;
        $comando = $con->createCommand($sql);
        //Utilities::putMessageLogFile($sql);
        if ($data['f_ini'] <> '' && $data['f_fin'] <> '') {
            $comando->bindParam(":f_ini", date("Y-m-d", strtotime($data['f_ini'])), \PDO::PARAM_STR);
            $comando->bindParam(":f_fin", date("Y-m-d", strtotime($data['f_fin'])), \PDO::PARAM_STR);
        }
        return $comando->queryAll();
    }
    /**
     * Function consulta el nombre de modalidad
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarOportunidadProximaAten($arrFiltro = array()) {
        $con = \Yii::$app->db_crm;        
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $str_search = "";
        $estado =1 ;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {

            if ($arrFiltro['search_dni'] != "") {
                $str_search .= "(pg.pges_cedula like :search) and ";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "bact.bact_fecha_registro >= :fec_ini AND ";
                $str_search .= "bact.bact_fecha_registro <= :fec_fin AND ";
            }
            if ($arrFiltro['empresa_id'] > 0) {
                $str_search .= " op.emp_id = :empresa  AND";
            }
        }
        $sql = "
               SELECT  LPAD(op.opo_codigo,9,'0') Codigo,
                        date_format(bact.bact_fecha_registro, '%Y-%m-%d') F_Atencion,
                        date_format(bact.bact_fecha_registro, '%H:%i') H_Atencion,
                        date_format(ifnull(bact.bact_fecha_proxima_atencion, ' '), '%Y-%m-%d') F_Prox_At,
                        date_format(ifnull(bact.bact_fecha_proxima_atencion, ' '), '%H:%i') H_Prox_At,
                        emp.emp_razon_social,
                        pg.pges_cedula,
                        CONCAT(ifnull(pg.pges_pri_nombre,''), ' ', ifnull(pg.pges_seg_nombre,''), ' ', ifnull(pg.pges_pri_apellido,''), ' ', ifnull(pg.pges_seg_apellido,'')) Nombres_Completos,                        	
                        ccan.ccan_nombre canal_contacto,
                        eop.eopo_nombre as Estado,
                        oact.oact_nombre,
                        uac.uaca_nombre as Unidad,
                        mo.mod_nombre as Modalidad,
                        ea.eaca_nombre as Carrera,
                        CONCAT(per.per_pri_nombre, ' ', ifnull(per.per_pri_apellido,' ')) Agente
                FROM " . $con->dbname . ".oportunidad op
                INNER JOIN " . $con->dbname . ".persona_gestion pg ON pg.pges_id=op.pges_id
                inner join " . $con->dbname . ".conocimiento_canal ccan on ccan.ccan_id=op.ccan_id
                INNER JOIN " . $con1->dbname . ".empresa emp ON emp.emp_id=op.emp_id
                INNER JOIN " . $con2->dbname . ".unidad_academica uac ON uac.uaca_id=op.uaca_id
                INNER JOIN " . $con->dbname . ".estado_oportunidad eop ON eop.eopo_id=op.eopo_id
                INNER JOIN " . $con->dbname . ".bitacora_actividades bact ON bact.opo_id=op.opo_id
                INNER JOIN " . $con1->dbname . ".usuario usu on usu.usu_id = bact.bact_usuario
                INNER JOIN " . $con1->dbname . ".persona per on per.per_id = usu.per_id
                INNER JOIN " . $con->dbname . ".observacion_actividades as oact on oact.oact_id=bact.oact_id
                INNER JOIN " . $con2->dbname . ".estudio_academico ea on ea.eaca_id = op.eaca_id                    
                INNER JOIN " . $con2->dbname . ".modalidad mo on mo.mod_id = op.mod_id
                WHERE $str_search 
                    op.opo_estado = :estado and op.opo_estado_logico = :estado and 
                    pg.pges_estado = :estado and pg.pges_estado_logico = :estado and
                    ccan.ccan_estado = :estado and ccan.ccan_estado_logico = :estado and 
                    emp.emp_estado = :estado and emp.emp_estado_logico = :estado and
                    uac.uaca_estado = :estado and uac.uaca_estado_logico = :estado and
                    eop.eopo_estado = :estado and eop.eopo_estado_logico = :estado and
                    bact.bact_estado = :estado and bact.bact_estado_logico = :estado  and
                    per.per_estado = :estado and per.per_estado_logico = :estado and
                    oact.oact_estado = :estado and oact.oact_estado_logico = :estado and
                    ea.eaca_estado = :estado and ea.eaca_estado_logico = :estado and
                    mo.mod_estado = :estado and mo.mod_estado_logico = :estado
                ";
        $sql .= " ORDER BY op.opo_codigo, bact.bact_fecha_registro";
        $comando = $con->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search_dni'] != "") {
                $search_cond = "%" . $arrFiltro["search_dni"] . "%";
                $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            }
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['empresa_id'] > "0") {
                $empresa = $arrFiltro['empresa_id'];
                $comando->bindParam(":empresa", $empresa, \PDO::PARAM_INT);
            }
        }
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        return $comando->queryAll();
    }
    /**
     * Function consulta el nombre de modalidad
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>;
     * @modificado por Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarAspirantesPendientes($arrFiltro = array()) {
        $con = \Yii::$app->db_captacion;
        $con1 = \Yii::$app->db_asgard;
        $con2 = \Yii::$app->db_academico;
        $con3 = \Yii::$app->db_facturacion;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search .= "(per.per_cedula like :search) and ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "sins.sins_fecha_solicitud >= :fec_ini AND ";
                $str_search .= "sins.sins_fecha_solicitud <= :fec_fin AND ";
            }
        }
        $sql = "
                    select
                        ifnull(per.per_cedula,per.per_pasaporte) as DNI,                    
                        DATE(sins.sins_fecha_solicitud) as fecha_solicitud,
                        sins.num_solicitud as num_solicitud,
                        concat(ifnull(per.per_pri_nombre,''),' ',ifnull(per.per_seg_nombre,'')) as nombres,                    
                        concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_seg_apellido,'')) as apellidos,                    
                        emp.emp_nombre_comercial as empresa,                                         
                        IFNULL(uaca.uaca_nombre,'') uaca_nombre,
                        case when sins.uaca_id<3 then (select eaca.eaca_nombre from " . $con2->dbname . ".estudio_academico eaca inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.eaca_id = eaca.eaca_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)
                            else (select mes.mest_nombre from " . $con2->dbname . ".modulo_estudio mes inner join " . $con->dbname . ".solicitud_inscripcion sins on sins.mest_id = mes.mest_id WHERE int_id = inte.int_id ORDER BY sins_fecha_solicitud desc LIMIT 1)				
                        end carrera,
                        ifnull(moda.mod_nombre,'') mod_nombre,
                        CONCAT(ifnull(pag.per_pri_nombre,' '), ' ', ifnull(pag.per_pri_apellido,' ')) Agente,
                        case sins.rsin_id
                            when 1 then 'Pendiente'
                            when 2 then 'Aprobado'
                            when 3 then 'Pre-Aprobado'
                            else 'Sin Estado'
                        end estado_solicitud,
                        case
                            when
                                ifnull((
                                    select 
                                        count(sdoc.sdoc_id) as num_documentos
                                    from 
                                    " . $con->dbname . ".interesado as inter
                                        join " . $con->dbname . ".solicitud_inscripcion as sins on sins.int_id=inter.int_id
                                        join " . $con->dbname . ".solicitudins_documento as sdoc on sdoc.sins_id=sins.sins_id
                                    where 
                                        inter.int_id=inte.int_id 
                                    group by inter.int_id 
                                ),0)>0 then 'Documentos Subidos'
                            else
                                'Pendiente'
                        end Estado_Documentos,
                        case
                            WHEN ifnull((SELECT opag_estado_pago FROM " . $con3->dbname . ".orden_pago op WHERE op.sins_id = sins.sins_id and op.opag_estado = '1'),'N') = 'N' THEN 'No generado'
                            WHEN (SELECT opag_estado_pago FROM " . $con3->dbname . ".orden_pago op WHERE op.sins_id = sins.sins_id and op.opag_estado = '1') = 'P' THEN 'Pendiente'
                            ELSE 'Pagado'
                        end Estado_Pago
                    from 
                    " . $con->dbname . ".interesado inte                        
                        join " . $con1->dbname . ".persona as per on inte.per_id=per.per_id
                        join " . $con->dbname . ".interesado_empresa as iemp on iemp.int_id=inte.int_id
                        join " . $con->dbname . ".solicitud_inscripcion as sins on sins.int_id=inte.int_id
                        join " . $con1->dbname . ".persona pag on pag.per_id = sins.sins_usuario_ingreso
                        join " . $con2->dbname . ".unidad_academica as uaca on uaca.uaca_id=sins.uaca_id
                        join " . $con2->dbname . ".modalidad as moda on moda.mod_id=sins.mod_id
                        join " . $con1->dbname . ".empresa as emp on emp.emp_id=iemp.emp_id
                        left join " . $con->dbname . ".admitido admit on admit.int_id=inte.int_id        
                    where
                        $str_search
                        inte.int_estado_logico=:estado AND
                        inte.int_estado=:estado AND                    
                        per.per_estado_logico=:estado AND						
                        per.per_estado=:estado AND
                        iemp.iemp_estado_logico=:estado AND						
                        iemp.iemp_estado=:estado AND
                        emp.emp_estado_logico=:estado AND						
                        emp.emp_estado=:estado
                        order by inte.int_fecha_creacion desc
                ";
        $comando = $con->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search_dni"] . "%";
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
        }
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    
    public function consultarInscriptos($anio) {
        $con = \Yii::$app->db_crm; 
        $con1 = \Yii::$app->db_captacion;
       
        //INNER JOIN  " . $con1->dbname . ".convenio_empresa B ON B.cemp_id=A.cemp_id
        $sql = "SELECT COUNT(*) CANT,A.cemp_id,
                IFNULL((SELECT IFNULL(B.cemp_nombre,'NO')  FROM " . $con1->dbname . ".convenio_empresa B WHERE B.cemp_estado=1 AND B.cemp_estado_logico=1 AND B.cemp_id=A.cemp_id),'NO CONVENIO') cemp_nombre,
                A.imae_fecha_pago,MONTH(A.imae_fecha_pago) MES
                    FROM " . $con->dbname . ".inscrito_maestria A			
                WHERE A.imae_estado=1 AND A.imae_estado_logico=1 AND YEAR(A.imae_fecha_pago)=:anio
                    GROUP BY A.cemp_id,MONTH(A.imae_fecha_pago) ORDER BY MONTH(A.imae_fecha_pago),A.cemp_id ASC;";
        
        $comando = $con->createCommand($sql);
        //Utilities::putMessageLogFile($sql);
        $comando->bindParam(":anio", $anio, \PDO::PARAM_STR);
        return $comando->queryAll();
    }
    
    public function getAllConvenios() {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT emp_id as id,emp_razon_social as value from " . $con->dbname . ".empresa WHERE emp_estado_logico=1 AND emp_estado=1 ORDER BY id asc";  
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }
    
    
    
    public function getAllAnios() {
        $con = \Yii::$app->db_crm;
        $sql = "SELECT DISTINCT(YEAR(imae_fecha_inscripcion)) anio
                    FROM " . $con->dbname . ".inscrito_maestria where imae_estado=1 and imae_estado_logico=1;";  
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }
    
    public function getAllConvenioEmpresa() {
        $con = \Yii::$app->db_captacion;
        $sql = "SELECT B.cemp_nombre  FROM " . $con->dbname . ".convenio_empresa B "
                . " WHERE B.cemp_estado=1 AND B.cemp_estado_logico=1 "; 
        $comando = $con->createCommand($sql);
        return $comando->queryAll();
    }

    public function consultarMallasacademicas($arrFiltro = array(), $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $estado = 1;
        //$str_search = '';

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            \app\models\Utilities::putMessageLogFile('unidad filtro: '. $arrFiltro['unidad']);
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "uaca.uaca_id = :unidad AND ";
            }
            \app\models\Utilities::putMessageLogFile('modalidad filtro: '. $arrFiltro['modalidad']);
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "moda.mod_id = :modalidad AND ";
            } 
            \app\models\Utilities::putMessageLogFile('carrera filtro: '. $arrFiltro['carrera']);
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= "eaca.eaca_id = :carrera AND ";
            }
            \app\models\Utilities::putMessageLogFile('malla filtro: '. $arrFiltro['malla']);
            if ($arrFiltro['malla'] != "" && $arrFiltro['malla'] > 0) {
                $str_search .= "mac.maca_id = :malla AND ";
            }
        }
       
        $sql = "Select concat(mac.maca_codigo,' - ',mac.maca_nombre) AS malla, 
                    a.asi_nombre as asignatura,
                    uaca.uaca_nombre as unidad,
                    moda.mod_nombre as modalidad,
                    d.made_semestre as semestre,
                    d.made_credito as credito,
                    u.uest_nombre as unidad_estudio,       
                    f.fmac_nombre as formacion_malla_academica,
                    ifnull(asi.asi_nombre,'') as materia_requisito,
                    eaca.eaca_nombre as carrera
              FROM db_academico.modalidad_estudio_unidad meu  
                  inner join db_academico.unidad_academica uaca on uaca.uaca_id = meu.uaca_id
                  inner join db_academico.modalidad moda on moda.mod_id = meu.mod_id
                  Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meu.eaca_id
                  INNER JOIN db_academico.malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
                  INNER JOIN db_academico.malla_academica mac ON mac.maca_id = mum.maca_id 
                  inner join db_academico.malla_academica_detalle d on d.maca_id = mac.maca_id
                  inner join db_academico.asignatura a on a.asi_id = d.asi_id
                  inner join db_academico.unidad_estudio u on u.uest_id = d.uest_id
                  inner join db_academico.nivel_estudio n on n.nest_id = d.nest_id
                  inner join db_academico.formacion_malla_academica f on f.fmac_id = d.fmac_id
                  left join db_academico.asignatura asi on asi.asi_id = d.made_asi_requisito
               WHERE  
                      $str_search
                      meu.meun_estado_logico = :estado AND meu.meun_estado = :estado AND
                      uaca.uaca_estado_logico = :estado AND uaca.uaca_estado = :estado AND
                      moda.mod_estado_logico = :estado AND moda.mod_estado = :estado AND
                      eaca.eaca_estado_logico = :estado AND eaca.eaca_estado = :estado AND
                      mum.mumo_estado_logico = :estado AND mum.mumo_estado = :estado AND
                      mac.maca_estado_logico = :estado AND mac.maca_estado = :estado AND
                      d.made_estado_logico = :estado AND d.made_estado = :estado";

        Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }

            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $carrera = $arrFiltro["carrera"];
                $comando->bindParam(":carrera", $carrera, \PDO::PARAM_INT);
            }

            if ($arrFiltro['malla'] != "" && $arrFiltro['malla'] > 0) {
                $malla = $arrFiltro["malla"];
                $comando->bindParam(":malla", $malla, \PDO::PARAM_INT);
            }
        }

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

    public function getListadoPromedio($arrFiltro = array(), $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            \app\models\Utilities::putMessageLogFile('estudiante filtro: '. $arrFiltro['estudiante']);
            if ($arrFiltro['estudiante'] != "" && $arrFiltro['estudiante'] > 0) {
                $str_search .= "est.per_id = " . $arrFiltro['estudiante'] . " AND ";
            }
        }

        $sql = "SELECT distinct ifnull(CONCAT(per.per_pri_apellido,' ' ,per.per_seg_apellido,' ' ,per.per_pri_nombre),'') as estudiante, 
                        eaca.eaca_nombre as carrera, 
                        enac.enac_asig_estado as estado_nota, 
                        asi.asi_descripcion as asignatura, 
                        maca.maca_nombre as malla,
                        ifnull((pmac.pmac_nota),'') as promedio
                FROM db_academico.estudiante_carrera_programa ecpr
                INNER JOIN db_academico.estudiante est ON est.est_id = ecpr.est_id
                INNER JOIN db_asgard.persona per ON per.per_id = est.per_id
                INNER JOIN db_academico.malla_academico_estudiante maes ON maes.per_id = per.per_id
                INNER JOIN db_academico.promedio_malla_academico pmac on pmac.maes_id = maes.maes_id
                INNER JOIN db_academico.estado_nota_academico enac on enac.enac_id = pmac.enac_id
                INNER JOIN db_academico.asignatura asi on asi.asi_id = maes.asi_id
                INNER JOIN db_academico.malla_academica maca on maca.maca_id = maes.maca_id
                INNER JOIN db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id
                INNER JOIN db_academico.estudio_academico eaca on eaca.eaca_id = meun.eaca_id
                WHERE $str_search
                ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND est.est_estado = 1 AND est.est_estado_logico = 1
                AND per.per_estado = 1 AND per.per_estado_logico = 1 
                AND maes.maes_estado = 1 AND maes.maes_estado_logico = 1
                AND pmac.pmac_estado = 1 AND pmac.pmac_estado_logico = 1
                AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1";

        Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $res = $comando->queryAll();

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            
            if ($arrFiltro['estudiante'] != "" && $arrFiltro['estudiante'] > 0) {
                $estudiante = $arrFiltro["estudiante"];
                $comando->bindParam(':estudiante', $estudiante, \PDO::PARAM_INT);
            }
        }

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

}
