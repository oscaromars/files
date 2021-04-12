<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "tipo_permiso".
 *
 * @property int $tper_id
 * @property string|null $tper_nombre
 * @property int|null $tper_usuario_ingreso
 * @property int|null $tper_usuario_modifica
 * @property string|null $tper_estado
 * @property string|null $tper_fecha_creacion
 * @property string|null $tper_fecha_modificacion
 * @property string|null $tper_estado_logico
 *
 * @property PermisosLicencias[] $permisosLicencias
 */
class TipoPermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_permiso';
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
            [['tper_usuario_ingreso', 'tper_usuario_modifica'], 'integer'],
            [['tper_fecha_creacion', 'tper_fecha_modificacion'], 'safe'],
            [['tper_nombre'], 'string', 'max' => 200],
            [['tper_estado', 'tper_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tper_id' => financiero::t('tipopermiso', 'Permit Code'),
            'tper_nombre' => financiero::t('tipopermiso', 'Permit Name'),
            'tper_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'tper_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'tper_estado' => financiero::t('gfinanciero', 'Status'),
            'tper_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'tper_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'tper_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
    }

    /**
     * Gets query for [[PermisosLicencias]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermisosLicencias()
    {
        return $this->hasMany(PermisosLicencias::className(), ['tper_id' => 'tper_id']);
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

        //// Code Begin
        if(isset($search)){
            $str_search .= "(tper_nombre like :search) AND ";
        }
              
        $cols  = "tper_id as Id, ";
        $cols .= "tper_nombre as Nombre ";
        
        if($export) $cols = "tper_nombre as Nombre";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".tipo_permiso
                WHERE
                    $str_search
                    tper_estado_logico = 1 AND tper_estado = 1
                ORDER BY tper_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
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
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['tper_id'])->where(['tper_estado_logico' => '1', 'tper_estado' => '1'])->orderBy(['tper_id' => SORT_DESC])->one();
        return $row['tper_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['tper_id'])->where(['tper_estado_logico' => '1', 'tper_estado' => '1'])->orderBy(['tper_id' => SORT_DESC])->one();
        $newId = 1 + $row['tper_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}



