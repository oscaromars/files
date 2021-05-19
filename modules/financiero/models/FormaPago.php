<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "forma_pago".
 *
 * @property int $fpag_id
 * @property string $fpag_nombre
 * @property string $fpag_descripcion
 * @property string $fpag_distintivo
 * @property int $fpag_usu_ingreso
 * @property int $fpag_usu_modifica
 * @property string $fpag_estado
 * @property string $fpag_fecha_creacion
 * @property string $fpag_fecha_modificacion
 * @property string $fpag_estado_logico
 *
 * @property InfoCargaPrepago[] $infoCargaPrepagos
 * @property RegistroPago[] $registroPagos
 * @property RegistroPagoFactura[] $registroPagoFacturas
 */
class FormaPago extends \app\modules\financiero\components\CActiveRecord 
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forma_pago';
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
    public function rules()
    {
        return [
            [['fpag_nombre', 'fpag_descripcion', 'fpag_usu_ingreso', 'fpag_estado'], 'required'],
            [['fpag_usu_ingreso', 'fpag_usu_modifica'], 'integer'],
            [['fpag_fecha_creacion', 'fpag_fecha_modificacion'], 'safe'],
            [['fpag_nombre'], 'string', 'max' => 200],
            [['fpag_descripcion'], 'string', 'max' => 500],
            [['fpag_distintivo', 'fpag_estado', 'fpag_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fpag_id' => 'Fpag ID',
            'fpag_nombre' => 'Fpag Nombre',
            'fpag_descripcion' => 'Fpag Descripcion',
            'fpag_distintivo' => 'Fpag Distintivo',
            'fpag_usu_ingreso' => 'Fpag Usu Ingreso',
            'fpag_usu_modifica' => 'Fpag Usu Modifica',
            'fpag_estado' => 'Fpag Estado',
            'fpag_fecha_creacion' => 'Fpag Fecha Creacion',
            'fpag_fecha_modificacion' => 'Fpag Fecha Modificacion',
            'fpag_estado_logico' => 'Fpag Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfoCargaPrepagos()
    {
        return $this->hasMany(InfoCargaPrepago::className(), ['fpag_id' => 'fpag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPagos()
    {
        return $this->hasMany(RegistroPago::className(), ['fpag_id' => 'fpag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPagoFacturas()
    {
        return $this->hasMany(RegistroPagoFactura::className(), ['fpag_id' => 'fpag_id']);
    }
    
    
     /**
     * Function formas de pago
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarFormaPago() {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT fp.fpag_id AS id, fp.fpag_nombre AS value  
                FROM " . $con->dbname . ".forma_pago fp 
                WHERE  fp.fpag_estado_logico = :estado AND
                       fp.fpag_estado = :estado ";                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

    /**
     * Function formas de pago
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarFormaPagosaldo() {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT fp.fpag_id AS id, fp.fpag_nombre AS value  
                FROM " . $con->dbname . ".forma_pago fp 
                WHERE  fp.fpag_estado_logico = :estado AND
                       fp.fpag_estado = :estado and fp.fpag_id in (4,5,1)";                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

    /**
     * Function formas de pago
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarBancos() {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT bc.ban_id AS id, bc.ban_nombre AS value  
                FROM " . $con->dbname . ".bancos bc
                WHERE  bc.ban_estado_logico = :estado AND
                       bc.ban_estado = :estado";                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

    /**
     * Function formas de pago
     * @author Giovanni Vergara <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return
     */
    public function consultarReferenciaBancos($rban_referencia,$ban_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT rb.ban_id AS id, 
                       rb.rban_referencia AS value 
                  FROM " . $con->dbname . ".referencia_bancos rb
                 WHERE rb.rban_estado_logico = :estado
                   AND rb.rban_estado = :estado
                   AND rb.rban_referencia = :rban_referencia
                   AND rb.ban_id = :ban_id";                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":rban_referencia", $rban_referencia, \PDO::PARAM_STR);
        $comando->bindParam(":ban_id", $ban_id, \PDO::PARAM_STR);
        $resultData = $comando->queryall();
        return $resultData;
    }

}
