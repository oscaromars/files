<?php
namespace app\modules\financiero\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "item".
 *
 * @property int $ite_id
 * @property int $scat_id
 * @property string $ite_nombre
 * @property string $ite_descripcion
 * @property string $ite_codigo
 * @property string $ite_ruta_imagen
 * @property int $ite_usu_ingreso
 * @property int $ite_usu_modifica
 * @property string $ite_estado
 * @property string $ite_fecha_creacion
 * @property string $ite_fecha_modificacion
 * @property string $ite_estado_logico
 *
 * @property DetalleComprobante[] $detalleComprobantes
 * @property DetalleSolicitudGeneral[] $detalleSolicitudGenerals
 * @property HistorialDescuentoItem[] $historialDescuentoItems
 * @property HistorialItemPrecio[] $historialItemPrecios
 * @property SubCategoria $scat
 * @property ItemMetodoNivel[] $itemMetodoNivels
 * @property ItemPrecio[] $itemPrecios
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scat_id', 'ite_nombre', 'ite_descripcion', 'ite_codigo', 'ite_usu_ingreso', 'ite_estado', 'ite_estado_logico'], 'required'],
            [['scat_id', 'ite_usu_ingreso', 'ite_usu_modifica'], 'integer'],
            [['ite_fecha_creacion', 'ite_fecha_modificacion'], 'safe'],
            [['ite_nombre'], 'string', 'max' => 200],
            [['ite_descripcion', 'ite_ruta_imagen'], 'string', 'max' => 500],
            [['ite_codigo'], 'string', 'max' => 5],
            [['ite_estado', 'ite_estado_logico'], 'string', 'max' => 1],
            [['scat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SubCategoria::className(), 'targetAttribute' => ['scat_id' => 'scat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ite_id' => 'Ite ID',
            'scat_id' => 'Scat ID',
            'ite_nombre' => 'Ite Nombre',
            'ite_descripcion' => 'Ite Descripcion',
            'ite_codigo' => 'Ite Codigo',
            'ite_ruta_imagen' => 'Ite Ruta Imagen',
            'ite_usu_ingreso' => 'Ite Usu Ingreso',
            'ite_usu_modifica' => 'Ite Usu Modifica',
            'ite_estado' => 'Ite Estado',
            'ite_fecha_creacion' => 'Ite Fecha Creacion',
            'ite_fecha_modificacion' => 'Ite Fecha Modificacion',
            'ite_estado_logico' => 'Ite Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleComprobantes()
    {
        return $this->hasMany(DetalleComprobante::className(), ['ite_id' => 'ite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleSolicitudGenerals()
    {
        return $this->hasMany(DetalleSolicitudGeneral::className(), ['ite_id' => 'ite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorialDescuentoItems()
    {
        return $this->hasMany(HistorialDescuentoItem::className(), ['ite_id' => 'ite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistorialItemPrecios()
    {
        return $this->hasMany(HistorialItemPrecio::className(), ['ite_id' => 'ite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScat()
    {
        return $this->hasOne(SubCategoria::className(), ['scat_id' => 'scat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemMetodoNivels()
    {
        return $this->hasMany(ItemMetodoNivel::className(), ['ite_id' => 'ite_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemPrecios()
    {
        return $this->hasMany(ItemPrecio::className(), ['ite_id' => 'ite_id']);
    }
    
    
    public function listarItems() {
        $con = \Yii::$app->db_facturacion;
        $estado = 1;

        $sql = "SELECT 	s.scat_id,
                        i.ite_id,
                        scat_nombre subcategoria,
                        ite_nombre item
                FROM " . $con->dbname . ".item i inner join " . $con->dbname . ".sub_categoria s on s.scat_id = i.scat_id
                WHERE i.ite_estado = :estado
                    and i.ite_estado_logico = :estado
                    and s.scat_estado = :estado
                    and s.scat_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        

        $resultData = $comando->queryall();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);
        return $dataProvider;
    }
    
    
    public function getPrecios($con, $ite_id) {
        $estado = 1;        
        $sql= "select ipre_precio             
               from " . $con->dbname . ".item_precio 
               where ite_id = :ite_id
               and ipre_estado_precio = 'A'
               and ipre_estado = :estado
               and ipre_estado_logico = :estado";
                    
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ite_id", $ite_id, \PDO::PARAM_INT);
        
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
