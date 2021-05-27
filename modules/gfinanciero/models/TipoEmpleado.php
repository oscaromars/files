<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "tipo_empleado".
 *
 * @property int $tipe_id
 * @property string|null $tipe_nombre
 * @property int|null $tipe_usuario_ingreso
 * @property int|null $tipe_usuario_modifica
 * @property string|null $tipe_estado
 * @property string|null $tipe_fecha_creacion
 * @property string|null $tipe_fecha_modificacion
 * @property string|null $tipe_estado_logico
 */
class TipoEmpleado extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipo_empleado';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_gfinanciero');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tipe_usuario_ingreso', 'tipe_usuario_modifica'], 'integer'],
            [['tipe_fecha_creacion', 'tipe_fecha_modificacion'], 'safe'],
            [['tipe_nombre'], 'string', 'max' => 200],
            [['tipe_estado', 'tipe_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'tipe_id' => 'Tipe ID',
            'tipe_nombre' => 'Tipe Nombre',
            'tipe_usuario_ingreso' => 'Tipe Usuario Ingreso',
            'tipe_usuario_modifica' => 'Tipe Usuario Modifica',
            'tipe_estado' => 'Tipe Estado',
            'tipe_fecha_creacion' => 'Tipe Fecha Creacion',
            'tipe_fecha_modificacion' => 'Tipe Fecha Modificacion',
            'tipe_estado_logico' => 'Tipe Estado Logico',
        ];
    }

    /**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
    public function getAllItemsGrid($search, $dataProvider = false, $export = false) {
        $search_cond = "%" . $search . "%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        $cols = "";
        //// Code Begin
        if (isset($search)) {
            $str_search .= "(tipe_nombre like :search) AND ";
        }
        $cols = "tipe_id as Id, ";
        $cols .= "tipe_nombre as Nombre, ";
        $cols .= "tipe_fecha_creacion as FecCre ";

        if ($export) {
            $cols = "tipe_nombre as Nombre, ";
            $cols .= "tipe_fecha_creacion as FecCre ";
        }
        $sql = "SELECT 
                    $cols
                FROM 
                    " . $con->dbname . ".tipo_empleado
                WHERE 
                    $str_search
                    tipe_estado_logico = 1                   
                ORDER BY tipe_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if (isset($search)) {
            $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        if ($dataProvider) {
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

}
