<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "detalle_descuento_item".
 *
 * @property int $ddit_id
 * @property int $dite_id
 * @property string $ddit_descripcion
 * @property string $ddit_tipo_beneficio
 * @property double $ddit_porcentaje
 * @property double $ddit_valor
 * @property string $ddit_finicio
 * @property string $ddit_ffin
 * @property string $ddit_estado_descuento
 * @property int $ddit_usu_creacion
 * @property int $ddit_usu_modificacion
 * @property string $ddit_estado
 * @property string $ddit_fecha_creacion
 * @property string $ddit_fecha_modificacion
 * @property string $ddit_estado_logico
 *
 * @property DescuentoItem $dite
 * @property HistorialDescuentoItem[] $historialDescuentoItems
 */
class DetalleDescuentoItem extends \app\modules\financiero\components\CActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'detalle_descuento_item';
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
            [['dite_id', 'ddit_estado_descuento', 'ddit_estado'], 'required'],
            [['dite_id', 'ddit_usu_creacion', 'ddit_usu_modificacion'], 'integer'],
            [['ddit_porcentaje', 'ddit_valor'], 'number'],
            [['ddit_finicio', 'ddit_ffin', 'ddit_fecha_creacion', 'ddit_fecha_modificacion'], 'safe'],
            [['ddit_descripcion'], 'string', 'max' => 100],
            [['ddit_tipo_beneficio', 'ddit_estado_descuento', 'ddit_estado', 'ddit_estado_logico'], 'string', 'max' => 1],
            [['dite_id'], 'exist', 'skipOnError' => true, 'targetClass' => DescuentoItem::className(), 'targetAttribute' => ['dite_id' => 'dite_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'ddit_id' => 'Ddit ID',
            'dite_id' => 'Dite ID',
            'ddit_descripcion' => 'Ddit Descripcion',
            'ddit_tipo_beneficio' => 'Ddit Tipo Beneficio',
            'ddit_porcentaje' => 'Ddit Porcentaje',
            'ddit_valor' => 'Ddit Valor',
            'ddit_finicio' => 'Ddit Finicio',
            'ddit_ffin' => 'Ddit Ffin',
            'ddit_estado_descuento' => 'Ddit Estado Descuento',
            'ddit_usu_creacion' => 'Ddit Usu Creacion',
            'ddit_usu_modificacion' => 'Ddit Usu Modificacion',
            'ddit_estado' => 'Ddit Estado',
            'ddit_fecha_creacion' => 'Ddit Fecha Creacion',
            'ddit_fecha_modificacion' => 'Ddit Fecha Modificacion',
            'ddit_estado_logico' => 'Ddit Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDite() {
        return $this->hasOne(DescuentoItem::className(), ['dite_id' => 'dite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorialDescuentoItems() {
        return $this->hasMany(HistorialDescuentoItem::className(), ['ddit_id' => 'ddit_id']);
    }

    /**
     * Function consultarDesctoxitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param
     * @return  $resultData (Para obtener el id del item, filtrando por nivel de interés,
     *                       modalidad y método de ingreso.)
     */
    public function consultarDesctoxitem($ite_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT ddi.ddit_id as id, ddi.ddit_descripcion as name
                FROM " . $con->dbname . ".detalle_descuento_item ddi inner join " . $con->dbname . ".descuento_item di
                        on di.dite_id = ddi.dite_id
                WHERE di.ite_id = :ite_id
                      and ddi.ddit_estado_descuento = 'A'
                      and now() between ddi.ddit_finicio and ifnull(ddi.ddit_ffin, now())  
                      and di.dite_estado = :estado
                      and di.dite_estado_logico = :estado
                      and ddi.ddit_estado = :estado
                      and ddi.ddit_estado_logico = :estado
                ORDER BY name asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultarValdctoItem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Para obtener el valor del descuento del item.)
     */
    public function consultarValdctoItem($dite_id) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT ddit_tipo_beneficio, ddit_porcentaje, ddit_valor
                FROM " . $con->dbname . ".detalle_descuento_item ddi
                WHERE ddi.ddit_id = :dite_id
                    and ddi.ddit_estado = :estado
                    and ddi.ddit_estado_logico = :estado";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dite_id", $dite_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    /**
     * Function consultarDesctohistoriaxitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Para obtener el id del item, filtrando por nivel de interés,
     *                       modalidad y método de ingreso.)
     */
    public function consultarDesctohistoriaxitem($ite_id, $fecha) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT hdi.ddit_id as id, hdi.hdit_descripcion as name
                FROM db_facturacion.historial_descuento_item hdi inner join db_facturacion.descuento_item di on di.dite_id = hdi.dite_id
                WHERE di.ite_id = :ite_id
                          and hdi.hdit_estado_descuento = 'A'
                          and :fecha between hdi.hdit_fecha_inicio and ifnull(hdi.hdit_fecha_fin, now())  
                          and di.dite_estado = :estado
                          and di.dite_estado_logico = :estado
                          and hdi.hdit_estado = :estado
                          and hdi.hdit_estado_logico = :estado
                ORDER BY name asc";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    /**
     * Function consultarHistoricodctoXitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Para obtener el valor histórico del descuento del item.)
     */
    public function consultarHistoricodctoXitem($dite_id, $fecha) {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;
        $sql = "SELECT hdit_tipo_beneficio, hdit_porcentaje, hdit_valor
                FROM " . $con->dbname . ".historial_descuento_item hdi
                WHERE hdi.ddit_id = :dite_id
                    and :fecha between hdi.hdit_fecha_inicio and ifnull(hdi.hdit_fecha_fin, now())  
                    and hdi.hdit_estado = :estado
                    and hdi.hdit_estado_logico = :estado";         
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":dite_id", $dite_id, \PDO::PARAM_INT);
        $comando->bindParam(":fecha", $fecha, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Function consultarDescuentoXitem
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>
     * @param   
     * @return  $resultData (Para obtener el id del detalle de descuento, filtrando por nivel de interés,
     *                       modalidad e item. Es para los casos en que la unidad acadèmica es diferente de
     *                       grado y posgrado)
     */
    public function consultarDescuentoXitemUnidad($uaca_id, $mod_id, $ite_id) {        
        $con = \Yii::$app->db_facturacion;        
        $estado = 1;                 
            $sql = "SELECT d.ddit_id as id, d.ddit_descripcion as name
                    FROM  " . $con->dbname . ".item_metodo_unidad a inner join " . $con->dbname . ".descuento_item b on b.ite_id = a.ite_id
                          inner join " . $con->dbname . ".detalle_descuento_item d on d.dite_id = b.dite_id
                    WHERE a.uaca_id = :uaca_id
                          and a.mod_id = :mod_id	  
                          and a.ite_id = :ite_id
                          and d.ddit_estado_descuento = 'A'
                          and now() between d.ddit_finicio and ifnull(d.ddit_ffin, now())  
                          and a.imni_estado = :estado
                          and a.imni_estado_logico = :estado
                          and b.dite_estado = :estado
                          and b.dite_estado_logico = :estado
                          and d.ddit_estado = :estado
                          and d.ddit_estado_logico = :estado";                   
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);        
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);        
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;                
    }
}
