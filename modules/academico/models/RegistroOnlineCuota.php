<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "registro_online_cuota".
 *
 * @property int $roc_id
 * @property int $ron_id
 * @property int $rpm_id
 * @property string $roc_num_cuota
 * @property string $roc_vencimiento
 * @property string $roc_porcentaje
 * @property string $roc_costo
 * @property string $roc_estado
 * @property string $roc_fecha_creacion
 * @property int $roc_usuario_modifica
 * @property string $roc_fecha_modificacion
 * @property string $roc_estado_logico
 * @property string roc_estado_pago
 * @property int fpe_id
 *
 * @property RegistroOnline $ron
 * @property RegistroPagoMatricula $rpm
 */
class RegistroOnlineCuota extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_online_cuota';
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
            [['ron_id', 'rpm_id', 'roc_estado', 'roc_estado_logico','fpe_id'], 'required'],
            [['ron_id', 'rpm_id', 'roc_usuario_modifica'], 'integer'],
            [['roc_costo'], 'number'],
            [['roc_fecha_creacion', 'roc_fecha_modificacion'], 'safe'],
            [['roc_num_cuota'], 'string', 'max' => 50],
            [['roc_vencimiento'], 'string', 'max' => 100],
            [['roc_porcentaje'], 'string', 'max' => 10],
            [['roc_estado', 'roc_estado_logico','roc_estado_pago'], 'string', 'max' => 1],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
            [['rpm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroPagoMatricula::className(), 'targetAttribute' => ['rpm_id' => 'rpm_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'roc_id' => 'Roc ID',
            'ron_id' => 'Ron ID',
            'rpm_id' => 'Rpm ID',
            'fpe_id' => 'fpe_id',
            'roc_num_cuota' => 'Roc Num Cuota',
            'roc_vencimiento' => 'Roc Vencimiento',
            'roc_porcentaje' => 'Roc Porcentaje',
            'roc_costo' => 'Roc Costo',
            'roc_estado' => 'Roc Estado',
            'roc_fecha_creacion' => 'Roc Fecha Creacion',
            'roc_usuario_modifica' => 'Roc Usuario Modifica',
            'roc_fecha_modificacion' => 'Roc Fecha Modificacion',
            'roc_estado_logico' => 'Roc Estado Logico',
            'roc_estado_pago' => 'Roc Estado Pago'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpm()
    {
        return $this->hasOne(RegistroPagoMatricula::className(), ['rpm_id' => 'rpm_id']);
    }


    public function updateEstadoPagoRegistroOnLineCuota( 
        $roc_id, 
        $roc_estado_pago,
        $roc_usuario_modifica
    ){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $roc_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_online_cuota             
                      SET
                        roc_estado_pago  = :roc_estado_pago, 
                        roc_usuario_modifica = :roc_usuario_modifica,
                        roc_fecha_modificacion = :roc_fecha_modificacion                         
                      WHERE 
                        roc_id = :roc_id AND 
                        roc_estado_logico = ".$estado);

            $comando->bindParam(":roc_id", $roc_id, \PDO::PARAM_INT);
            $comando->bindParam(":roc_usuario_modifica", $roc_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":roc_estado_pago", $roc_estado_pago, \PDO::PARAM_STR);
            $comando->bindParam(":roc_fecha_modificacion", $roc_fecha_modificacion, \PDO::PARAM_STR);

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

    //Acualiza valores de cuotas para rgistro online  de pago de matricula con tipo de'pago credito.
    public function updateRegistoOnlineCuotaCosto( 
        $roc_id, 
        $roc_costo_add,
        $roc_usuario_modifica
    ){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $roc_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_online_cuota             
                      SET
                        roc_costo =  roc_costo  + :roc_costo_add,
                        roc_usuario_modifica = :roc_usuario_modifica,
                        roc_fecha_modificacion = :roc_fecha_modificacion                         
                      WHERE 
                        roc_id = :roc_id AND 
                        roc_estado_logico = ".$estado);

            $comando->bindParam(":roc_id", $roc_id, \PDO::PARAM_INT);
            $comando->bindParam(":roc_usuario_modifica", $roc_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":roc_costo_add", $roc_costo_add, \PDO::PARAM_STR);
            $comando->bindParam(":roc_fecha_modificacion", $roc_fecha_modificacion, \PDO::PARAM_STR);

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


    
    
    

public function recalcCuotas($ron_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                 COUNT(*) as Cant,
                 SUM(roc_costo) as Costo
                from db_academico.registro_online_cuota 
                where ron_id = :ron_id and roc_estado_pago ='P'
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }



    public function totalCuotas($ron_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                 COUNT(*) as Cant,
                 SUM(roc_costo) as Costo
                from db_academico.registro_online_cuota 
                where ron_id = :ron_id and roc_estado_pago !='P'
                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }




     



     //Acualiza valores de cuotas para rgistro online  de pago de matricula con tipo de'pago credito.
    public function updatecancelCuotas( 
        $pagadas,
        $desc,
        $roc_cant_cuotas,
        $roc_costo_new,
        $ron_id
    ){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $roc_costo_new = $roc_costo_new - $desc;
        //$roc_porcentaje = 100 /  ($roc_cant_cuotas + $pagadas); // 
        $tot_deuda = $roc_costo_new + $pagadastot; // 
        $roc_costo = ($roc_costo_new / $roc_cant_cuotas); // 
        $roc_porcentaje=$roc_costo / $tot_deuda * 100;
        $roc_porcentaje = round($roc_porcentaje);
        $roc_fecha_modificacion = date("Y-m-d H:i:s");
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".registro_online_cuota             
                      SET                   
                        roc_porcentaje =  concat(:roc_porcentaje, '%'), 
                        roc_costo =  :roc_costo_new / :roc_cant_cuotas,
                        roc_fecha_modificacion = :roc_fecha_modificacion                         
                      WHERE 
                        ron_id = :ron_id AND 
                        roc_estado_pago ='P' AND
                        roc_estado_logico = ".$estado);

            $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
            //$comando->bindParam(":roc_usuario_modifica", $roc_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":roc_costo_new", $roc_costo_new, \PDO::PARAM_STR);
              $comando->bindParam(":roc_cant_cuotas", $roc_cant_cuotas, \PDO::PARAM_STR);
                        $comando->bindParam(":roc_porcentaje", $roc_porcentaje, \PDO::PARAM_STR);
                // $comando->bindParam(":desc", $desc \PDO::PARAM_STR);
            $comando->bindParam(":roc_fecha_modificacion", $roc_fecha_modificacion, \PDO::PARAM_STR);

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


   
    
    public function getcurrentCuotas($ron_id){
        $con = \Yii::$app->db_academico;

        $sql = "
                 SELECT
                 roc_id as id,
                 fpe_id as fpeid,
                 roc_num_cuota as cuota,
                 roc_costo as costo,
                 roc_vencimiento as vencimiento,
                 roc_estado_pago as estadopago
                from db_academico_mbtu.registro_online_cuota 
                where ron_id = :ron_id and roc_estado_pago ='P'

                ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        
            return $resultData;
        
    

    }

    /**
    *  @since 13/05/2021 
    *  analistadesarrollo03 DZM 
    *  Consulta las saldos pendientes  por id de persona
    */
    public function getcurrentSaldoPersona($per_id){
        $con = \Yii::$app->db_academico;

        $sql = "SELECT 
                    fpe.per_id, 
                    fpe.fpe_id,
                    fpe.fpe_num_cuota,
                    fpe_valor,
                    fpe.fpe_abono,
                    fpe.fpe_vencimiento 
                FROM db_facturacion_mbtu.facturas_pendientes_estudiante fpe
                WHERE
                    fpe.per_id = :per_id  AND
                    fpe.fpe_estado = 1 AND
                    fpe.fpe_estado_logico = 1 AND
                    (fpe.fpe_valor - fpe.fpe_abono ) > 0 ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function getDataCuotasRegistroOnline($ron_id, $dataProvider = false)
    {
        $con_academico = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "
            SELECT 
                c.roc_id as Id, 
                c.roc_num_cuota as Cuota, 
                c.roc_vencimiento as Vencimiento, 
                c.roc_porcentaje as Porcentaje, 
                c.roc_costo as Price
            FROM " . $con_academico->dbname . ".registro_online_cuota as c
            WHERE c.ron_id =:ron_id
            AND c.roc_estado =:estado
            AND c.roc_estado_logico =:estado
        ";

        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(":ron_id", $ron_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        if(!$dataProvider) return $resultData;

        return new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Cuota"],
            ],
        ]);
    }



}