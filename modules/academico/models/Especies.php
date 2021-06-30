<?php

namespace app\modules\academico\models;

use yii\base\Exception;
use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Especies
 *
 * @author byron Villacreses
 */
class Especies extends \yii\db\ActiveRecord {

    //put your code here

    public function recuperarIdsEstudiente($per_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT A.est_id FROM " . $con->dbname . ".estudiante A
                    WHERE A.est_estado=1 AND A.est_estado_logico=1 AND A.per_id=:per_id;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 0; //en caso de que existe problema o no retorne nada tiene 1 por defecto
        return $rawData;
    }

    public function recuperarIdsResponsable($uaca_id, $mod_id) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT resp_id FROM " . $con->dbname . ".responsable_especie
                    WHERE resp_estado=1 AND resp_estado_logico=1
                        AND uaca_id=:uaca_id AND mod_id=:mod_id;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $rawData = $comando->queryScalar();
        if ($rawData === false)
            return 1; //en caso de que existe problema o no retorne nada tiene 1 por defecto
        return $rawData;
    }

    public function consultaDatosEstudiante($id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT A.est_id, B.per_id Ids,B.per_pri_nombre,B.per_seg_nombre,B.per_pri_apellido,
                            B.per_seg_apellido,B.per_cedula, B.per_correo, D.uaca_id, D.mod_id, D.eaca_id, A.est_matricula, A.est_categoria
                    FROM " . $con->dbname . ".estudiante A
                            INNER JOIN " . $con1->dbname . ".persona B ON A.per_id=B.per_id
                            INNER JOIN " . $con->dbname . ".estudiante_carrera_programa C ON C.est_id = A.est_id
                            INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad D ON D.meun_id = C.meun_id
                WHERE A.est_estado=:estado AND A.est_estado_logico=:estado AND A.per_id=:Ids;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":Ids", $id, \PDO::PARAM_INT);
        $rawData = $comando->queryOne();
        return $rawData;
    }

    public static function getSolicitudesAlumnos($est_id, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_facturacion;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search = "AND (P.per_pri_nombre like :search OR ";
                $str_search .= "P.per_seg_nombre like :search OR ";
                $str_search .= "P.per_pri_apellido like :search ) ";
            }
            if ($arrFiltro['f_pago'] != "" && $arrFiltro['f_pago'] != "0") {
                $str_search .= " AND A.fpag_id= :fpag_id  ";
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " AND A.csol_fecha_creacion >= :fec_ini AND A.csol_fecha_creacion <= :fec_fin ";
            }
            if ($arrFiltro['f_estado'] != "" && $arrFiltro['f_estado'] != "0") {
                $str_search .= " AND A.csol_estado_aprobacion = :estado_aprobacion ";
            }
        }
        if ($est_id > "0") {
            $estudiante = " AND A.est_id=:est_id  ";
        }
        $sql = "SELECT A.est_id, lpad(ifnull(A.csol_id,0),9,'0') csol_id,A.empid,B.uaca_nombre,C.mod_nombre,D.fpag_nombre,date(A.csol_fecha_creacion) csol_fecha_solicitud,
                    A.csol_estado_aprobacion,A.csol_total, A.csol_estado_aprobacion, A.csol_observacion, concat(P.per_pri_nombre, ' ', P.per_pri_apellido) as nombre
                    FROM " . $con->dbname . ".cabecera_solicitud A
                    INNER JOIN " . $con->dbname . ".unidad_academica B ON B.uaca_id=A.uaca_id
                    INNER JOIN " . $con->dbname . ".modalidad C ON C.mod_id=A.mod_id
                    INNER JOIN " . $con1->dbname . ".forma_pago D ON D.fpag_id=A.fpag_id
                    INNER JOIN " . $con->dbname . ".estudiante E ON E.est_id=A.est_id
                    INNER JOIN " . $con2->dbname . ".persona P ON P.per_id=E.per_id
                WHERE  A.csol_estado=:estado AND A.csol_estado_logico=:estado $estudiante  $str_search  ORDER BY A.csol_id DESC;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if ($est_id > "0") {
            $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        }
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            }
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $forma_pago = $arrFiltro['f_pago'];
            $forma_estado = $arrFiltro['f_estado'];
            if ($forma_pago != "" && $arrFiltro['f_pago'] != "0") {
                $comando->bindParam(":fpag_id", $forma_pago, \PDO::PARAM_INT);
            }
            if ($forma_estado != "" && $arrFiltro['f_estado'] != "0") {
                $comando->bindParam(":estado_aprobacion", $forma_estado, \PDO::PARAM_INT);
            }
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
        }
        $resultData = $comando->queryAll();
        //Utilities::putMessageLogFile($resultData);
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'csol_id',
                    'csol_fecha_solicitud',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    public static function getTramite($uaca_id) {
        $estado = 1;
        $con = \Yii::$app->db_academico;
        $sql = "SELECT tra_id id,tra_nombre name
                    FROM " . $con->dbname . ".tramite
                WHERE uaca_id =:uaca_id AND tra_estado=:estado AND tra_estado_logico=:estado; ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public static function getFormaPago() {
        $con = \Yii::$app->db_facturacion;
        $sql = "SELECT fpag_id Ids,fpag_nombre Nombre
                    FROM " . $con->dbname . ".forma_pago
                WHERE fpag_estado=1 AND fpag_estado_logico=1 AND fpag_id IN(4,5,6);";
        $comando = $con->createCommand($sql);
        //$comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public static function getTramiteEspecie($Ids) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT esp_id id,esp_rubro name
                    FROM " . $con->dbname . ".especies
                WHERE esp_estado=1 AND esp_estado_logico=1 AND tra_id=:tra_id order by name asc;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":tra_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public function getDataEspecie($Ids) {
        $estado = 1;
        $con = \Yii::$app->db_academico;
        $sql = "SELECT A.esp_id,A.esp_valor,A.esp_emision_certificado,A.esp_dia_vigencia,
		concat(C.uaca_nomenclatura,tra_nomenclatura,lpad(ifnull(A.esp_codigo,0),3,'0')) codigo
                    FROM " . $con->dbname . ".especies A
			INNER JOIN (" . $con->dbname . ".tramite B
                            INNER JOIN " . $con->dbname . ".unidad_academica C ON B.uaca_id=C.uaca_id)
			ON A.tra_id=B.tra_id
                WHERE A.esp_estado=:estado AND A.esp_estado_logico=:estado AND A.esp_id=:esp_id; ";
        /* $sql = "SELECT esp_id,esp_valor,esp_emision_certificado,esp_dia_vigencia
          FROM " . $con->dbname . ".especies
          WHERE esp_estado=1 AND esp_estado_logico=1 AND esp_id=:esp_id;"; */
        $comando = $con->createCommand($sql);
        $comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function insertarLista($dts_Cab, $dts_Det) {
        $arroout = array();
        $con = \Yii::$app->db_academico;
        $trans = $con->beginTransaction();
        try {
            // \app\models\Utilities::putMessageLogFile($dts_Cab);
            $this->InsertarCabLista($con, $dts_Cab);
            //$idCab=$con->getLastInsertID();//IDS de la Persona
            $idCab = $con->getLastInsertID($con->dbname . '.cabecera_solicitud');
            $this->InsertarDetLista($con, $dts_Det, $idCab);

            $trans->commit();
            $con->close();
            //RETORNA DATOS
            //$arroout["ids"]= $ftem_id;
            $arroout["status"] = true;
            //$arroout["secuencial"]= $doc_numero;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            throw $e;
            $arroout["status"] = false;
            return $arroout;
        }
    }

    private function InsertarCabLista($con, $data) {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $sql = "INSERT INTO " . $con->dbname . ".cabecera_solicitud
                (empid,est_id,uaca_id,mod_id,fpag_id,csol_total,csol_estado_aprobacion,csol_usuario_ingreso,csol_estado,csol_fecha_creacion,csol_estado_logico)VALUES
                (:empid,:est_id,:uaca_id,:mod_id,:fpag_id,:csol_total,1,:csol_usuario_ingreso,1,CURRENT_TIMESTAMP(),1);";
        $command = $con->createCommand($sql);
        $command->bindParam(":empid", $data['empid'], \PDO::PARAM_INT);
        $command->bindParam(":est_id", $data['est_id'], \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $data['uaca_id'], \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $data['mod_id'], \PDO::PARAM_INT);
        $command->bindParam(":fpag_id", $data['fpag_id'], \PDO::PARAM_INT);
        $command->bindParam(":csol_total", $data['csol_total'], \PDO::PARAM_STR);
        $command->bindParam(":csol_usuario_ingreso", $usu_id, \PDO::PARAM_INT);
        $command->execute();
    }

    private function InsertarDetLista($con, $dts_Det, $idCab) {
        for ($i = 0; $i < sizeof($dts_Det); $i++) {
            $dts_Det[$i]['dsol_usuario_ingreso'] = @Yii::$app->session->get("PB_iduser");
            //$dts_Det[$i]['est_id'] = 1;
            $sql = "INSERT INTO " . $con->dbname . ".detalle_solicitud
                        (csol_id,tra_id,esp_id,est_id,dsol_cantidad,dsol_valor,dsol_total,dsol_observacion,dsol_archivo_extra,
                        dsol_usuario_ingreso,dsol_estado,dsol_fecha_creacion,dsol_estado_logico)
                    VALUES
                        (:csol_id,:tra_id,:esp_id,:est_id,:dsol_cantidad,:dsol_valor,:dsol_total,:dsol_observacion,:dsol_archivo_extra,
                        :dsol_usuario_ingreso,1,CURRENT_TIMESTAMP(),1);";
            $command = $con->createCommand($sql);
            $command->bindParam(":csol_id", $idCab, \PDO::PARAM_INT);
            $command->bindParam(":tra_id", $dts_Det[$i]['tra_id'], \PDO::PARAM_INT);
            $command->bindParam(":esp_id", $dts_Det[$i]['esp_id'], \PDO::PARAM_INT);
            $command->bindParam(":est_id", $dts_Det[$i]['est_id'], \PDO::PARAM_INT);
            $command->bindParam(":dsol_cantidad", $dts_Det[$i]['dsol_cantidad'], \PDO::PARAM_INT);
            $command->bindParam(":dsol_valor", $dts_Det[$i]['dsol_valor'], \PDO::PARAM_STR);
            $command->bindParam(":dsol_total", $dts_Det[$i]['dsol_total'], \PDO::PARAM_STR);
            $command->bindParam(":dsol_observacion", ucfirst(mb_strtolower($dts_Det[$i]['dsol_observacion'], 'UTF-8')), \PDO::PARAM_STR);
            $command->bindParam(":dsol_archivo_extra", $dts_Det[$i]['dsol_archivo_extra'], \PDO::PARAM_STR);
            $command->bindParam(":dsol_usuario_ingreso", $dts_Det[$i]['dsol_usuario_ingreso'], \PDO::PARAM_INT);
            $command->execute();
        }
    }

    public function consultarCabSolicitud($Ids) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT A.* FROM " . $con->dbname . ".cabecera_solicitud A
                    WHERE  A.csol_estado=1 AND A.csol_estado_logico=1 AND A.csol_id= :csol_id;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":csol_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public function consultarDetSolicitud($Ids) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT A.*,C.tra_nombre,B.esp_rubro FROM " . $con->dbname . ".detalle_solicitud A
			INNER JOIN " . $con->dbname . ".especies B ON A.esp_id=B.esp_id
			INNER JOIN " . $con->dbname . ".tramite C ON A.tra_id=C.tra_id
		WHERE A.dsol_estado=1 AND A.dsol_estado_logico=1 AND A.csol_id=:csol_id; ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":csol_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    public function CargarArchivo($fname, $csol_id) {
        $arroout = array();
        $con = \Yii::$app->db_academico;
        $trans = $con->beginTransaction();
        try {
            $path = Yii::$app->basePath . Yii::$app->params['documentFolder'] . "especies/" . $fname;
            $path = $fname;
            $sql = "UPDATE " . $con->dbname . ".cabecera_solicitud "
                    . "SET csol_ruta_archivo_pago=:csol_ruta_archivo_pago,csol_fecha_modificacion=CURRENT_TIMESTAMP() WHERE csol_id=:csol_id";

            $command = $con->createCommand($sql);
            $command->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);
            $command->bindParam(":csol_ruta_archivo_pago", $path, \PDO::PARAM_STR);
            $command->execute();

            $trans->commit();
            $con->close();
            //RETORNA DATOS
            $arroout["status"] = true;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            throw $e;
            $arroout["status"] = false;
            return $arroout;
        }
    }

    public function autorizarSolicitud($csol_id, $estado, $observacion) {
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $arroout = array();
        $con = \Yii::$app->db_academico;
        $trans = $con->beginTransaction();
        try {
            // \app\models\Utilities::putMessageLogFile($dts_Cab);

            $fecha_actual = date("d-m-Y");
            $this->actualizaCabPago($con, $csol_id, $estado, $observacion);

            if ($estado==3) {
                $cabSol = $this->consultarCabSolicitud($csol_id);
                $detSol = $this->consultarDetSolicitud($csol_id);
                for ($i = 0; $i < sizeof($detSol); $i++) {
                    $esp_id = $detSol[$i]['esp_id'];
                    $detSol[$i]['egen_fecha_solicitud'] = $cabSol[0]['csol_fecha_creacion'];
                    $detSol[$i]['egen_ruta_archivo_pago'] = $cabSol[0]['csol_ruta_archivo_pago'];
                    $detSol[$i]['uaca_id'] = $cabSol[0]['uaca_id'];
                    $detSol[$i]['mod_id'] = $cabSol[0]['mod_id'];
                    $detSol[$i]['fpag_id'] = $cabSol[0]['fpag_id'];
                    $detSol[$i]['egen_estado_aprobacion'] = $estado;
                    $detSol[$i]['empid'] = $emp_id;
                    $dataResp = $this->recuperarIdsResponsable($cabSol[0]['uaca_id'], $cabSol[0]['mod_id']);
                    $detSol[$i]['resp_id'] = $dataResp; //$dataResp[0]['resp_id']; //Responsable de firma
                    $detSol[$i]['egen_usuario_ingreso'] = $usu_id;
                    $detSol[$i]['egen_numero_solicitud'] = "U".$this->nuevaSecuencia($con, $esp_id);
                    $dataEsp = $this->consultarDataEspecie($esp_id);
                    $dias = $dataEsp[0]['esp_dia_vigencia'];
                    $detSol[$i]['egen_fecha_caducidad'] = date("Y-m-d", strtotime($fecha_actual . "+" . $dias . " days"));
                    $detSol[$i]['egen_certificado'] = $dataEsp[0]['esp_emision_certificado'];
                    $detSol[$i]['est_id'] = $cabSol[0]['est_id'];
                    $this->generarEspecies($con, $detSol[$i]);
                }
            }

            $trans->commit();
            $con->close();
            //RETORNA DATOS
            //$arroout["ids"]= $ftem_id;
            $arroout["status"] = true;
            //$arroout["secuencial"]= $doc_numero;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollBack();
            $con->close();
            throw $e;
            $arroout["status"] = false;
            return $arroout;
        }
    }

    public function consultarDataEspecie($Ids) {
        $con = \Yii::$app->db_academico;
        $sql = "SELECT esp_id,esp_valor,esp_emision_certificado,esp_dia_vigencia
                    FROM " . $con->dbname . ".especies
                WHERE esp_estado=1 AND esp_estado_logico=1 AND esp_id=:esp_id;";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

    private function actualizaCabPago($con, $csol_id, $estado, $observacion) {
        $sql = "UPDATE " . $con->dbname . ".cabecera_solicitud "
                . "SET csol_fecha_aprobacion=CURRENT_TIMESTAMP(),"
                . "csol_estado_aprobacion=:csol_estado_aprobacion, "
                . "csol_observacion=:csol_observacion "
                . "WHERE csol_id=:csol_id";

        $command = $con->createCommand($sql);
        $command->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);
        $command->bindParam(":csol_estado_aprobacion", $estado, \PDO::PARAM_STR);
        $command->bindParam(":csol_observacion", $observacion, \PDO::PARAM_STR);
        $command->execute();
    }

    private function generarEspecies($con, $data) {
        $data['fpagegen_observacion_id'] = "";

        $sql = "INSERT INTO " . $con->dbname . ".especies_generadas
                    (dsol_id,empid,est_id,resp_id,tra_id,esp_id,uaca_id,mod_id,fpag_id,egen_numero_solicitud,
                     egen_observacion,egen_fecha_solicitud,egen_fecha_aprobacion,egen_fecha_caducidad,egen_estado_aprobacion,
                     egen_ruta_archivo_pago,egen_certificado,egen_usuario_ingreso,egen_estado,egen_fecha_creacion,egen_estado_logico)
                VALUES
                    (:dsol_id,:empid,:est_id,:resp_id,:tra_id,:esp_id,:uaca_id,:mod_id,:fpag_id,:egen_numero_solicitud,
                     :egen_observacion,:egen_fecha_solicitud,CURRENT_TIMESTAMP(),:egen_fecha_caducidad,:egen_estado_aprobacion,
                     :egen_ruta_archivo_pago,:egen_certificado,:egen_usuario_ingreso,1,CURRENT_TIMESTAMP(),1); ";


        $command = $con->createCommand($sql);
        $command->bindParam(":dsol_id", $data['dsol_id'], \PDO::PARAM_INT);
        $command->bindParam(":empid", $data['empid'], \PDO::PARAM_INT);
        $command->bindParam(":est_id", $data['est_id'], \PDO::PARAM_INT);
        $command->bindParam(":resp_id", $data['resp_id'], \PDO::PARAM_INT);
        $command->bindParam(":esp_id", $data['esp_id'], \PDO::PARAM_INT);
        $command->bindParam(":tra_id", $data['tra_id'], \PDO::PARAM_INT);
        $command->bindParam(":uaca_id", $data['uaca_id'], \PDO::PARAM_INT);
        $command->bindParam(":mod_id", $data['mod_id'], \PDO::PARAM_INT);
        $command->bindParam(":fpag_id", $data['fpag_id'], \PDO::PARAM_INT);
        $command->bindParam(":egen_numero_solicitud", $data['egen_numero_solicitud'], \PDO::PARAM_STR);
        $command->bindParam(":egen_observacion", $data['egen_observacion'], \PDO::PARAM_STR);
        $command->bindParam(":egen_fecha_solicitud", $data['egen_fecha_solicitud'], \PDO::PARAM_STR);
        //$command->bindParam(":egen_fecha_aprobacion", $data['egen_fecha_aprobacion'], \PDO::PARAM_STR);
        $command->bindParam(":egen_fecha_caducidad", $data['egen_fecha_caducidad'], \PDO::PARAM_STR);
        $command->bindParam(":egen_estado_aprobacion", $data['egen_estado_aprobacion'], \PDO::PARAM_STR);
        $command->bindParam(":egen_ruta_archivo_pago", $data['egen_ruta_archivo_pago'], \PDO::PARAM_STR);
        $command->bindParam(":egen_certificado", $data['egen_certificado'], \PDO::PARAM_STR);
        $command->bindParam(":egen_usuario_ingreso", $data['egen_usuario_ingreso'], \PDO::PARAM_STR);
        $command->execute();
    }

    public function nuevaSecuencia($con, $esp_id) {
        $numero = 0;
        try {
            $sql = "SELECT IFNULL(CAST(esp_numero AS UNSIGNED),0) secuencia FROM " . $con->dbname . ".especies
                    WHERE esp_estado=1 AND esp_estado_logico=1 AND esp_id=:esp_id FOR UPDATE ";
            $sql .= "  ";
            \app\models\Utilities::putMessageLogFile('sql:' . $sql);
            $comando = $con->createCommand($sql);
            $comando->bindParam(":esp_id", $esp_id, \PDO::PARAM_INT);
            $rawData = $comando->queryScalar();
            if ($rawData !== false) {
                //$numero=str_pad((int)$rawData[0]["secuencia"]+1, 9, "0", STR_PAD_LEFT);
                $numero = str_pad((int) $rawData + 1, 9, "0", STR_PAD_LEFT);
                $sql = " UPDATE " . $con->dbname . ".especies SET esp_numero=:secuencia "
                        . " WHERE esp_estado=1 AND esp_estado_logico=1 AND esp_id=:esp_id ";
                $comando = $con->createCommand($sql);
                $comando->bindParam(":secuencia", $numero, \PDO::PARAM_STR);
                $comando->bindParam(":esp_id", $esp_id, \PDO::PARAM_INT);

                $rawData = $comando->execute();
            }
        } catch (Exception $e) {
            Utilities::putMessageLogFile($e);
        }
        return $numero;
    }

    public static function getEstadoPago($estado) {

        $mensaje = "";
        switch ($estado) {
            case '1':
                $mensaje = Yii::t("formulario", "Pendiente");
                break;
            case '2':
                $mensaje = Yii::t("formulario", "Pago Solicitud - Rechazado");
                break;
            case '3':
                $mensaje = Yii::t("formulario", "Generado");
                break;
            default:
                $mensaje = "";
        }
        return $mensaje;
    }

    public static function getSolicitudesGeneradas($est_id, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $str_search = "";
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= " A.egen_fecha_aprobacion BETWEEN :fec_ini AND :fec_fin AND ";
            }
            if ($arrFiltro['search'] != "") {
                $str_search .= "(D.per_pri_nombre like :estudiante OR ";
                $str_search .= "D.per_pri_apellido like :estudiante OR ";
                $str_search .= "D.per_cedula like :estudiante )  AND ";
            }
            if ($arrFiltro['unidad'] > 0) {
                $str_search .= "A.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] > 0) {
                $str_search .= "A.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['tramite'] > 0) {
                $str_search .= "A.tra_id = :tramite AND ";
            }
            if ($arrFiltro['estdocerti'] == 0) {
                $str_search .= "CG.cgen_estado_certificado IS NULL AND A.egen_certificado = 'SI' AND"; // son los pendientes no estan en la tabla
            }
            if ($arrFiltro['estdocerti'] == 1) {
                $str_search .= "CG.cgen_estado_certificado = :estdocerti AND "; // los de estado 1 en la tabla
            }
        }
        if ($onlyData == false) {
            $secuencial = 'A.egen_id, ';
        } else {
            $secuencial = null;
        }

        $sql = "SELECT $secuencial concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',A.egen_numero_solicitud) as egen_numero_solicitud,
                    T.tra_nombre as tramite, C.esp_rubro,concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,
                    D.per_cedula, F.uaca_nombre,G.mod_nombre,date(A.egen_fecha_aprobacion) fecha_aprobacion,
                    A.egen_fecha_caducidad, A.egen_certificado,
                    IFNULL((SELECT IFNULL(ceg.cgen_codigo,' ')  FROM " . $con->dbname . ".certificados_generadas ceg
                        WHERE ceg.cgen_estado=:estado AND ceg.cgen_estado_logico=:estado AND ceg.egen_id=A.egen_id),' ') codigo_generado
                FROM " . $con->dbname . ".especies_generadas A
                            INNER JOIN (" . $con->dbname . ".estudiante B
                                            INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id)
                                    ON A.est_id=B.est_id
                            INNER JOIN " . $con->dbname . ".especies C ON A.esp_id=C.esp_id
                            INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=A.uaca_id
                            INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=A.mod_id
                            INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = A.tra_id
                            LEFT JOIN db_academico.certificados_generadas CG ON CG.egen_id = A.egen_id
                WHERE $str_search A.egen_estado=:estado AND A.egen_estado_logico=:estado  ORDER BY A.egen_id DESC; ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        //$comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $unidad = $arrFiltro['unidad'];
            $modalidad = $arrFiltro['modalidad'];
            $tramite = $arrFiltro['tramite'];
            $estadocerti = $arrFiltro['estdocerti'];
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":estudiante", $search_cond, \PDO::PARAM_STR);
            }
            if ($arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            if ($arrFiltro['tramite'] > 0) {
                $comando->bindParam(":tramite", $tramite, \PDO::PARAM_INT);
            }
            if ($arrFiltro['estdocerti'] > -1) {
                $comando->bindParam(":estdocerti", $estadocerti, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        //Utilities::putMessageLogFile($resultData);
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'egen_id',
                    'fecha_aprobacion',
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

    public function consultarEspecieGenerada($Ids) {
        $rawData = array();
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT A.egen_id,A.dsol_id,A.egen_numero_solicitud,C.esp_rubro,concat(D.per_pri_nombre,' ',D.per_seg_nombre,' ',D.per_pri_apellido,' ',D.per_seg_apellido) Nombres,D.per_cedula,
                    A.uaca_id,F.uaca_nombre,G.mod_nombre,concat(E.resp_titulo,' ',E.resp_nombre) Responsable,date(A.egen_fecha_aprobacion) fecha_aprobacion,
                    A.egen_fecha_caducidad,D.per_correo,D.per_celular,A.esp_id, ea.eaca_nombre Carrera, esp_dia_vigencia, det.dsol_observacion as detalle, det.dsol_archivo_extra as imagen, D.per_id
                    FROM " . $con->dbname . ".especies_generadas A
                            INNER JOIN (" . $con->dbname . ".estudiante B
                                            INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id)
                                    ON A.est_id=B.est_id
                            INNER JOIN " . $con->dbname . ".especies C ON A.esp_id=C.esp_id
                            INNER JOIN " . $con->dbname . ".estudiante_carrera_programa ecp ON ecp.est_id = A.est_id
                            INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad meu ON meu.meun_id = ecp.meun_id
                            INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=A.uaca_id
                            INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=A.mod_id
                            INNER JOIN " . $con->dbname . ".estudio_academico ea ON ea.eaca_id=meu.eaca_id
                            INNER JOIN " . $con->dbname . ".responsable_especie E ON (E.uaca_id=A.uaca_id and E.mod_id=A.mod_id)
                            INNER JOIN " . $con->dbname . ".detalle_solicitud det ON det.dsol_id = A.dsol_id
                WHERE A.egen_estado=:estado AND A.egen_estado_logico=:estado AND A.egen_id=:egen_id ; ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":egen_id", $Ids, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        //return $comando->queryAll();
        $rawData = $comando->queryOne();
        return $rawData;
    }

    /**
     * Function consultaSolicitudexrubro
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $csol_id
     * @return
     */
    public function consultaSolicitudexrubro($csol_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT group_concat(esp.esp_rubro) as especies
                  FROM " . $con->dbname . ".detalle_solicitud dso
                       INNER JOIN " . $con->dbname . ".especies esp ON esp.esp_id = dso.esp_id
                  WHERE csol_id = :csol_id AND
                    dso.dsol_estado = :estado AND
                    dso.dsol_estado_logico = :estado AND
                    esp.esp_estado = :estado AND
                    esp.esp_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultaPeridxestid
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $est_id
     * @return
     */
    public function consultaPeridxestid($est_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT per_id
                  FROM " . $con->dbname . ".estudiante
                  WHERE est_id = :est_id AND
                    est_estado = :estado AND
                    est_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    public static function getSolicitudesGeneradasxest($csol_id, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT A.egen_id, concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',A.egen_numero_solicitud) as egen_numero_solicitud,
                    T.tra_nombre as tramite, C.esp_rubro,concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,D.per_cedula,
                    F.uaca_nombre,G.mod_nombre,date(A.egen_fecha_aprobacion) fecha_aprobacion,
                    A.egen_fecha_caducidad, Z.cgen_ruta_archivo_pdf as imagen, Z.cgen_estado_certificado
                FROM " . $con->dbname . ".especies_generadas A
                            INNER JOIN (" . $con->dbname . ".estudiante B
                                            INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id)
                                    ON A.est_id=B.est_id
                            INNER JOIN " . $con->dbname . ".detalle_solicitud ds on ds.dsol_id = A.dsol_id
                            INNER JOIN " . $con->dbname . ".cabecera_solicitud cs on cs.csol_id = ds.csol_id
                            INNER JOIN " . $con->dbname . ".especies C ON A.esp_id=C.esp_id
                            INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=A.uaca_id
                            INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=A.mod_id
                            INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = A.tra_id
                            LEFT JOIN " . $con->dbname . ".certificados_generadas Z ON Z.egen_id = A.egen_id
                WHERE cs.csol_id = :csol_id AND
                      A.egen_estado=:estado AND
                      A.egen_estado_logico=:estado
                ORDER BY A.egen_id DESC; ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);

        $resultData = $comando->queryAll();
        //Utilities::putMessageLogFile($resultData);
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'egen_id',
                    'fecha_aprobacion',
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
     * Function consultarcabeceraxdetalle
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $csol_id
     * @return
     */
    public function consultarcabeceraxdetalle($dsol_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT csol_id
                  FROM " . $con->dbname . ".detalle_solicitud
                  WHERE dsol_id = :dsol_id AND
                    dsol_estado = :estado AND
                    dsol_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dsol_id", $dsol_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    public static function getSolicitudesGeneradasxdet($csol_id, $sol_id, $onlyData = false) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT A.egen_id, concat(F.uaca_nomenclatura,T.tra_nomenclatura,lpad(ifnull(C.esp_codigo,0),3,'0'),'-',A.egen_numero_solicitud) as egen_numero_solicitud,
                    T.tra_nombre as tramite, C.esp_rubro,concat(D.per_pri_nombre,' ',D.per_pri_apellido) Nombres,D.per_cedula,
                    F.uaca_nombre,G.mod_nombre,date(A.egen_fecha_aprobacion) fecha_aprobacion,
                    A.egen_fecha_caducidad
                FROM " . $con->dbname . ".especies_generadas A
                            INNER JOIN (" . $con->dbname . ".estudiante B
                                            INNER JOIN " . $con1->dbname . ".persona D ON B.per_id=D.per_id)
                                    ON A.est_id=B.est_id
                            INNER JOIN " . $con->dbname . ".detalle_solicitud ds on ds.dsol_id = A.dsol_id
                            INNER JOIN " . $con->dbname . ".cabecera_solicitud cs on cs.csol_id = ds.csol_id
                            INNER JOIN " . $con->dbname . ".especies C ON A.esp_id=C.esp_id
                            INNER JOIN " . $con->dbname . ".unidad_academica F ON F.uaca_id=A.uaca_id
                            INNER JOIN " . $con->dbname . ".modalidad G ON G.mod_id=A.mod_id
                            INNER JOIN " . $con->dbname . ".tramite T ON T.tra_id = A.tra_id
                WHERE cs.csol_id = :csol_id AND ds.dsol_id = :dsol_id AND
                      A.egen_estado=:estado AND
                      A.egen_estado_logico=:estado
                ORDER BY A.egen_id DESC; ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);
        $comando->bindParam(":dsol_id", $sol_id, \PDO::PARAM_INT);

        $resultData = $comando->queryAll();
        //Utilities::putMessageLogFile($resultData);
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'egen_id',
                    'fecha_aprobacion',
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
     * Function consultarPeriodoactivo
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $paca_id
     * @return
     */
    public function consultarPeriodoactivo() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT group_concat(paca_id) as paca_id
                  FROM " . $con->dbname . ".periodo_academico
                  WHERE paca_activo = 'A' AND
                    paca_estado = :estado AND
                    paca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function consultarPagodia
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer eppa_estado_pago
     * @return
     */
    public function consultarPagodia($paca_id, $est_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        if (!empty($paca_id)) {
            $periodo = 'paca_id in (' . $paca_id . ') AND';
        }
        $sql = "SELECT count(*) as eppa_estado_pago
                  FROM " . $con->dbname . ".estudiante_periodo_pago
                  WHERE $periodo
                    eppa_estado_pago = :estado AND
                    est_id = :est_id AND
                    eppa_estado = :estado AND
                    eppa_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function
     * @author  Giovanni Vergara <abalistadesarrollo02@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultarSolicitudXcorreo($csol_id) {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        $sql = "SELECT A.*,C.tra_nombre,B.esp_rubro, B.esp_emision_certificado,me.uaca_id, me.mod_id
                , CONCAT(pers.per_pri_nombre , ' ' , pers.per_pri_apellido) as nombres
                 FROM " . $con->dbname . ".detalle_solicitud A
			INNER JOIN " . $con->dbname . ".especies B ON A.esp_id=B.esp_id
			INNER JOIN " . $con->dbname . ".tramite C ON A.tra_id=C.tra_id
                        INNER JOIN " . $con->dbname . ".estudiante_carrera_programa ec ON ec.est_id = A.est_id
                        INNER JOIN " . $con->dbname . ".modalidad_estudio_unidad me ON me.meun_id = ec.meun_id
                        INNER JOIN " . $con->dbname . ".estudiante est ON est.est_id = A.est_id
                        INNER JOIN " . $con1->dbname . ".persona pers ON pers.per_id = est.per_id
		 WHERE A.dsol_estado= :estado AND A.dsol_estado_logico= :estado  AND A.csol_id=:csol_id; ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":csol_id", $csol_id, \PDO::PARAM_INT);
        return $comando->queryAll();
    }

}
