<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\DistributivoAcademicoEstudiante;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DistributivoAcademicoEstudianteSearch extends DistributivoAcademicoEstudiante {

	public function rules() {
        return [
	            [['daca_id', 'est_id'], 'integer'],
        ];
    }

    function search($params) {
        $query = DistributivoAcademicoEstudiante::find();

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
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'daes_id' => $this->daes_id,
        ]);

        $query->andFilterWhere([
            'daca_id' => $this->daca_id,
            'est_id' => $this->est_id,
        ]);

        return $dataProvider;
    }


    public function getListadoReportepromedio($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        

        $sql = "SELECT  concat(per.per_pri_nombre, ' ', per.per_pri_apellido) as estudiante,
                      per.per_cedula as cedula,
                      IFNULL(est.est_matricula, '') as matricula,
                      IFNULL(eaca.eaca_nombre, '') as carrera,
                      asi.asi_descripcion as asignatura,
					  ccal.ccal_calificacion as calificacion
                FROM db_academico.distributivo_academico_estudiante daes 
                INNER JOIN db_academico.cabecera_calificacion ccal ON ccal.est_id = daes.est_id
                INNER JOIN db_academico.esquema_calificacion_unidad ecun ON ecun.ecun_id = ccal.ecun_id
                INNER JOIN db_academico.esquema_calificacion ecal ON ecal.ecal_id = ecun.ecal_id
                LEFT JOIN db_academico.estudiante est ON est.est_id = daes.est_id
                INNER JOIN db_asgard.persona per ON per.per_id = est.per_id
                INNER JOIN db_academico.asignatura asi ON asi.asi_id = ccal.asi_id
				INNER JOIN db_academico.estudiante_carrera_programa ecpr ON ecpr.est_id = est.est_id
				INNER JOIN db_academico.modalidad_estudio_unidad meun ON meun.meun_id = ecpr.meun_id
				INNER JOIN db_academico.estudio_academico eaca ON eaca.eaca_id = meun.eaca_id
				INNER JOIN db_academico.periodo_academico AS paca ON paca.paca_id = ccal.paca_id
                WHERE 
				meun.uaca_id = asi.uaca_id
				AND est.est_estado = 1
				AND est.est_estado_logico = 1
				AND per.per_estado = 1
				AND per.per_estado_logico = 1 
				AND daes.daes_estado = 1
				AND daes.daes_estado_logico = 1
				AND asi.asi_estado = 1 
				AND asi.asi_estado_logico = 1
				AND paca.paca_estado = 1 AND paca.paca_estado_logico = 1";

        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->est_id) {
                    $sql = $sql . " and daes.est_id =" . $this->est_id;
                }

            } 
        }
        if ($tipo == 2) {

            if ($params['est_id']) {
                $sql = $sql . " and daes.est_id =" . $params['est_id'];
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