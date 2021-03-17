<?php

namespace app\modules\financiero\models;
use yii\data\ArrayDataProvider;
use Yii;
use app\models\Utilities;

/**
 * This is the model class for table "cruce".
 *
 * @property int $cru_id
 * @property int $est_id
 * @property int $pfes_id
 * @property string $cru_comprobante
 * @property string|null $cru_fecha_comprobante
 * @property float $cru_saldo_favor_inicial
 * @property float $cru_saldo_favor
 * @property string $cru_estado_cruce
 * @property int $cru_usu_ingreso
 * @property int|null $cru_usu_modifica
 * @property string $cru_estado
 * @property string $cru_fecha_creacion
 * @property string|null $cru_fecha_modificacion
 * @property string $cru_estado_logico
 *
 * @property PagosFacturaEstudiante $pfes
 * @property DetalleCruce[] $detalleCruces
 */
class Cruce extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cruce';
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
            [['est_id', 'pfes_id', 'cru_comprobante', 'cru_saldo_favor_inicial', 'cru_saldo_favor', 'cru_estado_cruce', 'cru_usu_ingreso', 'cru_estado', 'cru_estado_logico'], 'required'],
            [['est_id', 'pfes_id', 'cru_usu_ingreso', 'cru_usu_modifica'], 'integer'],
            [['cru_fecha_comprobante', 'cru_fecha_creacion', 'cru_fecha_modificacion'], 'safe'],
            [['cru_saldo_favor_inicial', 'cru_saldo_favor'], 'number'],
            [['cru_comprobante'], 'string', 'max' => 30],
            [['cru_estado_cruce'], 'string', 'max' => 3],
            [['cru_estado', 'cru_estado_logico'], 'string', 'max' => 1],
            [['pfes_id'], 'exist', 'skipOnError' => true, 'targetClass' => PagosFacturaEstudiante::className(), 'targetAttribute' => ['pfes_id' => 'pfes_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cru_id' => 'Cru ID',
            'est_id' => 'Est ID',
            'pfes_id' => 'Pfes ID',
            'cru_comprobante' => 'Cru Comprobante',
            'cru_fecha_comprobante' => 'Cru Fecha Comprobante',
            'cru_saldo_favor_inicial' => 'Cru Saldo Favor Inicial',
            'cru_saldo_favor' => 'Cru Saldo Favor',
            'cru_estado_cruce' => 'Cru Estado Cruce',
            'cru_usu_ingreso' => 'Cru Usu Ingreso',
            'cru_usu_modifica' => 'Cru Usu Modifica',
            'cru_estado' => 'Cru Estado',
            'cru_fecha_creacion' => 'Cru Fecha Creacion',
            'cru_fecha_modificacion' => 'Cru Fecha Modificacion',
            'cru_estado_logico' => 'Cru Estado Logico',
        ];
    }

    /**
     * Gets query for [[Pfes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPfes()
    {
        return $this->hasOne(PagosFacturaEstudiante::className(), ['pfes_id' => 'pfes_id']);
    }

    /**
     * Gets query for [[DetalleCruces]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleCruces()
    {
        return $this->hasMany(DetalleCruce::className(), ['cru_id' => 'cru_id']);
    }

    /**
     * Function insertarCruce
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return cru_id
     */
    public function insertarCruce($est_id, $pfes_id, $cru_comprobante, $cru_fecha_comprobante, $cru_saldo_favor_inicial, $cru_saldo_favor, $cru_estado_cruce, $cru_usu_ingreso) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "cru_estado";
        $bdet_sql = "1";

        $param_sql .= ", cru_estado_logico";
        $bdet_sql .= ", 1";

        $param_sql .= ", cru_fecha_creacion";
        $bdet_sql .= ", :cru_fecha_creacion";

        if (isset($est_id)) {
            $param_sql .= ", est_id";
            $bdet_sql .= ", :est_id";
        }
        if (isset($pfes_id)) {
            $param_sql .= ", pfes_id";
            $bdet_sql .= ", :pfes_id";
        }
        if (isset($cru_comprobante)) {
            $param_sql .= ", cru_comprobante";
            $bdet_sql .= ", :cru_comprobante";
        }
        if (isset($cru_fecha_comprobante)) {
            $param_sql .= ", cru_fecha_comprobante";
            $bdet_sql .= ", :cru_fecha_comprobante";
        }
        if (isset($cru_saldo_favor_inicial)) {
            $param_sql .= ", cru_saldo_favor_inicial";
            $bdet_sql .= ", :cru_saldo_favor_inicial";
        }
        if (isset($cru_saldo_favor)) {
            $param_sql .= ", cru_saldo_favor";
            $bdet_sql .= ", :cru_saldo_favor";
        }
        if (isset($cru_estado_cruce)) {
            $param_sql .= ", cru_estado_cruce";
            $bdet_sql .= ", :cru_estado_cruce";
        }
        if (isset($cru_usu_ingreso)) {
            $param_sql .= ", cru_usu_ingreso";
            $bdet_sql .= ", :cru_usu_ingreso";
        }  
        try {
            $sql = "INSERT INTO " . $con->dbname . ".cruce ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
            if (isset($est_id)) {
                $comando->bindParam(':est_id', $est_id, \PDO::PARAM_INT);
            }
            if (isset($pfes_id)) {
                $comando->bindParam(':pfes_id', $pfes_id, \PDO::PARAM_INT);
            }
            if (isset($cru_comprobante)) {
                $comando->bindParam(':cru_comprobante', $cru_comprobante, \PDO::PARAM_STR);
            }
            if (!empty((isset($cru_fecha_comprobante)))) {
                $comando->bindParam(':cru_fecha_comprobante', $cru_fecha_comprobante, \PDO::PARAM_STR);
            }
            if (!empty((isset($cru_saldo_favor_inicial)))) {
                $comando->bindParam(':cru_saldo_favor_inicial', $cru_saldo_favor_inicial, \PDO::PARAM_STR);
            }
            if (!empty((isset($cru_saldo_favor)))) {
                $comando->bindParam(':cru_saldo_favor', $cru_saldo_favor, \PDO::PARAM_STR);
            }           
            if (!empty((isset($cru_estado_cruce)))) {
                $comando->bindParam(':cru_estado_cruce', $cru_estado_cruce, \PDO::PARAM_STR);
            }
            if (!empty((isset($cru_usu_ingreso)))) {
                $comando->bindParam(':cru_usu_ingreso', $cru_usu_ingreso, \PDO::PARAM_INT);
            }
            $comando->bindParam(":cru_fecha_creacion", $fecha, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.cruce');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return $ex;
        }
    }

     /**
     * Function insertarDetallecruce
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return dcru_id
     */
    public function insertarDetallecruce($cru_id, $dcru_comprobante_cruce, $dcru_fecha_comprobante_cruce, $dcru_valor_cruce, $dcru_observacion, $dcru_usu_ingreso) {
        $con = \Yii::$app->db_facturacion;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $fecha = date(Yii::$app->params["dateTimeByDefault"]);
        $param_sql = "dcru_estado";
        $bdet_sql = "1";

        $param_sql .= ", dcru_estado_logico";
        $bdet_sql .= ", 1";

        $param_sql .= ", dcru_fecha_creacion";
        $bdet_sql .= ", :dcru_fecha_creacion";

        if (isset($cru_id)) {
            $param_sql .= ", cru_id";
            $bdet_sql .= ", :cru_id";
        }
        if (isset($dcru_comprobante_cruce)) {
            $param_sql .= ", dcru_comprobante_cruce";
            $bdet_sql .= ", :dcru_comprobante_cruce";
        }
        if (isset($dcru_fecha_comprobante_cruce)) {
            $param_sql .= ", dcru_fecha_comprobante_cruce";
            $bdet_sql .= ", :dcru_fecha_comprobante_cruce";
        }
        if (isset($dcru_valor_cruce)) {
            $param_sql .= ", dcru_valor_cruce";
            $bdet_sql .= ", :dcru_valor_cruce";
        }
        if (isset($dcru_observacion)) {
            $param_sql .= ", dcru_observacion";
            $bdet_sql .= ", :dcru_observacion";
        }
        if (isset($dcru_usu_ingreso)) {
            $param_sql .= ", dcru_usu_ingreso";
            $bdet_sql .= ", :dcru_usu_ingreso";
        }        
        try {
            $sql = "INSERT INTO " . $con->dbname . ".detalle_cruce ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);
            if (isset($cru_id)) {
                $comando->bindParam(':cru_id', $cru_id, \PDO::PARAM_INT);
            }
            if (isset($dcru_comprobante_cruce)) {
                $comando->bindParam(':dcru_comprobante_cruce', $dcru_comprobante_cruce, \PDO::PARAM_STR);
            }
            if (isset($dcru_fecha_comprobante_cruce)) {
                $comando->bindParam(':dcru_fecha_comprobante_cruce', $dcru_fecha_comprobante_cruce, \PDO::PARAM_STR);
            }
            if (!empty((isset($dcru_valor_cruce)))) {
                $comando->bindParam(':dcru_valor_cruce', $dcru_valor_cruce, \PDO::PARAM_STR);
            }
            if (!empty((isset($dcru_observacion)))) {
                $comando->bindParam(':dcru_observacion', ucfirst(mb_strtolower($dcru_observacion, 'UTF-8')), \PDO::PARAM_STR);
            }
            if (!empty((isset($dcru_usu_ingreso)))) {
                $comando->bindParam(':dcru_usu_ingreso', $dcru_usu_ingreso, \PDO::PARAM_INT);
            }
            $comando->bindParam(":dcru_fecha_creacion", $fecha, \PDO::PARAM_STR);
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.detalle_cruce');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return $ex;
        }
    }

    /**
     * Function consultarSaldos
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>;
     * @param
     * @return cru_id
     */
    public function consultarSaldos($arrFiltro = array(), $onlyData = false){
        $con    = \Yii::$app->db_facturacion;
        $con1   = \Yii::$app->db_asgard;
        $con2   = \Yii::$app->db_academico;

        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "inte.cru_fecha_creacion >= :fec_ini AND ";
                $str_search .= "inte.cru_fecha_creacion <= :fec_fin AND ";
            }
        }
        $sql = "SELECT cru.cru_id,
                       per.per_id,
                       cru.cru_fecha_creacion,
                       concat(ifnull(per.per_pri_nombre,''),' ',ifnull(per.per_seg_nombre,'')) as nombres,
                       concat(ifnull(per.per_pri_apellido,''),' ',ifnull(per.per_seg_apellido,'')) as apellidos, 
                       cru.cru_saldo_favor_inicial,
                       cru.cru_saldo_favor
                  FROM ". $con->dbname  .".cruce cru  
            INNER JOIN ". $con2->dbname .".estudiante as est on est.est_id = cru.est_id
            INNER JOIN ". $con1->dbname .".persona    as per on per.per_id = est.per_id
                 WHERE cru_estado = :cru_estado";
        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":cru_estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            $empresa = $arrFiltro["company"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $comando->bindParam(":emp_id", $empresa, \PDO::PARAM_STR);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'DNI',
                    'nombres',
                    'apellidos',
                    'empresa',
                ],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }//function consultarSaldos

    /**
     * Function consultarCruces
     * @author  Galo Aguirre <analistadesarrollo06@uteg.edu.ec>;
     * @param
     * @return cru_id
     */
    public function consultarCruces($dcru_id, $arrFiltro = array(), $onlyData = false){
        $con    = \Yii::$app->db_facturacion;
        $con1   = \Yii::$app->db_asgard;
        $con2   = \Yii::$app->db_academico;

        $estado = 1;
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $str_search = "(per.per_pri_nombre like :search OR ";
            $str_search .= "per.per_seg_nombre like :search OR ";
            $str_search .= "per.per_pri_apellido like :search OR ";
            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $str_search .= "inte.cru_fecha_creacion >= :fec_ini AND ";
                $str_search .= "inte.cru_fecha_creacion <= :fec_fin AND ";
            }
        }
        $sql = "SELECT *
                  FROM ". $con->dbname  .".detalle_cruce dcru
                 WHERE dcru.dcru_id            = :dcru_id
                   AND dcru.dcru_estado        = :cru_estado
                   AND dcru.dcru_estado_logico = :cru_estado";

        
        $comando = $con->createCommand($sql);
        $comando->bindParam(":dcru_id", $dcru_id, \PDO::PARAM_STR);
        $comando->bindParam(":cru_estado", $estado, \PDO::PARAM_STR);

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            $search_cond = "%" . $arrFiltro["search"] . "%";
            $fecha_ini = $arrFiltro["f_ini"] . " 00:00:00";
            $fecha_fin = $arrFiltro["f_fin"] . " 23:59:59";

            if ($arrFiltro['f_ini'] != "" && $arrFiltro['f_fin'] != "") {
                $comando->bindParam(":fec_ini", $fecha_ini, \PDO::PARAM_STR);
                $comando->bindParam(":fec_fin", $fecha_fin, \PDO::PARAM_STR);
            }
            $empresa = $arrFiltro["company"];
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            if ($arrFiltro['company'] != "" && $arrFiltro['company'] > 0) {
                $comando->bindParam(":emp_id", $empresa, \PDO::PARAM_STR);
            }
        }
        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'DNI',
                    'nombres',
                    'apellidos',
                    'empresa',
                ],
            ],
        ]);

        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }//function consultarSaldos
}
