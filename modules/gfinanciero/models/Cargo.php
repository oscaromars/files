<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "cargos".
 *
 * @property int $carg_id
 * @property string|null $carg_nombre
 * @property float|null $carg_sueldo
 * @property int|null $carg_usuario_ingreso
 * @property int|null $carg_usuario_modifica
 * @property string|null $carg_estado
 * @property string|null $carg_fecha_creacion
 * @property string|null $carg_fecha_modificacion
 * @property string|null $carg_estado_logico
 *
 * @property EmpleadoCargo[] $empleadoCargos
 */
class Cargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cargos';
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
            [['carg_sueldo'], 'number'],
            [['carg_usuario_ingreso', 'carg_usuario_modifica'], 'integer'],
            [['carg_fecha_creacion', 'carg_fecha_modificacion'], 'safe'],
            [['carg_nombre'], 'string', 'max' => 200],
            [['carg_estado', 'carg_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        
        
        return [
            'carg_id' => financiero::t('cargo', 'Charge Code'),
            'carg_nombre' => financiero::t('cargo', 'Charge Name'),
            'carg_sueldo' => financiero::t('cargo', 'Salary'),
            'carg_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'carg_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'carg_estado' => financiero::t('gfinanciero', 'Status'),
            'carg_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'carg_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'carg_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
    }

    /**
     * Gets query for [[EmpleadoCargos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleadoCargos()
    {
        return $this->hasMany(EmpleadoCargo::className(), ['carg_id' => 'carg_id']);
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
            $str_search .= "(carg_nombre like :search) AND ";
        }
              
        $cols  = "carg_id as Id, ";
        $cols .= "carg_nombre as Nombre, ";
        $cols .= "carg_sueldo as Salario ";
        
        
        if($export) $cols = "carg_nombre as Nombre, carg_sueldo as Salario ";
        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".cargos
                WHERE
                    $str_search
                    carg_estado_logico = 1 AND carg_estado = 1
                ORDER BY carg_id;";
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
        $row = self::find()->select(['carg_id'])->where(['carg_estado_logico' => '1', 'carg_estado' => '1'])->orderBy(['carg_id' => SORT_DESC])->one();
        return $row['carg_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['carg_id'])->where(['carg_estado_logico' => '1', 'carg_estado' => '1'])->orderBy(['carg_id' => SORT_DESC])->one();
        $newId = 1 + $row['carg_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }    
    
}
