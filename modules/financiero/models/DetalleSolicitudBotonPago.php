<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "detalle_solicitud_boton_pago".
 *
 * @property int $dsbp_id
 * @property int $sbpa_id
 * @property int $ite_id
 * @property int $dsbp_cantidad
 * @property double $dsbp_precio
 * @property double $dsbp_valor_iva
 * @property double $dsbp_valor_total
 * @property string $dsbp_estado
 * @property string $dsbp_fecha_creacion
 * @property string $dsbp_fecha_modificacion
 * @property string $dsbp_estado_logico
 *
 * @property SolicitudBotonPago $sbpa
 * @property Item $ite
 */
class DetalleSolicitudBotonPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detalle_solicitud_boton_pago';
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
    public function rules()
    {
        return [
            [['sbpa_id', 'ite_id', 'dsbp_cantidad', 'dsbp_precio', 'dsbp_valor_iva', 'dsbp_valor_total', 'dsbp_estado', 'dsbp_estado_logico'], 'required'],
            [['sbpa_id', 'ite_id', 'dsbp_cantidad'], 'integer'],
            [['dsbp_precio', 'dsbp_valor_iva', 'dsbp_valor_total'], 'number'],
            [['dsbp_fecha_creacion', 'dsbp_fecha_modificacion'], 'safe'],
            [['dsbp_estado', 'dsbp_estado_logico'], 'string', 'max' => 1],
            [['sbpa_id'], 'exist', 'skipOnError' => true, 'targetClass' => SolicitudBotonPago::className(), 'targetAttribute' => ['sbpa_id' => 'sbpa_id']],
            [['ite_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['ite_id' => 'ite_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dsbp_id' => 'Dsbp ID',
            'sbpa_id' => 'Sbpa ID',
            'ite_id' => 'Ite ID',
            'dsbp_cantidad' => 'Dsbp Cantidad',
            'dsbp_precio' => 'Dsbp Precio',
            'dsbp_valor_iva' => 'Dsbp Valor Iva',
            'dsbp_valor_total' => 'Dsbp Valor Total',
            'dsbp_estado' => 'Dsbp Estado',
            'dsbp_fecha_creacion' => 'Dsbp Fecha Creacion',
            'dsbp_fecha_modificacion' => 'Dsbp Fecha Modificacion',
            'dsbp_estado_logico' => 'Dsbp Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSbpa()
    {
        return $this->hasOne(SolicitudBotonPago::className(), ['sbpa_id' => 'sbpa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIte()
    {
        return $this->hasOne(Item::className(), ['ite_id' => 'ite_id']);
    }
    
    public function insertarDetSolBotPag($con,$idsbp,$item_ids,$cantidad,$item_precio,$val_iva) {  
        $estado = 1;
        $total = $cantidad*$item_precio;
        $sql = "INSERT INTO " . $con->dbname . ".detalle_solicitud_boton_pago            
            (sbpa_id, ite_id, dsbp_cantidad, dsbp_precio, dsbp_valor_iva, dsbp_valor_total, dsbp_estado, dsbp_estado_logico) VALUES
            (:idsbp,:ite_id,:cantidad,:dsbp_precio,:dsbp_valor_iva,:dsbp_valor_total,:dsbp_estado,:dsbp_estado)";
        $command = $con->createCommand($sql);        
        $command->bindParam(":idsbp", $idsbp, \PDO::PARAM_INT);
        $command->bindParam(":ite_id", $item_ids, \PDO::PARAM_INT);
        $command->bindParam(":cantidad", $cantidad, \PDO::PARAM_INT);
        $command->bindParam(":dsbp_precio", $item_precio, \PDO::PARAM_INT);
        $command->bindParam(":dsbp_valor_total", $total, \PDO::PARAM_INT);
        $command->bindParam(":dsbp_valor_iva", $val_iva, \PDO::PARAM_INT);
        $command->bindParam(":dsbp_estado", $estado, \PDO::PARAM_STR);        
        $command->execute();
        return $con->getLastInsertID();        
    }
}
