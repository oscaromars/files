<?php

/* 
 * The PenBlu framework is free software. It is released under the terms of
 * the following BSD License.
 *
 * Copyright (C) 2015 by PenBlu Software (http://www.penblu.com)
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions 
 * are met:
 *
 *  - Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in
 *    the documentation and/or other materials provided with the
 *    distribution.
 *  - Neither the name of PenBlu Software nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * PenBlu is based on code by 
 * Yii Software LLC (http://www.yiisoft.com) Copyright © 2008
 *
 */

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

financiero::registerTranslations();

/**
 * This is the model class for table "IG0002".
 *
 * @property string $COD_TIP
 * @property string|null $NOM_TIP
 * @property string|null $FEC_TIP
 * @property float|null $REG_ASO
 * @property string|null $FEC_SIS
 * @property string|null $HOR_SIS
 * @property string|null $USUARIO
 * @property string|null $EQUIPO
 * @property string $EST_LOG
 * @property string $EST_DEL
 *
 * @property IG0020[] $iG0020s
 */
class TipoArticulo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'IG0002';
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
            [['COD_TIP'], 'required'],
            [['FEC_TIP', 'FEC_SIS'], 'safe'],
            [['REG_ASO'], 'number'],
            [['COD_TIP'], 'string', 'max' => 3],
            [['NOM_TIP'], 'string', 'max' => 60],
            [['HOR_SIS'], 'string', 'max' => 10],
            [['EQUIPO'], 'string', 'max' => 15],
            [['USUARIO'], 'string', 'max' => 250],
            [['EST_LOG', 'EST_DEL'], 'string', 'max' => 1],
            [['COD_TIP'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'COD_TIP' => financiero::t('tipoarticulo', 'Code'),
            'NOM_TIP' => financiero::t('tipoarticulo', 'Name Item'),
            'FEC_TIP' => financiero::t('tipoarticulo', 'Creation Date'),
            'REG_ASO' => financiero::t('gfinanciero', 'Associated Register'),
            'FEC_SIS' => financiero::t('gfinanciero', 'System Date'),
            'HOR_SIS' => financiero::t('gfinanciero', 'System Hour'),
            'USUARIO' => financiero::t('gfinanciero', 'User'),
            'EQUIPO' => financiero::t('gfinanciero', 'Computer'),
            'EST_LOG' => financiero::t('gfinanciero', 'Logic Status'),
            'EST_DEL' => 'Est Del',
        ];
    }

    /**
     * Gets query for [[ItemArticulo]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemArticulos()
    {
        return $this->hasMany(ItemArticulo::className(), ['COD_TIP' => 'COD_TIP']);
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
            $str_search .= "(NOM_TIP like :search) AND ";
        }
        $cols = "COD_TIP as Id, NOM_TIP as Nombre, FEC_TIP as Fecha, EST_LOG as Estado";
        if($export) $cols = "COD_TIP as Id, NOM_TIP as Nombre, FEC_TIP as Fecha";
        $sql = "SELECT 
                    $cols
                FROM 
                    ".$con->dbname.".IG0002
                WHERE 
                    $str_search
                    EST_LOG = 1 AND EST_DEL = 1
                ORDER BY COD_TIP;";
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
     * Return columns to dataset of create a query to widget Search.
     *
     * @return mixed Return a Record Array
     */
    public static function getDataColumnsQueryWidget(){
        $arr_data = [];
        $arr_data['con'] = Yii::$app->db_gfinanciero;
        $arr_data['table'] = "IG0002";
        $arr_data['cols'] = [
            'COD_TIP', 
            'NOM_TIP',
        ];
        $arr_data['aliasCols'] = [
            financiero::t('tipoarticulo', 'Code'), 
            financiero::t('tipoarticulo', 'Type'),
        ];
        $arr_data['colVisible'] = [
            financiero::t('tipoarticulo', 'Code'), 
            financiero::t('tipoarticulo', 'Type'),
        ];
        $arr_data['where'] = "EST_LOG = 1 and EST_DEL = 1";
        $arr_data['order'] = "NOM_TIP ASC";
        $arr_data['limitPages'] = Yii::$app->params['pageSize'];
        return $arr_data;
    }
    
    /**
     * Get Last Id Item Record
     * 
     * @return void
     */
    public static function getLastIdItemRecord(){
        $row = self::find()->select(['COD_TIP'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_TIP' => SORT_DESC])->one();
        return $row['COD_TIP'];
    }

    /**
     * Get Next Id Item Record
     *
     * @return void
     */
    public static function getNextIdItemRecord(){
        $row = self::find()->select(['COD_TIP'])->where(['EST_LOG' => '1', 'EST_DEL' => '1'])->orderBy(['COD_TIP' => SORT_DESC])->one();
        $newId = 1 + $row['COD_TIP'];
        $newId = str_pad($newId, 3, "0", STR_PAD_LEFT);
        return $newId;
    }

}
