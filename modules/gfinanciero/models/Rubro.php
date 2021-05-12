<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "rubros".
 *
 * @property int $rub_id
 * @property string|null $rub_nombre
 * @property string|null $rub_tipo
 * @property string|null $rub_cuenta_principal
 * @property string|null $rub_cuenta_provisional
 * @property int|null $rub_usuario_ingreso
 * @property int|null $rub_usuario_modifica
 * @property string|null $rub_estado
 * @property string|null $rub_fecha_creacion
 * @property string|null $rub_fecha_modificacion
 * @property string|null $rub_estado_logico
 */
class Rubro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rubros';
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
            [['rub_usuario_ingreso', 'rub_usuario_modifica'], 'integer'],
            [['rub_fecha_creacion', 'rub_fecha_modificacion'], 'safe'],
            [['rub_nombre'], 'string', 'max' => 200],
            [['rub_tipo', 'rub_estado', 'rub_estado_logico'], 'string', 'max' => 1],
            [['rub_cuenta_principal', 'rub_cuenta_provisional'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rub_id' => financiero::t('rubro', 'Heading Code'),
            'rub_nombre' => financiero::t('rubro', 'Heading Name'),
            'rub_tipo' => financiero::t('rubro', 'Type Heading'),
            'rub_cuenta_principal' => financiero::t('rubro', 'Main Account'),
            'rub_cuenta_provisional' => financiero::t('rubro', 'Provisional Account'),
            'rub_usuario_ingreso' => financiero::t('gfinanciero', 'User Creates'),
            'rub_usuario_modifica' => financiero::t('gfinanciero', 'User Modifies'),
            'rub_estado' => financiero::t('gfinanciero', 'Status'),
            'rub_fecha_creacion' => financiero::t('gfinanciero', 'Creation Date'),
            'rub_fecha_modificacion' => financiero::t('gfinanciero', 'Modification Date'),
            'rub_estado_logico' => financiero::t('gfinanciero', 'Logic Status'),
        ];
    }
/**
     * Get all items of Model by params to filter data.
     *
     * @param  string $search   Search Item Name
     * @param  string $type     Type Item Name
     * @param  bool $dataProvider   Param to get a DataProvider or a Record Array
     * @return mixed Return a Record Array or DataProvider
     */
     public function getAllItemsGrid($search, $type = NULL, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(rub_nombre like :search) AND ";
        }
        if(isset($type) && $type !== "0"){
            $str_search .= " rub_tipo = :type AND ";
        }
              
        $cols  = "R.rub_id as Id, ";
        $cols .= "R.rub_nombre as Nombre, ";
        $cols .= "R.rub_tipo as Tipo, ";
        $cols .= "IFNULL (CONCAT(C1.NOM_CTA,' - ',R.rub_cuenta_principal),'-') as Cprincipal, ";
        $cols .= "IFNULL (CONCAT(C2.NOM_CTA,' - ',R.rub_cuenta_provisional),'-') as Cprovisional ";        

        if($export){ 
                $cols   = "R.rub_nombre as Nombre,";
                $cols  .= " R.rub_tipo as Tipo,";
                $cols  .= " IFNULL (CONCAT(C1.NOM_CTA,' - ',R.rub_cuenta_principal),'-') as Cprincipal,";
                $cols  .= " IFNULL (CONCAT(C2.NOM_CTA,' - ',R.rub_cuenta_provisional),'-') as Cprovisional ";
        }

        $sql = "SELECT
                    $cols
                FROM
                    ".$con->dbname.".rubros R
                LEFT JOIN   ".$con->dbname.".CATALOGO C1 on C1.COD_CTA=R.rub_cuenta_principal AND C1.EST_LOG=1 AND C1.EST_DEL=1 
                LEFT JOIN   ".$con->dbname.".CATALOGO C2 on C2.COD_CTA=R.rub_cuenta_provisional AND C2.EST_LOG=1 AND C2.EST_DEL=1                      
                    
                WHERE
                    $str_search
                    R.rub_estado_logico = 1 AND R.rub_estado = 1                   
                    
                ORDER BY R.rub_id;";
       

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($type) && $type !== "0"){
            $comando->bindParam(":type",$type, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        foreach($result as $key => $value){
            if($value['Tipo'] == "I"){
                $result[$key]['Tipo'] = financiero::t('rubro', 'Entry');
            }else if($value['Tipo'] == "E"){
                $result[$key]['Tipo'] = financiero::t('rubro', 'Egress');
            }
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
     * Get all types of Transactions Item.
     *
     * @return mixed Return an Array of types Transactions
     */
    public static function getTypesRubros(){
        $arr_tipo = [
            'I' => financiero::t('rubro', 'Entry'),
            'E' => financiero::t('rubro', 'Egress'),
        ];
        return $arr_tipo;
    }

    /**
     * Get Last Id Item Record
     *
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['rub_id'])->where(['rub_estado_logico' => '1', 'rub_estado' => '1'])->orderBy(['rub_id' => SORT_DESC])->one();
        return $row['rub_id'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['rub_id'])->where(['rub_estado_logico' => '1', 'rub_estado' => '1'])->orderBy(['rub_id' => SORT_DESC])->one();
        $newId = 1 + $row['rub_id'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}

