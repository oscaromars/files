<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
/**
 * This is the model class for table "MG0020".
 *
 * @property string $COD_MED
 * @property string|null $N_A_MED
 * @property string|null $NOM_MED
 * @property float|null $FAC_CON
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class UnidadMedida extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0020';
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
            [['COD_MED'], 'required'],
            [['FAC_CON', 'REG_ASO'], 'number'],
            [['FEC_SIS'], 'safe'],
            [['COD_MED'], 'string', 'max' => 2],
            [['N_A_MED'], 'string', 'max' => 4],
            [['NOM_MED'], 'string', 'max' => 30],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_MED'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_MED' => financiero::t('unidadmedida', 'Code'),
            'N_A_MED' => financiero::t('unidadmedida', 'Term'),
            'NOM_MED' => financiero::t('unidadmedida', 'Item Name'),
            'FAC_CON' => financiero::t('unidadmedida', 'Conversion Factor '),
            'REG_ASO' => 'Reg Aso',                  
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }
    
     public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(NOM_MED like :search) AND ";
        }
        $cols = "COD_MED as Id,N_A_MED as Termino, NOM_MED as Nombre,FAC_CON as Factor, FEC_SIS as Fecha";
        if($export) $cols = "COD_MED as Id,N_A_MED as Termino, NOM_MED as Nombre";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".MG0020  
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_MED;";
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
                    'attributes' => ['Termino','Nombre', 'Estado'],
                ],
            ]);
            return $dataProvider;
        }
        return $result;
    }

    /**
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "MG0020";
        $arr_data['cols'] = [
            'COD_MED', 
            'NOM_MED',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('unidadmedida', 'Code'), 
            financiero::t('unidadmedida', 'Item'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('unidadmedida', 'Code'), 
            financiero::t('unidadmedida', 'Item'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "NOM_MED ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }
    
    /**
     * Get Last Id Item Record
     * 
     * @return void
     */
    public static function getLastIdItemArticulo(){
        $row = TipoArticulo::find()->select(['COD_MED'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_MED' => SORT_DESC])->one();
        return $row['COD_MED'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemArticulo(){
        $row = TipoArticulo::find()->select(['COD_MED'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_MED' => SORT_DESC])->one();
        $newId = 1 + $row['COD_MED'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
