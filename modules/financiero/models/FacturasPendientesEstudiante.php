<?php

namespace app\modules\financiero\models;
use yii\data\ArrayDataProvider;
use Yii;
use app\models\Utilities;

/**
 * This is the model class for table "facturas_pendientes_estudiante".
 *
 * @property int $fpe_id
 * @property int $roc_id
 * @property int $est_id
 * @property string $fpe_tipo_documento
 * @property string $fpe_numero_documento
 * @property int $fpe_usu_ingreso
 * @property int $fpe_usu_modifica
 * @property string $fpe_estado
 * @property double $fpe_abono
 * @property string $fpe_fecha_creacion
 * @property string $fpe_fecha_modificacion
 * @property string $fpe_estado_logico
 * @property string $fpe_vencimiento
 * @property string $fpe_num_cuota
 * @property string $fpe_valor
 */
class FacturasPendientesEstudiante extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturas_pendientes_estudiante';
    }

     /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
   /* public function rules()
    {
        return [
            [['roc_id', 'est_id', 'fpe_usu_ingreso', 'fpe_usu_modifica'], 'integer'],
           // [['est_id', 'fpe_tipo_documento', 'fpe_numero_documento', 'fpe_usu_ingreso', 'fpe_estado', 'fpe_estado_logico'], 'required'],
            [['fpe_abono'], 'number'],
            [['fpe_fecha_creacion', 'fpe_fecha_modificacion'], 'safe'],
            //[['fpe_tipo_documento'], 'string', 'max' => 10],
            //[['fpe_numero_documento'], 'string', 'max' => 30],
            [['fpe_estado', 'fpe_estado_logico'], 'string', 'max' => 1],
        ];
    }
*/
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fpe_id' => 'Fpe ID',
            'roc_id' => 'Roc ID',
            'est_id' => 'Est ID',
            'fpe_tipo_documento' => 'Fpe Tipo Documento',
            'fpe_numero_documento' => 'Fpe Numero Documento',
            'fpe_usu_ingreso' => 'Fpe Usu Ingreso',
            'fpe_usu_modifica' => 'Fpe Usu Modifica',
            'fpe_estado' => 'Fpe Estado',
            'fpe_abono' => 'Fpe Abono',
            'fpe_fecha_creacion' => 'Fpe Fecha Creacion',
            'fpe_fecha_modificacion' => 'Fpe Fecha Modificacion',
            'fpe_estado_logico' => 'Fpe Estado Logico',
        ];
    }

    /***
    * Didimo Zamora
    * Colsulta de facturas pendientes del estudiante.
    */
    public static function getFcaturasPendientesEstudiante($per_id, $onlyData = false) {

        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  
                    fpe.fpe_id,
                    concat(fpe.fpe_tipo_documento,'-',roc.rpm_id) num_nof,
                    roc.roc_vencimiento as f_sus_d, 
                    roc.roc_num_cuota cuota,
                    roc.roc_costo valor_cuota,
                    ROUND((roc.roc_costo  - fpe.fpe_abono),2) as saldo ,
                    fpe.per_id,
                    fpe.roc_id,
                    fpe.fpe_estado,
                    fpe.fpe_abono abono,
                    roc.roc_id
                FROM " . $con->dbname . ".facturas_pendientes_estudiante fpe 
                INNER JOIN " . $con1->dbname . ".registro_online_cuota roc on roc.roc_id = fpe.roc_id  
                INNER JOIN " . $con2->dbname . ".persona per on   per.per_id = fpe.per_id
                INNER JOIN " . $con1->dbname . ".estudiante est on  est.per_id = per.per_id 
                WHERE 
                    per.per_id = :per_id 
                AND fpe.fpe_estado_logico = :estado
                AND roc.roc_estado_logico = :estado
                AND est.est_estado_logico = :estado
                AND per.per_estado_logico = :estado
                AND roc.roc_estado_pago = 'P'";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();

        //return $resultData;
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

     /***
    * Didimo Zamora
    * Colsulta de facturas pendientes del estudiante.
    */
    public static function getFcaturasPendientesEstudianteByFpe_id($fpe_id) {

        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  
                    fpe.fpe_id,
                    fpe.fpe_numero_documento num_nof,
                    roc.roc_vencimiento as f_sus_d, 
                    roc.roc_num_cuota cuota,
                    roc.roc_costo valor_cuota,
                    ROUND((roc.roc_costo  - fpe.fpe_abono),2) as saldo ,
                    fpe.per_id,
                    fpe.roc_id,
                    fpe.fpe_estado,
                    fpe.fpe_abono abono
                FROM " . $con->dbname . ".facturas_pendientes_estudiante fpe 
                INNER JOIN " . $con1->dbname . ".registro_online_cuota roc on roc.roc_id = fpe.roc_id  
                INNER JOIN " . $con2->dbname . ".persona per on   per.per_id = fpe.per_id
                INNER JOIN " . $con1->dbname . ".estudiante est on  est.per_id = per.per_id 
                WHERE 
                fpe.fpe_id = :fpe_id 
                AND fpe.fpe_estado_logico = :estado
                AND roc.roc_estado_logico = :estado
                AND est.est_estado_logico = :estado
                AND per.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
        //$comando->bindParam(":est_id", $est_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        //$resultData = $comando->queryAll();
         $resultData = $comando->queryOne();
        //return $resultData;
        return $resultData;

    }


    public function updateFacturaPendienteEstudianteById( 
        $fpe_id, 
        $fpe_numero_documento, 
        $fpe_usu_modifica, 
        $fpe_abono
    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $cgen_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET 
                        fpe_numero_documento  = :fpe_numero_documento, 
                        fpe_usu_modifica  = :fpe_usu_modifica,  
                        fpe_abono  = :fpe_abono 
                      WHERE 
                        fpe_id = :fpe_id AND 
                        fpe_estado_logico = ".$estado);

            $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_numero_documento", $fpe_numero_documento, \PDO::PARAM_STR);
            $comando->bindParam(":fpe_usu_modifica", $fpe_usu_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_abono", $fpe_abono, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }


     /**
     * Function findByCondition
     * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>
     *  
     */
    public function crearFacturaPendienteEstudiante($roc_id, 
        $per_id,
        $fpe_tipo_documento,
        $fpe_usu_ingreso
    ) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "fpe_estado";
        $bdet_sql = "1";

        $param_sql .= ", fpe_estado_logico";
        $bdet_sql .= ", 1";

        //$param_sql .= ", pfes_fecha_registro";
        //$bdet_sql .= ", :pfes_fecha_registro";

        $param_sql .= ", fpe_fecha_creacion";
        $bdet_sql .= ", :fpe_fecha_creacion";

        $param_sql .= ", fpe_numero_documento";
        $bdet_sql .= ", 0";

        if (isset($roc_id)) {
            $param_sql .= ", roc_id";
            $bdet_sql .= ", :roc_id";
        }
        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bdet_sql .= ", :per_id";
        }
        if (isset($fpe_tipo_documento)) {
            $param_sql .= ", fpe_tipo_documento";
            $bdet_sql .= ", :fpe_tipo_documento";
        }
        if (isset($fpe_usu_ingreso)) {
            $param_sql .= ", fpe_usu_ingreso";
            $bdet_sql .= ", :fpe_usu_ingreso";
        }


          try {
            $sql = "INSERT INTO  " . $con->dbname . ".facturas_pendientes_estudiante ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
             if (!empty((isset($roc_id)))) {
                $comando->bindParam(':roc_id', $roc_id, \PDO::PARAM_INT);
            }
           if (!empty((isset($per_id)))) {
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($fpe_tipo_documento)))) {
                $comando->bindParam(':fpe_tipo_documento', $fpe_tipo_documento, \PDO::PARAM_STR);
            }
            if (!empty((isset($fpe_usu_ingreso)))) {
                $comando->bindParam(':fpe_usu_ingreso', $fpe_usu_ingreso, \PDO::PARAM_INT);
            }
            
            $comando->bindParam(":fpe_fecha_creacion", $fecha, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.facturas_pendientes_estudiante');
            }catch (Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                return $ex;
            }



    }

    public function updateAbonoPendienteEstudianteById( 
        $fpe_id,  
        $fpe_usu_modifica, 
        $fpe_abono_rvr
    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET 
                         
                        fpe_usu_modifica  = :fpe_usu_modifica,  
                        fpe_abono  =  fpe_abono - :fpe_abono_rvr 
                        fpe_fecha_modificacion = :fpe_fecha_modificacion
                      WHERE 
                        fpe_id = :fpe_id AND 
                        fpe_estado_logico = ".$estado);

            $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_fecha_modificacion", $cgen_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":fpe_usu_modifica", $fpe_fecha_modificacion, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_abono_rvr", $fpe_abono_rvr, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    
    
    
    
    /**
     * Function findByCondition
     * @author  Didimo Zamora <analistadesarrollo03@uteg.edu.ec>
     *  
     */
    public function crearCabeceraFacturaPendienteEstudiante( 
        $per_id,
        $fpe_num_cuota,
        $fpe_valor,
        $fpe_tipo_documento,
        $fpe_usu_ingreso,
        $fpe_vencimiento
    ) {
        $con = \Yii::$app->db_facturacion;

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "fpe_estado";
        $bdet_sql = "1";

        $param_sql .= ", fpe_estado_logico";
        $bdet_sql .= ", 1";

        //$param_sql .= ", pfes_fecha_registro";
        //$bdet_sql .= ", :pfes_fecha_registro";

        $param_sql .= ", fpe_fecha_creacion";
        $bdet_sql .= ", :fpe_fecha_creacion";

        $param_sql .= ", fpe_numero_documento";
        $bdet_sql .= ", 0";

        if (isset($per_id)) {
            $param_sql .= ", per_id";
            $bdet_sql .= ", :per_id";
        }
        if (isset($fpe_num_cuota)) {
            $param_sql .= ", fpe_num_cuota";
            $bdet_sql .= ", :fpe_num_cuota";
            if($fpe_num_cuota=='1'){
                $param_sql .= ", fpe_abono";
                $bdet_sql .= ", :fpe_abono";
            }
        }
        if (isset($fpe_valor)) {
            $param_sql .= ", fpe_valor";
            $bdet_sql .= ", :fpe_valor";
        }

        if (isset($fpe_tipo_documento)) {
            $param_sql .= ", fpe_tipo_documento";
            $bdet_sql .= ", :fpe_tipo_documento";
        }
        if (isset($fpe_usu_ingreso)) {
            $param_sql .= ", fpe_usu_ingreso";
            $bdet_sql .= ", :fpe_usu_ingreso";
        }
        if (isset($fpe_vencimiento)) {
            $param_sql .= ", fpe_vencimiento";
            $bdet_sql .= ", :fpe_vencimiento";
        }


          try {
            $sql = "INSERT INTO  " . $con->dbname . ".facturas_pendientes_estudiante ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
           if (!empty((isset($per_id)))) {
                $comando->bindParam(':per_id', $per_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($fpe_tipo_documento)))) {
                $comando->bindParam(':fpe_tipo_documento', $fpe_tipo_documento, \PDO::PARAM_STR);
            }
            if (!empty((isset($fpe_usu_ingreso)))) {
                $comando->bindParam(':fpe_usu_ingreso', $fpe_usu_ingreso, \PDO::PARAM_INT);
            }
            if (!empty((isset($fpe_num_cuota)))) {
                $comando->bindParam(':fpe_num_cuota', $fpe_num_cuota, \PDO::PARAM_INT);
                 if($fpe_num_cuota=='1'){
                    $comando->bindParam(':fpe_abono', $fpe_valor, \PDO::PARAM_INT);
                 }

            }
            if (!empty((isset($fpe_valor)))) {
                $comando->bindParam(':fpe_valor', $fpe_valor, \PDO::PARAM_INT);
            }
            if (!empty((isset($fpe_vencimiento)))) {
                $comando->bindParam(':fpe_vencimiento', $fpe_vencimiento, \PDO::PARAM_STR);
            }

            $comando->bindParam(":fpe_fecha_creacion", $fecha, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.facturas_pendientes_estudiante');
            }catch (Exception $ex) {
                if ($trans !== null)
                    $trans->rollback();
                return $ex;
            }



    }



    public function updateSaldosCuotasNewRegistroOnLine( 
        $fpe_id,
        $fpe_valor,  
        $fpe_usu_modifica
    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET 
                         
                        fpe_usu_modifica  = :fpe_usu_modifica,  
                        fpe_valor  =  fpe_valor + :fpe_valor,
                        fpe_fecha_modificacion = :fpe_fecha_modificacion
                      WHERE 
                        fpe_id = :fpe_id AND 
                        fpe_estado = 1 AND
                        fpe_estado_logico = ".$estado );

            $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_fecha_modificacion", $fpe_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":fpe_usu_modifica", $fpe_usu_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_valor", $fpe_valor, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }


    /***
    * Didimo Zamora
    * Colsulta de facturas pendientes del estudiante.
    */
    public static function getFcaturasPendientesEstudianteConsolidado($per_id, $onlyData = false) {

        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  
                    fpe.fpe_id,
                    fpe.fpe_tipo_documento num_nof,
                    -- roc.roc_vencimiento as f_sus_d, 
                    -- roc.roc_num_cuota cuota,
                    -- roc.roc_costo valor_cuota,
                    -- ROUND((roc.roc_costo  - fpe.fpe_abono),2) as saldo ,
                    fpe.per_id,
                    fpe.roc_id,
                    fpe.fpe_estado,
                    fpe.fpe_abono abono,
                    -- roc.roc_id,
                    fpe.fpe_vencimiento as f_sus_d,
                    fpe.fpe_valor valor_cuota,
                    fpe.fpe_num_cuota cuota,
                    fpe.fpe_vencimiento f_sus_d,
                    ROUND((fpe.fpe_valor  - fpe.fpe_abono),2) as saldo 
                FROM " . $con->dbname . ".facturas_pendientes_estudiante fpe 
                INNER JOIN " . $con2->dbname . ".persona per on   per.per_id = fpe.per_id
                INNER JOIN " . $con1->dbname . ".estudiante est on  est.per_id = per.per_id 
                WHERE 
                    per.per_id = :per_id 
                AND ROUND((fpe.fpe_valor  - fpe.fpe_abono),2) > 0        
                AND fpe.fpe_estado_logico = :estado
                AND est.est_estado_logico = :estado
                AND per.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();

        //return $resultData;
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


      /***
    * Didimo Zamora
    * Colsulta de facturas pendientes del estudiante.
    */
    public static function getFcaturasPendientesEstudianteByFpe_id2($fpe_id) {

        $con = \Yii::$app->db_facturacion;
        $con1 = \Yii::$app->db_academico;
        $con2 = \Yii::$app->db_asgard;
        $estado = 1;

        $sql = "SELECT  
                    fpe.fpe_id,
                    fpe.fpe_tipo_documento num_nof,
                    -- roc.roc_vencimiento as f_sus_d, 
                    -- roc.roc_num_cuota cuota,
                    -- roc.roc_costo valor_cuota,
                    -- ROUND((roc.roc_costo  - fpe.fpe_abono),2) as saldo ,
                    fpe.per_id,
                    fpe.roc_id,
                    fpe.fpe_estado,
                    fpe.fpe_abono abono,
                    -- roc.roc_id,
                    fpe.fpe_vencimiento as f_sus_d,
                    fpe.fpe_valor valor_cuota,
                    fpe.fpe_num_cuota cuota,
                    fpe.fpe_vencimiento f_sus_d,
                    ROUND((fpe.fpe_valor  - fpe.fpe_abono),2) as saldo 
                FROM " . $con->dbname . ".facturas_pendientes_estudiante fpe 
                INNER JOIN " . $con2->dbname . ".persona per on   per.per_id = fpe.per_id
                INNER JOIN " . $con1->dbname . ".estudiante est on  est.per_id = per.per_id 
                WHERE 
                fpe.fpe_id = :fpe_id 
                AND fpe.fpe_estado_logico = :estado
                AND est.est_estado_logico = :estado
                AND per.per_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
        //$comando->bindParam(":est_id", $est_id, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        //$resultData = $comando->queryAll();
         $resultData = $comando->queryOne();
        //return $resultData;
        return $resultData;

    }






         //Acualiza valores de cuotas para rgistro online  de pago de matricula con tipo de'pago credito.
    public function insertarFacturacancel(
    $datafpeid,
    $dataid, //  
     $datacuota, // fpe_num_cuota = :datacuota,
      $datacosto // 

    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        //$roc_costo_new = $roc_costo_new - $desc;
        //$roc_porcentaje = 100 /  ($roc_cant_cuotas + $pagadas);
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");


        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET              
                      roc_id =  :dataid,    
                      fpe_valor = :datacosto,
                        fpe_fecha_modificacion = :fpe_fecha_modificacion                         
                      WHERE 
                        fpe_id = :datafpeid ");
            
              $comando->bindParam(":datafpeid", $datafpeid, \PDO::PARAM_INT);
           $comando->bindParam(":dataid", $dataid, \PDO::PARAM_INT);
            //$comando->bindParam(":roc_usuario_modifica", $roc_usuario_modifica, \PDO::PARAM_INT);
          //  $comando->bindParam(":datacuota", $datacuota, \PDO::PARAM_STR);
              $comando->bindParam(":datacosto", $datacosto, \PDO::PARAM_STR);
                        //$comando->bindParam(":roc_porcentaje", $roc_porcentaje, \PDO::PARAM_STR);
                // $comando->bindParam(":desc", $desc \PDO::PARAM_STR);
            $comando->bindParam(":fpe_fecha_modificacion", $fpe_fecha_modificacion, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
    
    
    public function getcurrentFacturas($roc_id){
        $con = \Yii::$app->db_facturacion;

        $sql = "  SELECT
                 a.fpe_id as fpeid                 
                from db_financiero_mbtu.facturas_pendientes_estudiantes as a
                inner join db_academico.registro_online_cuota as b
                where roc_id = :roc_id and fpe_estado ='1'
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":roc_id", $roc_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }
    
    
    public function getcurrentCuotas($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                  COUNT(*) as Cantf,
                 SUM(fpe_valor) as Costof,
                 fpe_id as fpeid
                 from db_facturacion_mbtu.facturas_pendientes_estudiante 
                where per_id = :per_id 
                and fpe_estado ='1'

                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }
    

    public function getcurrentId($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                 fpe_id as fpeid
                 from db_facturacion_mbtu.facturas_pendientes_estudiante 
                where per_id = :per_id 
                and fpe_estado ='1'

                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }
    
    
    
     public function insertarnewFacturacancel(
    $datafpeid,
    $datacuotas, // 
    $datacosto 

    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        //$roc_costo_new = $roc_costo_new - $desc;
        //$roc_porcentaje = 100 /  ($roc_cant_cuotas + $pagadas);
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");
        $datacosto= $datacosto / $datacuotas;


        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET              
                      fpe_valor = :datacosto,
                        fpe_fecha_modificacion = :fpe_fecha_modificacion                         
                      WHERE 
                        fpe_id = :datafpeid ");
            
              $comando->bindParam(":datafpeid", $datafpeid, \PDO::PARAM_INT);
          // $comando->bindParam(":dataid", $dataid, \PDO::PARAM_INT);
            //$comando->bindParam(":roc_usuario_modifica", $roc_usuario_modifica, \PDO::PARAM_INT);
          //  $comando->bindParam(":datacuota", $datacuota, \PDO::PARAM_STR);
              $comando->bindParam(":datacosto", $datacosto, \PDO::PARAM_STR);
                        //$comando->bindParam(":roc_porcentaje", $roc_porcentaje, \PDO::PARAM_STR);
                // $comando->bindParam(":desc", $desc \PDO::PARAM_STR);
            $comando->bindParam(":fpe_fecha_modificacion", $fpe_fecha_modificacion, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }


    //analistadesarrollo03
    //12-05-2021
    //Actualiza saldos de  facturapendiente estudiante
    public function updateSaldosRechazoPagoInscripcion( 
        $fpe_id,
        $fpe_valor,  
        $fpe_usu_modifica
    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET 
                         
                        fpe_usu_modifica  = :fpe_usu_modifica,  
                        fpe_abono  =  fpe_abono - :fpe_valor,
                        fpe_fecha_modificacion = :fpe_fecha_modificacion
                      WHERE 
                        fpe_id = :fpe_id AND 
                        fpe_estado = 1 AND
                        fpe_estado_logico = ".$estado );

            $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_fecha_modificacion", $fpe_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":fpe_usu_modifica", $fpe_usu_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_valor", $fpe_valor, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

     //analistadesarrollo03
    //13-05-2021
    //Actualiza saldos de  facturapendiente estudiante
    public function updateSaldosPendientesByAnulMaterias( 
        $fpe_id,
        $fpe_valor,  
        $fpe_usu_modifica
    ){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $fpe_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".facturas_pendientes_estudiante             
                      SET 
                         
                        fpe_usu_modifica  = :fpe_usu_modifica,  
                        fpe_valor  =  fpe_valor - :fpe_valor,
                        fpe_fecha_modificacion = :fpe_fecha_modificacion
                      WHERE 
                        fpe_id = :fpe_id AND 
                        fpe_estado = 1 AND
                        fpe_estado_logico = ".$estado );

            $comando->bindParam(":fpe_id", $fpe_id, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_fecha_modificacion", $fpe_fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":fpe_usu_modifica", $fpe_usu_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":fpe_valor", $fpe_valor, \PDO::PARAM_STR);

            $response = $comando->execute();

            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }



}