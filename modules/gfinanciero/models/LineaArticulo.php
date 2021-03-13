<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();
/**
 * This is the model class for table "IG0001".
 *
 * @property string $COD_LIN
 * @property string $NOM_LIN
 * @property string $FEC_LIN
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class LineaArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0001';
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
            [['COD_LIN', 'NOM_LIN', 'FEC_LIN'], 'required'],
            [['FEC_LIN', 'FEC_SIS'], 'safe'],
            [['REG_ASO'], 'number'],
            [['COD_LIN'], 'string', 'max' => 3],
            [['NOM_LIN'], 'string', 'max' => 60],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_LIN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_LIN' => 'Cod Lin',
            'NOM_LIN' => 'Nom Lin',
            'FEC_LIN' => 'Fec Lin',
            'REG_ASO' => 'Reg Aso',
            'FEC_SIS' => 'Fec Sis',
            'HOR_SIS' => 'Hor Sis',
            'USUARIO' => 'Usuario',
            'EQUIPO' => 'Equipo',
            'EST_LOG' => 'Est Log',
            'EST_DEL' => 'Est Del',
        ];
    }
    
    public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(NOM_LIN like :search) AND ";
        }
        $cols = "COD_LIN as Id, NOM_LIN as Nombre, FEC_LIN as Fecha";
        if($export) $cols .= ", EST_LOG as Estado";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".IG0001  
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_LIN;";
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
    public static function getLastIdItemArticulo(){
        $row = LineaArticulo::find()->select(['COD_LIN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_LIN' => SORT_DESC])->one();
        return $row['COD_LIN'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemArticulo(){
        $row = LineaArticulo::find()->select(['COD_LIN'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_LIN' => SORT_DESC])->one();
        $newId = 1 + $row['COD_LIN'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }
}
