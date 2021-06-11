<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\EstudioAcademico;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PlanificacionSearch extends Planificacion {

	public function rules() {
        return [
            [['pla_id', 'saca_id', 'mod_id', 'per_id'], 'integer'],
            
        ];
    }

    function search($params) {
        $query = Planificacion::find();
           
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'arr_carrera' => $arr_carrera,
            'pagination' => [
                'pagesize' => 10 
            ]
        ]);


        $this->load($params);

        if (!$this->validate()) {
            
            return $dataProvider;
        }

        $query->andFilterWhere([
            'daca_id' => $this->daca_id,
        ]);

        $query->andFilterWhere([
            'pla_id' => $this->pla_id,
            'saca_id' => $this->saca_id,
            'mod_id' => $this->mod_id,
            'per_id' => $this->per_id,
            
        ]);


        return $dataProvider;
    }

    public function getListadoMatriculados($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        $sql = "select  
                CONCAT(per.per_pri_apellido,' ' ,per.per_pri_nombre) as estudiante,
                per.per_cedula as cedula,
                CONCAT(baca.baca_nombre, '-', pla.pla_periodo_academico) as semestre,
                eaca.eaca_descripcion as carrera,
                moda.mod_descripcion as modalidad,
                uaca.uaca_descripcion as unidad,
                est.est_matricula as n_matricula
                FROM db_academico.planificacion pla  
                Inner Join db_academico.registro_online ron on ron.per_id = pla.per_id
                Inner Join db_academico.registro_pago_matricula rpm on rpm.ron_id = ron.ron_id and rpm_estado_aprobacion = 1
                Inner Join db_academico.planificacion_estudiante pes on pes.pes_id = ron.pes_id 
                Inner Join db_asgard.persona per on per.per_id = pes.per_id 
                Inner Join db_academico.estudiante est on est.per_id = per.per_id 
                Inner Join db_academico.estudiante_carrera_programa ecpr on ecpr.est_id = est.est_id 
                Inner Join db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id 
                Inner Join db_academico.unidad_academica uaca on uaca.uaca_id = meun.uaca_id 
                Inner Join db_academico.modalidad moda on moda.mod_id = pla.mod_id 
                Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meun.eaca_id
                Inner Join db_academico.periodo_academico paca on paca.paca_id = pla.paca_id
                Inner Join db_academico.bloque_academico baca on baca.baca_id = paca.baca_id
                Where rpm.rpm_estado_aprobacion = 1 and
                    pla.pla_estado = 1 and pla.pla_estado_logico = 1
                    and est.est_estado = 1 and est.est_estado_logico = 1
                    and per.per_estado = 1 and per.per_estado_logico = 1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->mod_id) {
                    $sql = $sql . " and pla.mod_id =" . $this->mod_id;

                }

                if ($this->pla_id) {
                    $sql = $sql . " and pla.pla_id =" . $this->pla_id;
                }

                /*if ($this->eaca_id) {
                    $sql = $sql . " and eaca.eaca_id =" . $this->eaca_id;
                }*/

            } 
        }
        if ($tipo == 2) {

            if ($params['mod_id']) {
                $sql = $sql . " and pla.mod_id =" . $params['mod_id'];
            }

            if ($params['pla_id']) {
                $sql = $sql . " and pla.pla_id =" . $params['pla_id'];
            }

            /*if ($params['eaca_id']) {
                $sql = $sql . " and eaca.eaca_id =" . $params['eaca_id'];
            }*/

        }
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
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