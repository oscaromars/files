<?php

namespace app\modules\financiero\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "documento".
 *
 * @property int $doc_id
 * @property int $tdoc_id
 * @property string $doc_nombres_cliente
 * @property string $doc_direccion
 * @property string $doc_telefono
 * @property string $doc_correo
 * @property double $doc_valor
 * @property int $doc_usuario_transaccion
 * @property string $doc_estado
 * @property string $doc_fecha_creacion
 * @property string $doc_fecha_modificacion
 * @property string $doc_estado_logico
 *
 * @property DetalleDocumento[] $detalleDocumentos
 */
class Documento extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'documento';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tdoc_id', 'doc_nombres_cliente', 'doc_valor', 'doc_usuario_transaccion'], 'required'],
            [['tdoc_id', 'doc_usuario_transaccion'], 'integer'],
            [['doc_valor'], 'number'],
            [['doc_fecha_creacion', 'doc_fecha_modificacion'], 'safe'],
            [['doc_nombres_cliente'], 'string', 'max' => 250],
            [['doc_direccion'], 'string', 'max' => 500],
            [['doc_telefono', 'doc_correo'], 'string', 'max' => 50],
            [['doc_estado', 'doc_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'doc_id' => 'Doc ID',
            'tdoc_id' => 'Tdoc ID',
            'doc_nombres_cliente' => 'Doc Nombres Cliente',
            'doc_direccion' => 'Doc Direccion',
            'doc_telefono' => 'Doc Telefono',
            'doc_correo' => 'Doc Correo',
            'doc_valor' => 'Doc Valor',
            'doc_usuario_transaccion' => 'Doc Usuario Transaccion',
            'doc_estado' => 'Doc Estado',
            'doc_fecha_creacion' => 'Doc Fecha Creacion',
            'doc_fecha_modificacion' => 'Doc Fecha Modificacion',
            'doc_estado_logico' => 'Doc Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleDocumentos() {
        return $this->hasMany(DetalleDocumento::className(), ['doc_id' => 'doc_id']);
    }

    public function insertDocumento($con, $tdoc_id, $tipo_dni_id, $doc_dni_val, $sbpa_id, $nombres, $direccion, $telefono, $correo, $valor, $usuario, $doc_dni_key) {
        $estado = 1;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $sql = "INSERT INTO " . $con->dbname . ".documento
            (tdoc_id,  doc_tipo_dni, $doc_dni_key,  sbpa_id, doc_nombres_cliente, doc_direccion, doc_telefono,doc_fecha_pago, doc_correo, doc_valor, doc_pagado, doc_usuario_transaccion,doc_estado,doc_estado_logico) VALUES
            (:tdoc_id,:doc_tipo_dni, :$doc_dni_key, :sbpa_id,:doc_nombres_cliente,:doc_direccion,:doc_telefono,:fecha_pago,:doc_correo,:doc_valor, 0, :doc_usuario_transaccion,:doc_estado,:doc_estado)";
        \app\models\Utilities::putMessageLogFile('insert documento:' . $sql);
        $command = $con->createCommand($sql);
        $command->bindParam(":tdoc_id", $tdoc_id, \PDO::PARAM_INT);
        $command->bindParam(":sbpa_id", $sbpa_id, \PDO::PARAM_INT);
        $command->bindParam(":fecha_pago", $fecha, \PDO::PARAM_STR);
        $command->bindParam(":doc_tipo_dni", $tipo_dni_id, \PDO::PARAM_STR);
        $command->bindParam(":$doc_dni_key", $doc_dni_val, \PDO::PARAM_STR);
        $command->bindParam(":doc_nombres_cliente", $nombres, \PDO::PARAM_STR);
        $command->bindParam(":doc_direccion", $direccion, \PDO::PARAM_STR);
        $command->bindParam(":doc_telefono", $telefono, \PDO::PARAM_STR);
        $command->bindParam(":doc_correo", $correo, \PDO::PARAM_STR);
        $command->bindParam(":doc_valor", $valor, \PDO::PARAM_INT);
        $command->bindParam(":doc_usuario_transaccion", $usuario, \PDO::PARAM_STR);
        $command->bindParam(":doc_estado", $estado, \PDO::PARAM_STR);
        $command->execute();
        return $con->getLastInsertID();
    }

    public function actualizarDocumento($con, $doc_id, $estado_pago = 0) {
        $estado = 1;
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $sql = "UPDATE " . $con->dbname . ".documento
                set doc_pagado = :doc_pagado,
                    doc_fecha_pago = :fecha_pago,                    
                    doc_fecha_modificacion = :fecha_pago
                WHERE doc_id = :doc_id
                and doc_estado = :estado
                and doc_estado_logico = :estado";

        \app\models\Utilities::putMessageLogFile('sql: ' . $sql);
        $command = $con->createCommand($sql);
        $command->bindParam(":doc_id", $doc_id, \PDO::PARAM_INT);
        $command->bindParam(":doc_pagado", $estado_pago, \PDO::PARAM_INT);
        $command->bindParam(":fecha_pago", $fecha, \PDO::PARAM_STR);
        $command->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $response = $command->execute();
        return $response;
    }

    /**
     * Function consultarDatosxId
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData 
     */
    public function consultarDatosxId($con, $doc_id) {
        $estado = 1;
        $sql = "SELECT  d.tdoc_id,
                        d.doc_tipo_dni,
                        d.doc_cedula,
                        d.doc_pasaporte,
                        d.doc_ruc,
                        d.doc_nombres_cliente,
                        d.doc_telefono,
                        d.doc_correo,
                        d.doc_valor,
                        d.doc_fecha_pago,
                        d.doc_direccion,
                        pb.pben_cedula,
                        pb.pben_celular,
                        pb.pben_correo,
                        pb.pben_nombre,
                        pb.pben_apellido,
                        sb.sbpa_id,
                        sb.sbpa_fecha_solicitud,
                        d.doc_pagado
                FROM " . $con->dbname . ".documento d inner join " . $con->dbname . ".solicitud_boton_pago sb 
                         on sb.sbpa_id = d.sbpa_id     
                     inner join " . $con->dbname . ".persona_beneficiaria pb on pb.pben_id = sb.pben_id
                WHERE d.doc_id = :doc_id
                and d.doc_estado = :estado
                and d.doc_estado_logico = :estado
                and pb.pben_estado = :estado
                and pb.pben_estado_logico = :estado
                and sb.sbpa_estado = :estado
                and sb.sbpa_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":doc_id", $doc_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarEstadoByCedula($cedula,$cedula_fact) {
        $con = \Yii::$app->db_financiero;
        
        $sql = "
                SELECT
                    vire.status as estado
                FROM 
                    db_facturacion.persona_beneficiaria as pben
                    join db_facturacion.solicitud_boton_pago as sbpa on sbpa.pben_id =  pben.pben_id
                    join db_facturacion.documento as doc on doc.sbpa_id = sbpa.sbpa_id
                    join db_financiero.vpos_info_response as vire on vire.ordenPago = doc.doc_id and vire.tipo_orden = 2 
                WHERE
                    pben.pben_cedula = :cedula and
                    doc.doc_cedula = :cedula_fact
                ";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":cedula_fact", $cedula_fact, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData['estado'];
    }

    public function consultarVposByDocid($doc_id) {
        $estado = 1;
        $con = \Yii::$app->db_financiero;
        $sql = "
                SELECt   
                        vreq.reference,
                        vres.requestId as requestID,
                        json_response as resp
                FROM 
                        db_financiero.vpos_request as vreq
                        join db_financiero.vpos_response as vres on vres.ordenPago = :doc_id and vres.tipo_orden = 2
                WHERE
                        vreq.ordenPago = :doc_id and vreq.tipo_orden = 2
                        
                ";

        $comando = $con->createCommand($sql);
        //$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":doc_id", $doc_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDocIdByCedulaBen($cedula = null) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "
                SELECT  
                        doc.doc_id
                FROM    
                        db_facturacion.documento as doc
                        join db_facturacion.solicitud_boton_pago as sbpa on sbpa.sbpa_id = doc.sbpa_id
                        join db_facturacion.persona_beneficiaria as pben on pben.pben_id = sbpa.pben_id
                WHERE 
                        pben.pben_cedula = :cedula
                        and doc.doc_estado = :estado
                        and doc.doc_estado_logico = :estado
                        and pben.pben_estado = :estado
                        and pben.pben_estado_logico = :estado
                        and sbpa.sbpa_estado = :estado
                        and sbpa.sbpa_estado_logico = :estado
                
                ";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $resultData = $comando->queryOne();
        return $resultData['doc_id'];
    }

    /**
     * Function consultaResumen (Se obtiene resumen de las transacciones)
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   $sbpa_id (id de solicitud boton de pago)     
     */
    public function consultaResumen($sbpa_id) {
        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_financiero;
        $estado = 1;
        $sql = "SELECT 
                    pben.pben_nombre as nombre_beneficiario,
                    pben.pben_apellido as apellido_beneficiario,
                    pben.pben_cedula as cedula_beneficiario,
                    fdoc.doc_nombres_cliente as nombre_factura,
                    fdoc.doc_valor as valor,
                    fdoc.doc_cedula as cedula_factura,
                    fdoc.doc_pagado as estado,                   
                    fdoc.doc_fecha_pago as fecha,
                    vrep.requestid as referencia
                FROM  " . $con->dbname . ".solicitud_boton_pago sbp
                INNER JOIN " . $con->dbname . ".persona_beneficiaria pben on pben.pben_id = sbp.pben_id  
                INNER JOIN " . $con->dbname . ".documento fdoc on fdoc.sbpa_id = sbp.sbpa_id
                INNER JOIN " . $con1->dbname . ".vpos_response vrep on vrep.ordenPago = sbp.sbpa_id    
                WHERE 
                      sbp.sbpa_id = :sbpa_id AND                      
                      sbp.sbpa_estado =:estado AND
                      sbp.sbpa_estado_logico = :estado AND                       
                      pben.pben_estado =:estado AND
                      pben.pben_estado_logico = :estado AND
                      fdoc.doc_estado =:estado AND
                      fdoc.doc_estado_logico = :estado AND                      
                      vrep.estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":sbpa_id", $sbpa_id, \PDO::PARAM_INT);

        $resultData = $comando->queryOne();
        return $resultData;
    }

    public function consultarDetalledocumentoById($doc_id, $onlyData = null) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "
            select
                ite.ite_codigo as codigo,
                ite.ite_nombre as item, 
                ite.ite_id,
                ddoc.ddoc_cantidad as cantidad,
                ddoc.ddoc_valor_iva as iva,
                ddoc.ddoc_valor_total as total,
                imu.ming_id metodo, 
                imu.uaca_id unidad,
                imu.mod_id modalidad
            from 
                " . $con->dbname . ".detalle_documento ddoc
                join " . $con->dbname . ".item as ite on ite.ite_id = ddoc.ite_id
                left join " . $con->dbname . ".item_metodo_unidad imu on imu.ite_id = ddoc.ite_id
            where 
                ddoc.doc_id=:doc_id and
                ddoc.ddoc_estado = :status and
                imu.imni_estado = :status and 
                imu.imni_estado_logico = :status
            ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":status", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":doc_id", $doc_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'codigo',
                    'item',
                    'cantidad',
                    'iva',
                    'total'
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }

}
