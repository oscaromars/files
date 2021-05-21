<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\MallaAcademica;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MallaAcademicaSearch extends MallaAcademica {

    public function rules() {
        return [
            [['maca_usuario_ingreso', 'maca_usuario_modifica'], 'integer'],
        ];
    }

    public function search($params) {
        $query = MallaAcademica::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10 // in case you want a default pagesize
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'maca_id' => $this->maca_id,
        ]);

        $query->andFilterWhere([
            'maca_id' => $this->maca_id,
        ]);

        return $dataProvider;
    }

    public function consultarMallasacademicas($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
       
        $sql = "Select m.maca_id AS id,
                concat(m.maca_codigo,' - ',m.maca_nombre) AS malla,
                d.made_codigo_asignatura,
                a.asi_nombre as asignatura,
                d.made_semestre as semestre,
                d.made_credito as credito,
                u.uest_nombre as unidad_estudio,
                f.fmac_nombre as formacion_malla_academica,
                ifnull(asi.asi_nombre,'') as materia_requisito
                FROM db_academico.malla_academica m
                inner join db_academico.malla_academica_detalle d on d.maca_id = m.maca_id
                inner join db_academico.asignatura a on a.asi_id = d.asi_id
                inner join db_academico.unidad_estudio u on u.uest_id = d.uest_id
                inner join db_academico.nivel_estudio n on n.nest_id = d.nest_id
                inner join db_academico.formacion_malla_academica f on f.fmac_id = d.fmac_id
                left join db_academico.asignatura asi on asi.asi_id = d.made_asi_requisito
                WHERE
                -- m.maca_id = :malla
                 m.maca_estado = '1'
                and m.maca_estado_logico = '1'
                and d.made_estado = '1'
                and d.made_estado = '1'";

        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->maca_id) {
                    $sql = $sql . " and maca.maca_id =" . $this->maca_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['maca_id']) {
                $sql = $sql . " and maca.maca_id =" . $params['maca_id'];
            }
        }
        Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        $res = $comando->queryAll();

        if ($onlyData)
            return $res;
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [],
            ],
        ]);

        return $dataProvider;
    }
    
}