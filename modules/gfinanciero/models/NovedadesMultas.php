<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "novedades_multas".
 *
 * @property int $nmul_id
 * @property string $empl_codigo
 * @property string|null $nmul_observacion
 * @property float|null $nmul_valor
 * @property string|null $nmul_fecha_pago
 * @property string|null $nmul_estado_cancelado
 * @property int|null $nmul_usuario_autoriza
 * @property int|null $nmul_usuario_ingreso
 * @property int|null $nmul_usuario_modifica
 * @property string|null $nmul_estado
 * @property string|null $nmul_fecha_creacion
 * @property string|null $nmul_fecha_modificacion
 * @property string|null $nmul_estado_logico
 */
class NovedadesMultas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'novedades_multas';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empl_codigo'], 'required'],
            [['nmul_observacion'], 'string'],
            [['nmul_valor'], 'number'],
            [['nmul_fecha_pago', 'nmul_fecha_creacion', 'nmul_fecha_modificacion'], 'safe'],
            [['nmul_usuario_autoriza', 'nmul_usuario_ingreso', 'nmul_usuario_modifica'], 'integer'],
            [['empl_codigo'], 'string', 'max' => 20],
            [['nmul_estado_cancelado', 'nmul_estado', 'nmul_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nmul_id' => 'Nmul ID',
            'empl_codigo' => 'Empl Codigo',
            'nmul_observacion' => 'Nmul Observacion',
            'nmul_valor' => 'Nmul Valor',
            'nmul_fecha_pago' => 'Nmul Fecha Pago',
            'nmul_estado_cancelado' => 'Nmul Estado Cancelado',
            'nmul_usuario_autoriza' => 'Nmul Usuario Autoriza',
            'nmul_usuario_ingreso' => 'Nmul Usuario Ingreso',
            'nmul_usuario_modifica' => 'Nmul Usuario Modifica',
            'nmul_estado' => 'Nmul Estado',
            'nmul_fecha_creacion' => 'Nmul Fecha Creacion',
            'nmul_fecha_modificacion' => 'Nmul Fecha Modificacion',
            'nmul_estado_logico' => 'Nmul Estado Logico',
        ];
    }

    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $con2 = Yii::$app->db;
        $emp_id = Yii::$app->session->get("PB_idempresa");
        //// Code Begin
        if(isset($search)){
            $str_search .= "(e.empl_nombre like :search OR e.empl_apellido like :search) AND ";
        }
        $cols  = "nm.nmul_id as Id, e.empl_nombre as Nombre, e.empl_apellido as Apellido, ";
        $cols .= "de.dep_nombre as Departamento, sd.sdep_nombre as SubDepartamento, ";
        $cols .= "nm.nmul_valor as Multa, IFNULL(nm.nmul_fecha_pago, '') as FechaPago, nm.nmul_estado_cancelado as EstadoCancelado ";

        if($export){
            $cols  = "e.empl_nombre as Nombre, e.empl_apellido as Apellido, ";
            $cols .= "de.dep_nombre as Departamento, sd.sdep_nombre as SubDepartamento, ";
            $cols .= "nm.nmul_valor as Multa, IFNULL(nm.nmul_fecha_pago, '') as FechaPago, IF(nm.nmul_estado_cancelado = '1', '".financiero::t('novedadesmultas', 'Canceled')."', '".financiero::t('novedadesmultas', 'No Canceled')."') as EstadoCancelado ";
        }
        $sql = "SELECT 
                    $cols 
                FROM 
                    ".$con->dbname.".empleado AS e 
                    INNER JOIN ".$con->dbname.".sub_departamento AS sd ON sd.sdep_id = e.sdep_id 
                    INNER JOIN ".$con->dbname.".departamentos AS de ON de.dep_id = sd.dep_id 
                    INNER JOIN ".$con2->dbname.".persona AS p ON e.per_id = p.per_id 
                    INNER JOIN ".$con2->dbname.".empresa_persona AS ep ON ep.per_id = p.per_id
                    INNER JOIN ".$con->dbname.".novedades_multas AS nm ON nm.empl_codigo = e.empl_codigo 
                WHERE 
                    $str_search
                    ep.emp_id = :emp_id AND 
                    p.per_estado_logico = 1 AND p.per_estado = 1 AND 
                    e.empl_estado_logico = 1 AND e.empl_estado = 1 AND 
                    nm.nmul_estado_logico = 1 AND nm.nmul_estado = 1 
                ORDER BY nm.nmul_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $comando->bindParam(":emp_id", $emp_id, \PDO::PARAM_INT);

        $result = $comando->queryAll();

        foreach($result as $key => $value){
            $result[$key]['Multa'] = Yii::$app->params['currency'] . number_format($value['Multa'], 2, '.', ',');
            $result[$key]['FechaPago'] = (isset($result[$key]['FechaPago']) && $result[$key]['FechaPago'] != "")?(date(Yii::$app->params["dateByDefault"], strtotime($result[$key]['FechaPago']))):'';
        }

        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $result,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }

    /**
     * Return Status Values Penalty.
     *
     * @return mixed Return a Status Values Penalty
     */
    public static function getStatusValues(){
        return [
            "0" => financiero::t('novedadesmultas', 'No Canceled'), 
            "1" => financiero::t('novedadesmultas', 'Canceled'), 
        ];
    }
}
