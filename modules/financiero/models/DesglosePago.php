<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "desglose_pago".
 *
 * @property int $dpag_id
 * @property int $opag_id
 * @property int $ite_id
 * @property double $dpag_subtotal
 * @property double $dpag_iva
 * @property double $dpag_total
 * @property string $dpag_fecha_ini_vigencia
 * @property string $dpag_fecha_fin_vigencia
 * @property string $dpag_estado_pago
 * @property int $dpag_usu_ingreso
 * @property int $dpag_usu_modifica
 * @property string $dpag_estado
 * @property string $dpag_fecha_creacion
 * @property string $dpag_fecha_modificacion
 * @property string $dpag_estado_logico
 *
 * @property OrdenPago $opag
 * @property RegistroPago[] $registroPagos
 */
class DesglosePago extends \app\modules\financiero\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'desglose_pago';
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
            [['opag_id', 'dpag_subtotal', 'dpag_iva', 'dpag_total', 'dpag_usu_ingreso', 'dpag_estado', 'dpag_estado_logico'], 'required'],
            [['opag_id', 'ite_id', 'dpag_usu_ingreso', 'dpag_usu_modifica'], 'integer'],
            [['dpag_subtotal', 'dpag_iva', 'dpag_total'], 'number'],
            [['dpag_fecha_ini_vigencia', 'dpag_fecha_fin_vigencia', 'dpag_fecha_creacion', 'dpag_fecha_modificacion'], 'safe'],
            [['dpag_estado_pago', 'dpag_estado', 'dpag_estado_logico'], 'string', 'max' => 1],
            [['opag_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrdenPago::className(), 'targetAttribute' => ['opag_id' => 'opag_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dpag_id' => 'Dpag ID',
            'opag_id' => 'Opag ID',
            'ite_id' => 'Ite ID',
            'dpag_subtotal' => 'Dpag Subtotal',
            'dpag_iva' => 'Dpag Iva',
            'dpag_total' => 'Dpag Total',
            'dpag_fecha_ini_vigencia' => 'Dpag Fecha Ini Vigencia',
            'dpag_fecha_fin_vigencia' => 'Dpag Fecha Fin Vigencia',
            'dpag_estado_pago' => 'Dpag Estado Pago',
            'dpag_usu_ingreso' => 'Dpag Usu Ingreso',
            'dpag_usu_modifica' => 'Dpag Usu Modifica',
            'dpag_estado' => 'Dpag Estado',
            'dpag_fecha_creacion' => 'Dpag Fecha Creacion',
            'dpag_fecha_modificacion' => 'Dpag Fecha Modificacion',
            'dpag_estado_logico' => 'Dpag Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpag()
    {
        return $this->hasOne(OrdenPago::className(), ['opag_id' => 'opag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegistroPagos()
    {
        return $this->hasMany(RegistroPago::className(), ['dpag_id' => 'dpag_id']);
    }

    /**
     * Function actualizaDesglosepago (Actualiza tabla desglose pago.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param
     * @return
     */
    public function actualizaDesglosepago($opag_id, $dpag_estado_pago, $ite_id, $dpag_subtotal, $dpag_total, $dpag_usu_modifica) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $dpag_fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);

        $comando = $con->createCommand
                ("UPDATE " . $con->dbname . ".orden_pago
                SET
                    ite_id = :ite_id,
                    dpag_subtotal = :dpag_subtotal,
                    dpag_total = :dpag_total,
                    dpag_fecha_modificacion = :dpag_fecha_modificacion,
                    dpag_usu_modifica = :dpag_usu_modifica
                WHERE opag_id = :opag_id AND
                      dpag_estado_pago = :dpag_estado_pago
                      dpag_estado =:estado AND
                      dpag_estado_logico = :estado");

        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":opag_id", $opag_id, \PDO::PARAM_INT);
        $comando->bindParam(":dpag_estado_pago", $dpag_estado_pago, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $comando->bindParam(":dpag_subtotal", $dpag_subtotal, \PDO::PARAM_STR);
        $comando->bindParam(":dpag_total", $dpag_total, \PDO::PARAM_STR);
        $comando->bindParam(":dpag_fecha_modificacion", $dpag_fecha_modificacion, \PDO::PARAM_STR);
        $comando->bindParam(":dpag_usu_modifica", $dpag_usu_modifica, \PDO::PARAM_STR);
        $response = $comando->execute();

        return $response;
    }
}
