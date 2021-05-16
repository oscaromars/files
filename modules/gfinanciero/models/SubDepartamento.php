<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "sub_departamento".
 *
 * @property int $sdep_id
 * @property int $dep_id
 * @property string|null $sdep_nombre
 * @property int|null $sdep_usuario_ingreso
 * @property int|null $sdep_usuario_modifica
 * @property string|null $sdep_estado
 * @property string|null $sdep_fecha_creacion
 * @property string|null $sdep_fecha_modificacion
 * @property string|null $sdep_estado_logico
 *
 * @property Empleado[] $empleados
 * @property EmpleadoCargo[] $empleadoCargos
 * @property Departamentos $dep
 */
class SubDepartamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sub_departamento';
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
            [['dep_id'], 'required'],
            [['dep_id', 'sdep_usuario_ingreso', 'sdep_usuario_modifica'], 'integer'],
            [['sdep_fecha_creacion', 'sdep_fecha_modificacion'], 'safe'],
            [['sdep_nombre'], 'string', 'max' => 200],
            [['sdep_estado', 'sdep_estado_logico'], 'string', 'max' => 1],
            [['dep_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departamentos::className(), 'targetAttribute' => ['dep_id' => 'dep_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sdep_id' => financiero::t('departamento', 'Code'),
            'dep_id' => financiero::t('departamento', 'Sub Code'),
            'sdep_nombre' => financiero::t('departamento', 'Sub Department'),
            'sdep_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'sdep_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'sdep_estado' => financiero::t('gfinanciero', 'Status'),
            'sdep_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'sdep_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'sdep_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
    }

    /**
     * Gets query for [[Empleados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleados()
    {
        return $this->hasMany(Empleado::className(), ['sdep_id' => 'sdep_id']);
    }

    /**
     * Gets query for [[EmpleadoCargos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleadoCargos()
    {
        return $this->hasMany(EmpleadoCargo::className(), ['sdep_id' => 'sdep_id']);
    }

    /**
     * Gets query for [[Dep]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDep()
    {
        return $this->hasOne(Departamentos::className(), ['dep_id' => 'dep_id']);
    }
    
    public function getAllItemsGrid($search, $departamento = NULL,$dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;
        
        //// Code Begin
        if(isset($search)){
            $str_search .= "(sdep_nombre like :search) AND ";
        }
        if(isset($departamento) && $departamento !== "0"){
            $str_search .= " a.dep_id = :dep_id AND ";
        }
        $cols = "a.sdep_id Id,a.dep_id DepId,b.dep_nombre NombreDepart,a.sdep_nombre Nombre, a.sdep_fecha_creacion Fecha";
        if($export) $cols = "a.sdep_id Id,b.dep_nombre NombreDepart,a.sdep_nombre Nombre, a.sdep_fecha_creacion Fecha";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".sub_departamento a  
                        inner join ".$con->dbname.".departamentos b on a.dep_id=b.dep_id
                WHERE 
                    $str_search
                    sdep_estado = 1 AND sdep_estado_logico = 1
                ORDER BY sdep_id;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($departamento) && $departamento !== "0"){
            $comando->bindParam(":dep_id",$departamento, \PDO::PARAM_STR);
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
                    'attributes' => ['Nombre','NombreDepart', 'Estado'],
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
    
    
}
