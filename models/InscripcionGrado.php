<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

use \yii\data\ActiveDataProvider;
use Yii;
use app\modules\financiero\models\OrdenPago;
use app\models\Persona;
use app\models\EmpresaPersona;
use \app\modules\admision\models\SolicitudInscripcion;
use app\modules\admision\models\Interesado;
use app\modules\admision\models\InteresadoEmpresa;
use app\models\Usuario;
use app\models\UsuaGrolEper;
use yii\base\Security;
use app\modules\financiero\models\Secuencias;
use app\modules\admision\models\DocumentoAdjuntar;
use yii\base\Exception;

/**
 * Description of InscripcionAdmision
 *
 * @author root
 */
class InscripcionGrado extends \yii\db\ActiveRecord {

	/**
     * Function addLabelTimeDocumentos renombra el documento agregando una varible de tiempo 
     * @author  Developer Uteg <developer@uteg.edu.ec>
     * @param   int     $sins_id        Id de la solicitud
     * @param   string  $file           Uri del Archivo a modificar
     * @param   int     $timeSt         Parametro a agregar al nombre del archivo
     * @return  $newFile | FALSE (Retorna el nombre del nuevo archivo o false si fue error).
     */
    public static function addLabelTimeDocumentos($sins_id, $file, $timeSt) {
        $arrIm = explode(".", basename($file));
        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
        $baseFile = Yii::$app->basePath;
        $search = ".$typeFile";
        $replace = "_$timeSt" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        if (file_exists($baseFile . $file)) {
            if (rename($baseFile . $file, $baseFile . $newFile)) {
                return $newFile;
            }
        } else {
            return $newFile;
        }
    }

