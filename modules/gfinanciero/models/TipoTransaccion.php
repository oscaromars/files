<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "MG0040".
 *
 * @property string $C_TRA_E
 * @property string|null $N_TRA_E
 * @property string|null $T_TRA_E
 * @property float|null $NUM_T_E
 * @property float|null $V_TRA_E
 * @property float|null $P_TRA_E
 * @property string|null $F_U_ACT
 * @property string|null $CON_T_E
 * @property string|null $TIP_PAG
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class TipoTransaccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'MG0040';
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
            [['C_TRA_E'], 'required'],
            [['NUM_T_E', 'V_TRA_E', 'P_TRA_E', 'REG_ASO'], 'number'],
            [['F_U_ACT', 'FEC_SIS'], 'safe'],
            [['C_TRA_E'], 'string', 'max' => 3],
            [['N_TRA_E'], 'string', 'max' => 30],
            [['T_TRA_E', 'TIP_PAG', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['CON_T_E', 'HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['C_TRA_E'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'C_TRA_E' => financiero::t('tipotransaccion', 'Code'),
            'N_TRA_E' => financiero::t('tipotransaccion', 'Transaction Name'),
            'T_TRA_E' => financiero::t('tipotransaccion', 'Transaction Type'),
            'NUM_T_E' => financiero::t('tipotransaccion', 'Transaction Number'),
            'V_TRA_E' => financiero::t('tipotransaccion', 'Transaction Value'),
            'P_TRA_E' => financiero::t('tipotransaccion', 'Transaction Liability'),
            'F_U_ACT' => financiero::t('tipotransaccion', 'Update Date'),
            'CON_T_E' => financiero::t('tipotransaccion', 'Transaction Counter'),
            'TIP_PAG' => financiero::t('tipotransaccion', 'Transaction Status'),
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO'  => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
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
            $str_search .= "(N_TRA_E like :search) AND ";
        }
        if(isset($type) && $type !== "0"){
            $str_search .= " T_TRA_E = :type AND ";
        }
        $cols  = "C_TRA_E as Id, ";
        $cols .= "N_TRA_E as Nombre, ";
        $cols .= "T_TRA_E as TipoTrans ";
        //$cols .= "V_TRA_E as ValorTrans, ";
        //$cols .= "P_TRA_E as PasivoTrans, ";
        //$cols .= "F_U_ACT as FechaActualizacion, ";
        //$cols .= "CON_T_E as ContTrans, ";
        //$cols .= "TIP_PAG as TipoPago, ";

        if($export) $cols = "C_TRA_E as Id, N_TRA_E as Nombre, T_TRA_E as TipoTrans";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".MG0040
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY C_TRA_E;";
        //// Code End

        $comando = $con->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($type) && $type !== "0"){
            $comando->bindParam(":type",$type, \PDO::PARAM_STR);
        }
        $result = $comando->queryAll();
        foreach($result as $key => $value){
            if($value['TipoTrans'] == "H"){
                $result[$key]['TipoTrans'] = financiero::t('tipotransaccion', 'Credit Balance');
            }else if($value['TipoTrans'] == "D"){
                $result[$key]['TipoTrans'] = financiero::t('tipotransaccion', 'Debit Balance');
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
     * Get Last Id Item Record
     * 
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['C_TRA_E'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['C_TRA_E' => SORT_DESC])->one();
        return $row['C_TRA_E'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['C_TRA_E'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['C_TRA_E' => SORT_DESC])->one();
        $newId = 1 + $row['C_TRA_E'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
