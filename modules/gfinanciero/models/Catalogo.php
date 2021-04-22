<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "CATALOGO".
 *
 * @property string $COD_CTA
 * @property string|null $COD_PAD
 * @property string|null $CTA_SEA
 * @property string|null $NOM_CTA
 * @property string|null $TIP_CTA
 * @property string|null $TIP_ELE
 * @property string|null $TIP_REG
 * @property float|null $CAT_SDB
 * @property float|null $CAT_SHB
 * @property float|null $CAT_D00
 * @property float|null $CAT_H00
 * @property float|null $CAT_D01
 * @property float|null $CAT_H01
 * @property float|null $CAT_D02
 * @property float|null $CAT_H02
 * @property float|null $CAT_D03
 * @property float|null $CAT_H03
 * @property float|null $CAT_D04
 * @property float|null $CAT_H04
 * @property float|null $CAT_D05
 * @property float|null $CAT_H05
 * @property float|null $CAT_D06
 * @property float|null $CAT_H06
 * @property float|null $CAT_D07
 * @property float|null $CAT_H07
 * @property float|null $CAT_D08
 * @property float|null $CAT_H08
 * @property float|null $CAT_D09
 * @property float|null $CAT_H09
 * @property float|null $CAT_D10
 * @property float|null $CAT_H10
 * @property float|null $CAT_D11
 * @property float|null $CAT_H11
 * @property float|null $CAT_D12
 * @property float|null $CAT_H12
 * @property string|null $ESTATUS
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 */
class Catalogo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'CATALOGO';
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
            [['COD_CTA'], 'required'],
            [['CAT_SDB', 'CAT_SHB', 'CAT_D00', 'CAT_H00', 'CAT_D01', 'CAT_H01', 'CAT_D02', 'CAT_H02', 'CAT_D03', 'CAT_H03', 'CAT_D04', 'CAT_H04', 'CAT_D05', 'CAT_H05', 'CAT_D06', 'CAT_H06', 'CAT_D07', 'CAT_H07', 'CAT_D08', 'CAT_H08', 'CAT_D09', 'CAT_H09', 'CAT_D10', 'CAT_H10', 'CAT_D11', 'CAT_H11', 'CAT_D12', 'CAT_H12'], 'number'],
            [['FEC_SIS'], 'safe'],
            [['COD_CTA', 'COD_PAD', 'CTA_SEA'], 'string', 'max' => 12],
            [['NOM_CTA'], 'string', 'max' => 120],
            [['TIP_CTA'], 'string', 'max' => 2],
            [['TIP_ELE', 'TIP_REG'], 'string', 'max' => 30],
            [['ESTATUS', 'EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['USUARIO'], 'string', 'max' => 250],
            [['EQUIPO'], 'string', 'max' => 15],
            [['COD_CTA'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_CTA' => 'Cod Cta',
            'COD_PAD' => 'Cod Pad',
            'CTA_SEA' => 'Cta Sea',
            'NOM_CTA' => 'Nom Cta',
            'TIP_CTA' => 'Tip Cta',
            'TIP_ELE' => 'Tip Ele',
            'TIP_REG' => 'Tip Reg',
            'CAT_SDB' => 'Cat Sdb',
            'CAT_SHB' => 'Cat Shb',
            'CAT_D00' => 'Cat D00',
            'CAT_H00' => 'Cat H00',
            'CAT_D01' => 'Cat D01',
            'CAT_H01' => 'Cat H01',
            'CAT_D02' => 'Cat D02',
            'CAT_H02' => 'Cat H02',
            'CAT_D03' => 'Cat D03',
            'CAT_H03' => 'Cat H03',
            'CAT_D04' => 'Cat D04',
            'CAT_H04' => 'Cat H04',
            'CAT_D05' => 'Cat D05',
            'CAT_H05' => 'Cat H05',
            'CAT_D06' => 'Cat D06',
            'CAT_H06' => 'Cat H06',
            'CAT_D07' => 'Cat D07',
            'CAT_H07' => 'Cat H07',
            'CAT_D08' => 'Cat D08',
            'CAT_H08' => 'Cat H08',
            'CAT_D09' => 'Cat D09',
            'CAT_H09' => 'Cat H09',
            'CAT_D10' => 'Cat D10',
            'CAT_H10' => 'Cat H10',
            'CAT_D11' => 'Cat D11',
            'CAT_H11' => 'Cat H11',
            'CAT_D12' => 'Cat D12',
            'CAT_H12' => 'Cat H12',
            'ESTATUS' => financiero::t('gfinanciero', 'Status'),
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
    
     public function getAllItemsGrid($search, $dataProvider = false, $export = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $con = Yii::$app->db_gfinanciero;

        //// Code Begin
        if(isset($search)){
            $str_search .= "(NOM_CTA like :search) AND ";
        }
        $cols  = "COD_CTA as Id, ";
        $cols .= "NOM_CTA as Nombre, ";
        $cols .= "TIP_CTA as TipoCta, ";
        $cols .= "TIP_REG as TipoReg ";
       
        //if($export) $cols .= ", EST_LOG as Estado";
        if($export) $cols = "COD_CTA,NOM_CTA,TIP_ELE,TIP_REG";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".CATALOGO  
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_CTA;";
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
                    'attributes' => ['Nombre','TipoCta','TipoReg'],
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
        $arr_data['table'] = "CATALOGO";
        $arr_data['cols'] = [
            'COD_CTA', 
            'NOM_CTA',
            'TIP_REG',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('catalogo', 'Code'), 
            financiero::t('catalogo', 'Account'),
            financiero::t('catalogo', 'Record_Type'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('catalogo', 'Code'), 
            financiero::t('catalogo', 'Account'),
            //financiero::t('catalogo', 'Record_Type'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = NULL; //"COD_CTA ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
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
    
    public static function getTipoCuenta($tipo) {
        $mensaje = "";
        switch ($tipo) {
            case '1':
                $mensaje = financiero::t('catalogo', 'Active');
                break;
            case '2':
                $mensaje = financiero::t('catalogo', 'Passive');
                break;
            case '3':
                $mensaje = financiero::t('catalogo', 'Patrimony');
                break;
            case '4':
                $mensaje = financiero::t('catalogo', 'Entry');
                break;
            case '5':
                $mensaje = financiero::t('catalogo', 'Egress');
                break;
            case '6':
                $mensaje = financiero::t('catalogo', 'Result');
                break;
            default:
                $mensaje = "";
        }
        return $mensaje;
    }
    
    /**
     * Get Last Id Item Record
     * 
     * @return void
     */
    public static function getBuscarCodigoNombre($search){
        $data = Catalogo::find()
            ->select(["COD_CTA as id", "NOM_CTA as name", "CONCAT(COD_CTA,SPACE(1),NOM_CTA) as value"])
            ->where("EST_LOG = 1 and EST_DEL = 1 AND (NOM_CTA like '%".$search."%' OR COD_CTA like '%".$search."%')")
            ->asArray()
            ->all();
        return $data;
    }

    
    
    
}