    public function insertarInscripciongrado($data) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {
            $twin_id = $this->insertarDataInscripciongrado($con, $data["DATA_1"]);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripciongrado($igra_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuagrado($igra_id);
            }
            $trans->commit();
            //RETORNA DATOS 
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $igra_id;
            $arroout["data"] = $data; //$rawData;
            return $arroout;
        } catch (\Exception $e) {
            $trans->rollback();

            $arroout["status"] = FALSE;
            $arroout["error"] = $e->getCode();
            $arroout["message"] = $e->getMessage();
            $arroout["data"] = null; //$rawData;
            return $arroout;
        }
    }

    public function actualizarInscripciongrado($data) {
        $arroout = array();
        $con = \Yii::$app->db_inscripcion;
        $trans = $con->beginTransaction();
        try {
            $twin_id = $this->updateDataInscripciongrado($con, $data["DATA_1"]);
            if (empty($data['opcion'])) {
                $data = $this->consultarDatosInscripciongrado($igra_id);
            } else {
                $data = $this->consultarDatosInscripcionContinuagrado($igra_id);
            }
            $trans->commit();
            $arroout["status"] = TRUE;
            $arroout["error"] = null;
            $arroout["message"] = null;
            $arroout["ids"] = $igra_id;
            $arroout["data"] = $data; //$rawData;
            return $arroout;
        } catch (Exception $e) {
            $trans->rollback();
            $arroout["status"] = FALSE;
            $arroout["error"] = $e->getCode();
            $arroout["message"] = $e->getMessage();
            $arroout["data"] = null; //$rawData;
            return $arroout;
        }
    }

    private function insertarDataInscripciongrado($con, $data) {
        $ruta_doc_titulo = '';
        $ruta_doc_dni = '';
        $ruta_doc_certvota = '';
        $ruta_doc_foto = '';
        $ruta_doc_certificado = '';
        $igra_mensaje1 = 0;
        $igra_mensaje2 = 0;

        $sql = "INSERT INTO " . $con->dbname . ".inscripcion_grado
            (uaca_id, eaca_id, mod_id, paca_id, igra_dni, igra_numero, igra_pri_nombre, igra_seg_nombre, igra_pri_apellido, igra_seg_apellido, igra_lugar_nacimiento, igra_fecha_nacimiento, igra_nacionalidad, igra_estado_civil, igra_pais, igra_provincia, igra_canton, igra_parroquia, igra_direccion, igra_celular, igra_telefono, igra_correo, igra_direccion_trabajo, igra_caso_emergencia, igra_parentesco, igra_telf_emergencia, igra_dire_contacto_emerg, maca_id, igra_metodo_ingreso, ruta_doc_titulo, ruta_doc_dni, ruta_doc_certvota, ruta_doc_foto, ruta_doc_certificado, /*ruta_doc_hojavida, ruta_doc_aceptacion, ruta_doc_pago, */igra_mensaje1, /*igra_tipo_pago, */igra_mensaje2, igra_estado, igra_fecha_modificacion, igra_estado_logico)VALUES
            (:uaca_id, :eaca_id, :mod_id, :paca_id, :igra_dni, :igra_numero, :igra_pri_nombre, :igra_seg_nombre, :igra_pri_apellido, :igra_seg_apellido, :igra_lugar_nacimiento, :igra_fecha_nacimiento, :igra_nacionalidad, :igra_estado_civil, :igra_pais, :igra_provincia, :igra_canton, :igra_parroquia, :igra_direccion, :igra_celular, :igra_telefono, :igra_correo, :igra_direccion_trabajo, :igra_caso_emergencia, :igra_parentesco, :igra_telf_emergencia, :igra_dire_contacto_emerg, :maca_id, :igra_metodo_ingreso, :ruta_doc_titulo, :ruta_doc_dni, :ruta_doc_certvota, :ruta_doc_foto, :ruta_doc_certificado, :ruta_doc_hojavida, :ruta_doc_aceptacion, :ruta_doc_pago, :igra_mensaje1, /*:igra_tipo_pago, */:igra_mensaje2, 1, CURRENT_TIMESTAMP(), 1)";

        $met_ing = 0;
        if (empty($data[0]['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data[0]['ming_id'];
        }
        \app\models\Utilities::putMessageLogFile('identificacion:' . $data[0]['pges_cedula']);
        $command = $con->createCommand($sql);
        $command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":paca_id", $data[0]['periodo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_dni", $data[0]['tipo_dni'], \PDO::PARAM_STR);
        $command->bindParam(":igra_numero", $data[0]['pges_correo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_pri_nombre", $data[0]['pais'], \PDO::PARAM_STR);
        $command->bindParam(":igra_seg_nombre", $data[0]['pges_celular'], \PDO::PARAM_STR);
        $command->bindParam(":igra_pri_apellido", $data[0]['unidad_academica'], \PDO::PARAM_STR);
        $command->bindParam(":igra_seg_apellido", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":igra_lugar_nacimiento", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":igra_fecha_nacimiento", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":igra_nacionalidad", $data[0]['conoce'], \PDO::PARAM_STR);
        $command->bindParam(":igra_estado_civil", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_pais", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_provincia", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_canton", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_parroquia", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_direccion", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_celular", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":igra_telefono", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_correo", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_direccion_trabajo", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_caso_emergencia", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_parentesco", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_telf_emergencia", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_dire_contacto_emerg", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":maca_id", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":ruta_doc_titulo", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_dni", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_certvota", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_foto", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_certificado", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje1", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje2", $twin_mensaje2, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    private function updateDataInscripciongrado($con, $data) {
        $sql = "UPDATE " . $con->dbname . ".inscripcion_grado 
                SET uaca_id=:uaca_id,eaca_id=:eaca_id,mod_id=:mod_id,paca_id=:paca_id,
                    igra_dni=:igra_dni,igra_numero=:igra_numero,igra_pri_nombre=:igra_pri_nombre,igra_seg_nombre=:igra_seg_nombre,igra_pri_apellido=:igra_pri_apellido, 
                    igra_seg_apellido=:igra_seg_apellido,igra_lugar_nacimiento=:igra_lugar_nacimiento,igra_fecha_nacimiento=:igra_fecha_nacimiento,igra_nacionalidad=:igra_nacionalidad,igra_estado_civil=:igra_estado_civil, 
                    igra_pais=:igra_pais, igra_provincia=:igra_provincia,igra_canton=:igra_canton,
                    igra_parroquia=:igra_parroquia,igra_direccion=:igra_direccion, 
                    igra_celular=:igra_celular,igra_telefono=:igra_telefono,igra_correo=:igra_correo, igra_direccion_trabajo=:igra_direccion_trabajo,igra_caso_emergencia=:igra_caso_emergencia,igra_parentesco=:igra_parentesco,igra_telf_emergencia=:igra_telf_emergencia,igra_telf_emergencia=:igra_telf_emergencia,igra_dire_contacto_emerg=:igra_dire_contacto_emerg,maca_id=:maca_id,igra_metodo_ingreso=:igra_metodo_ingreso,ruta_doc_titulo, ruta_doc_dni, ruta_doc_certvota,ruta_doc_foto,ruta_doc_certificado,igra_mensaje1=:igra_mensaje1,igra_mensaje2=:igra_mensaje2,igra_fecha_modificacion=CURRENT_TIMESTAMP() 
                 WHERE igra_id =:igra_id ";
        $met_ing = 0;
        if (empty($data[0]['ming_id'])) {
            $met_ing = 0;
        } else {
            $met_ing = $data[0]['ming_id'];
        }
        $command = $con->createCommand($sql);
        $command->bindParam(":igra_id", $data[0]['igra_id'], \PDO::PARAM_STR);
        $command->bindParam(":uaca_id", $data[0]['unidad_academica'], \PDO::PARAM_STR);
        $command->bindParam(":eaca_id", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":mod_id", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":paca_id", $data[0]['periodo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_dni", $data[0]['tipo_dni'], \PDO::PARAM_STR);
        $command->bindParam(":igra_numero", $data[0]['pges_correo'], \PDO::PARAM_STR);
        $command->bindParam(":igra_pri_nombre", $data[0]['pais'], \PDO::PARAM_STR);
        $command->bindParam(":igra_seg_nombre", $data[0]['pges_celular'], \PDO::PARAM_STR);
        $command->bindParam(":igra_pri_apellido", $data[0]['unidad_academica'], \PDO::PARAM_STR);
        $command->bindParam(":igra_seg_apellido", $data[0]['modalidad'], \PDO::PARAM_STR);
        $command->bindParam(":igra_lugar_nacimiento", $data[0]['carrera'], \PDO::PARAM_STR);
        $command->bindParam(":igra_fecha_nacimiento", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":igra_nacionalidad", $data[0]['conoce'], \PDO::PARAM_STR);
        $command->bindParam(":igra_estado_civil", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_pais", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_provincia", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_canton", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_parroquia", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_direccion", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_celular", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":igra_telefono", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":igra_correo", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":igra_direccion_trabajo", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":igra_caso_emergencia", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":igra_parentesco", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_telf_emergencia", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_dire_contacto_emerg", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":maca_id", $twin_mensaje2, \PDO::PARAM_STR);
        $command->bindParam(":igra_metodo_ingreso", $met_ing, \PDO::PARAM_INT);
        $command->bindParam(":ruta_doc_titulo", $ruta_doc_titulo, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_dni", $ruta_doc_dni, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_certvota", $ruta_doc_certvota, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_foto", $ruta_doc_foto, \PDO::PARAM_STR);
        $command->bindParam(":ruta_doc_certificado", $ruta_doc_certificado, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje1", $twin_mensaje1, \PDO::PARAM_STR);
        $command->bindParam(":igra_mensaje2", $twin_mensaje2, \PDO::PARAM_STR);
        $command->execute();

        return $data[0]['igra_id'];
    }

    public static function addLabelFechaDocPagos($sins_id, $file, $FechaTime) {
        $arrIm = explode(".", basename($file));
        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
        $baseFile = Yii::$app->basePath;
        $search = ".$typeFile";
        $replace = "-$FechaTime" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        if (file_exists($baseFile . $file)) {
            if (rename($baseFile . $file, $baseFile . $newFile)) {
                return $newFile;
            }
        } else {
            return $newFile;
        }
    }

    public function consultarDatosInscripciongrado($igra_id) {
        $con = \Yii::$app->db_inscripcion;
        $con2 = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_precio = 'A';

        $sql = "
                SELECT  ua.uaca_nombre unidad, 
                        m.mod_nombre modalidad,
                        ea.eaca_nombre carrera,
                        ea.eaca_id as id_carrera,
                        mi.ming_nombre metodo,
                        ip.ipre_precio as precio,
                        igra_pri_nombre,
                        igra_pri_apellido,
                        igra_numero,
                        igra_correo,
                        igra_pais,
                        igra_celular,
                        igra.uaca_id,
                        igra.mod_id,
                        igra.eaca_id,
                        igra_metodo_ingreso,
                        -- conuteg_id,
                        ruta_doc_titulo,
                        ruta_doc_dni,
                        -- 96 as ddit_valor,-- ddit.ddit_valor,
                        ruta_doc_certvota,
                        ruta_doc_foto,
                        ruta_doc_certificado,                        
                        ruta_doc_hojavida,
                        igra_dni,
                        ruta_doc_aceptacion
                        -- igra.cemp_id,
                        -- igra_tipo_pago,
                        -- ruta_doc_pago
                FROM " . $con->dbname . ".inscripcion_grado igra inner join db_academico.unidad_academica ua on ua.uaca_id = igra.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = igra.mod_id
                     inner join " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = igra.eaca_id
                     left join " . $con->dbname . ".metodo_ingreso mi on mi.ming_id = igra.igra_metodo_ingreso
                     left join " . $con2->dbname . ".item_metodo_unidad imi on (imi.ming_id =  igra.igra_metodo_ingreso and imi.uaca_id = igra.uaca_id and imi.mod_id = igra.mod_id)
                     left join " . $con2->dbname . ".item_precio ip on ip.ite_id = imi.ite_id
                     left join " . $con2->dbname . ".descuento_item as ditem on ditem.ite_id=imi.ite_id
                     left join " . $con2->dbname . ".detalle_descuento_item as ddit on ddit.dite_id=ditem.dite_id
                WHERE igra.igra_id = :igra_id AND                     
                     -- ip.ipre_estado_precio = :estado_precio AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     ea.eaca_estado = :estado AND
                     ea.eaca_estado_logico = :estado
                     -- imi.imni_estado = :estado AND
                     -- imi.imni_estado_logico = :estado 
                     -- AND ip.ipre_estado = :estado AND
                     -- ip.ipre_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":igra_id", $igra_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDatosInscripcionContinuagrado($igra_id) {
        $con = \Yii::$app->db_inscripcion;
        $con2 = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $estado_precio = 'A';

        $sql = "
                SELECT  ua.uaca_nombre unidad, 
                        m.mod_nombre modalidad,
                        mest.mest_nombre carrera,
                        mest.mest_id as id_carrera,                        
                        ip.ipre_precio as precio,
                        igra_pri_nombre,
                        igra_pri_apellido,
                        igra_numero,
                        igra_correo,
                        igra_pais,
                        igra_celular,
                        igra.uaca_id,
                        igra.mod_id,
                        igra.car_id,
                        igra_metodo_ingreso,
                        -- conuteg_id,
                        ruta_doc_titulo,
                        ruta_doc_dni,                        
                        ruta_doc_certvota,
                        ruta_doc_foto,
                        ruta_doc_certificado,                        
                        ruta_doc_hojavida,
                        igra_dni,
                        ruta_doc_aceptacion
                        -- igra.cemp_id,
                        -- igra_tipo_pago,
                        -- ruta_doc_pago
                FROM  " . $con->dbname . ".inscripcion_grado igra inner join " . $con1->dbname . ".unidad_academica ua on ua.uaca_id = igra.uaca_id
                     inner join " . $con1->dbname . ".modalidad m on m.mod_id = igra.mod_id
                     inner join " . $con1->dbname . ".modulo_estudio mest on mest.mest_id = igra.eaca_id                     
                     left join " . $con2->dbname . ".item_metodo_unidad imi on (imi.uaca_id = igra.uaca_id and imi.mod_id = igra.mod_id and imi.mest_id = igra.car_id)
                     left join  " . $con2->dbname . ".item_precio ip on ip.ite_id = imi.ite_id
                     left join  " . $con2->dbname . ".descuento_item as ditem on ditem.ite_id=imi.ite_id
                     left join  " . $con2->dbname . ".detalle_descuento_item as ddit on ddit.dite_id=ditem.dite_id
                WHERE igra.igra_id = :igra_id and                     
                     ip.ipre_estado_precio = 'A' AND
                     ua.uaca_estado = :estado AND
                     ua.uaca_estado_logico = :estado AND
                     m.mod_estado = :estado AND
                     m.mod_estado_logico = :estado AND
                     mest.mest_estado = :estado AND
                     mest.mest_estado_logico = :estado AND
                     imi.imni_estado = :estado AND
                     imi.imni_estado_logico = :estado AND
                     ip.ipre_estado = :estado AND
                     ip.ipre_estado_logico = '1'";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":igra_id", $twin_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado_precio", $estado_precio, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}