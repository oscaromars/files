<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "departamentos".
 *
 * @property int $dep_id
 * @property string|null $dep_nombre
 * @property int|null $dep_usuario_ingreso
 * @property int|null $dep_usuario_modifica
 * @property string|null $dep_estado
 * @property string|null $dep_fecha_creacion
 * @property string|null $dep_fecha_modificacion
 * @property string|null $dep_estado_logico
 *
 * @property SubDepartamento[] $subDepartamentos
 */
class Departamentos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departamentos';
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
            [['dep_usuario_ingreso', 'dep_usuario_modifica'], 'integer'],
            [['dep_fecha_creacion', 'dep_fecha_modificacion'], 'safe'],
            [['dep_nombre'], 'string', 'max' => 200],
            [['dep_estado', 'dep_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dep_id' => financiero::t('departamento', 'Code'),
            'dep_nombre' => financiero::t('departamento', 'Name Department'),
            'dep_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'dep_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'dep_estado' => financiero::t('gfinanciero', 'Status'),
            'dep_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'dep_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'dep_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
    }

    /**
     * Gets query for [[SubDepartamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubDepartamentos()
    {
        return $this->hasMany(SubDepartamento::className(), ['dep_id' => 'dep_id']);
    }
    
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(dep_nombre like :search) AND ";
        }
        $cols = "dep_id as Id, dep_nombre as Nombre, dep_fecha_creacion as Fecha";
        if($export) $cols .= ", dep_estado as Estado";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".departamentos  
                WHERE 
                    $str_search
                    dep_estado = 1 AND dep_estado_logico = 1
                ORDER BY dep_id;";
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
                    'attributes' => ['Nombre', 'Estado'],
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
    public static function getLastId(){
        $row = Departamentos::find()->select(['dep_id'])->where(['dep_estado' => '1', 'dep_estado_logico' => '1'])->orderBy(['dep_id' => SORT_DESC])->one();
        return ($row<>"")?$row['dep_id']+1:1;
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemArticulo(){
        $row = LineaArticulo::find()->select(['dep_id'])->where(['dep_estado' => '1', 'dep_estado_logico' => '1'])->orderBy(['dep_id' => SORT_DESC])->one();
        $newId = 1 + $row['dep_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
    
    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    
    public static function getDepartamentos() {
        $con = \Yii::$app->db_gfinanciero;
        $sql = "SELECT dep_id Ids,dep_nombre Nombre 
                    FROM " . $con->dbname . ".departamentos
                WHERE dep_estado=1 AND dep_estado_logico=1 ";
        $comando = $con->createCommand($sql);
        //$comando->bindParam(":esp_id", $Ids, \PDO::PARAM_INT);
        return $comando->queryAll();
    }
}
