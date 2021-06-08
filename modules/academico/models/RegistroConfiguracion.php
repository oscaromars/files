<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Exception;
/**
 * This is the model class for table "registro_configuracion".
 *
 * @property int $rco_id
 * @property int $pla_id
 * @property string $rco_fecha_inicio
 * @property string $rco_fecha_fin
 * @property int $rco_num_bloques
 * @property string $rco_estado
 * @property string $rco_fecha_creacion
 * @property int $rco_usuario_modifica
 * @property string $rco_fecha_modificacion
 * @property string $rco_estado_logico
 *
 * @property Planificacion $pla
 */
class RegistroConfiguracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_configuracion';
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
            [['pla_id', 'rco_num_bloques', 'rco_fecha_fin', 'rco_fecha_inicio', 'rco_estado', 'rco_estado_logico'], 'required'],
            [['pla_id', 'rco_num_bloques', 'rco_usuario_modifica'], 'integer'],
            [['rco_fecha_inicio', 'rco_fecha_fin', 'rco_fecha_creacion', 'rco_fecha_modificacion'], 'safe'],
            [['rco_estado', 'rco_estado_logico'], 'string', 'max' => 1],
            [['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rco_id' => 'Rco ID',
            'pla_id' => 'Pla ID',
            'rco_fecha_inicio' => 'Rco Fecha Inicio',
            'rco_fecha_fin' => 'Rco Fecha Fin',
            'rco_num_bloques' => 'Num Bloques',
            'rco_estado' => 'Ron Estado',
            'rco_fecha_creacion' => 'Ron Fecha Creacion',
            'rco_usuario_modifica' => 'Ron Usuario Modifica',
            'rco_fecha_modificacion' => 'Ron Fecha Modificacion',
            'rco_estado_logico' => 'Ron Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPla()
    {
        return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
    }

    public function getRegistroConfList($periodo_academico, $mod_id, $onlyData=false){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $where = "";
        
        if($mod_id != 0)    $where .= "m.mod_id =:mod_id AND ";
        if(isset($periodo_academico) && $periodo_academico != "")   $where .= "p.pla_periodo_academico =:periodo AND ";

        $sql = "
            SELECT 
                r.rco_id AS id, 
                p.pla_periodo_academico AS PeriodoAcademico,
                m.mod_nombre AS Modalidad,
                r.rco_fecha_inicio AS Inicio,
                r.rco_fecha_fin AS Fin,
                r.rco_num_bloques AS Bloque
            FROM 
                " . $con->dbname . ".registro_configuracion as r 
                INNER JOIN " . $con->dbname . ".planificacion as p ON r.pla_id = p.pla_id
                INNER JOIN " . $con->dbname . ".modalidad as m ON m.mod_id = p.mod_id
            WHERE 
                $where
                r.rco_estado =:estado AND r.rco_estado_logico =:estado AND 
                p.pla_estado =:estado AND p.pla_estado_logico =:estado AND 
                m.mod_estado =:estado AND m.mod_estado_logico =:estado
            ORDER BY 
                r.rco_fecha_inicio DESC;
        ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if($mod_id != 0)    $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        if(isset($periodo_academico) && $periodo_academico != "")   $comando->bindParam(":periodo", $periodo_academico, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        if($onlyData)
            return $resultData;

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Periodo', 'Modalidad'],
            ],
        ]);
        return $dataProvider;
    }

    
    public function registrarCargaCartera($est_id,$cedula, $per_id, $secuencial, $forma_pago,$fecha,$in, $numero_cuota,  
    $valor_cuota, $total, $id_user){
        $con = \Yii::$app->db_facturacion;
        $con2 = \Yii::$app->db_asgard;
        $con3 = \Yii::$app->db_academico;
        $estado = 1;
        \app\models\Utilities::putMessageLogFile('modelo 1...: ');

        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        try {
        $sql="INSERT INTO " . $con->dbname . ".carga_cartera 
        (
                est_id,
                ccar_tipo_documento,
                ccar_numero_documento,
                ccar_documento_identidad,
                ccar_forma_pago,
                ccar_num_cuota,
                ccar_fecha_factura,
                ccar_fecha_vencepago,
                ccar_dias_plazo,
                ccar_valor_cuota,
                ccar_valor_factura,
                ccar_fecha_pago,
                ccar_retencion_fuente,
                ccar_retencion_iva,
                ccar_numero_retencion,
                ccar_valor_iva,
                ccar_estado_cancela,
                ccar_codigo_cobrador,
                ccar_fecha_aprueba_rechaza,
                ccar_usu_aprueba_rechaza,
                ccar_usu_ingreso,
                ccar_usu_modifica,
                ccar_estado,
                ccar_abono,
                ccar_fecha_creacion,
                ccar_fecha_modificacion,
                ccar_estado_logico)
                VALUES ( :est_id, 
                    'FE', :secuencial,
                    :cedula,
                     '$forma_pago', 
                     ('0$in / 0$numero_cuota'), :fecha_transaccion, 
                    '$fecha', NULL,
                    :valor_cuota, :total, NULL, NULL, NULL, NULL, NULL, 'C', NULL, NULL, NULL, $id_user, NULL,:estado,NULL, :fecha_transaccion, NULL ,:estado);";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":est_id", $est_id, \PDO::PARAM_STR);
        $comando->bindParam(":cedula", $cedula, \PDO::PARAM_STR);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":valor_cuota", $valor_cuota, \PDO::PARAM_STR);
        $comando->bindParam(":total", $total, \PDO::PARAM_STR);
        $comando->bindParam(":fecha_transaccion", $fecha_transaccion, \PDO::PARAM_STR);
        $comando->bindParam(":secuencial", $secuencial, \PDO::PARAM_STR);
        
        $resultData = $comando->execute();
        if ($trans !== null)
            $trans->commit();
        return $con->getLastInsertID($con->dbname . '.carga_cartera');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    public function getSecuencialCargaCartera(){
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT substring(concat('00000000',max(ccar_id)),-8) as secuencial 
                from " . $con->dbname . ".carga_cartera;";

        $comando = $con->createCommand($sql);
        
        $resultData = $comando->queryOne();

        return $resultData;
    }
    
    public function getCuotaActual($in){
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT fecha_vencimiento as 'fecha'
                from " . $con->dbname . ".fechas_vencimiento_pago 
                where cuota = $in and 
                estado_logico = 1";

        $comando = $con->createCommand($sql);
        
        $resultData = $comando->queryOne();

        return $resultData;
    }
}
