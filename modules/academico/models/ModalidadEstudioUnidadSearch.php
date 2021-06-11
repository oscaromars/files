<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\ModalidadEstudioUnidad;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModalidadEstudioUnidadSearch extends ModalidadEstudioUnidad {

    public function rules() {
        return [
            [['uaca_id', 'mod_id', 'eaca_id', 'emp_id', 'meun_usuario_ingreso', 'meun_usuario_modifica'], 'integer'],
        ];
    }

    public function search($params) {
        $query = ModalidadEstudioUnidad::find();

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
            'meun_id' => $this->meun_id,
        ]);

        $query->andFilterWhere([
            'uaca_id' => $this->uaca_id,
            'mod_id' => $this->mod_id,
            'eaca_id' => $this->eaca_id,
            'emp_id' => $this->emp_id,
        ]);
        return $dataProvider;
    }

    public function consultarMallasacademicas($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
       
        $sql = "Select concat(mac.maca_codigo,' - ',mac.maca_nombre) AS malla,
                    -- d.made_codigo_asignatura,  
                    a.asi_nombre as asignatura,
                    d.made_semestre as semestre,
                              eaca.eaca_nombre as carrera,
                    d.made_credito as credito,
                    u.uest_nombre as unidad_estudio,       
                    f.fmac_nombre as formacion_malla_academica,
                    ifnull(asi.asi_nombre,'') as materia_requisito
              FROM db_academico.modalidad_estudio_unidad meu  
                  INNER JOIN db_academico.malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
                  INNER JOIN db_academico.malla_academica mac ON mac.maca_id = mum.maca_id 
                          Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meu.eaca_id
                  inner join db_academico.malla_academica_detalle d on d.maca_id = mac.maca_id
                  inner join db_academico.asignatura a on a.asi_id = d.asi_id
                  inner join db_academico.unidad_estudio u on u.uest_id = d.uest_id
                  inner join db_academico.nivel_estudio n on n.nest_id = d.nest_id
                  inner join db_academico.formacion_malla_academica f on f.fmac_id = d.fmac_id
                  left join db_academico.asignatura asi on asi.asi_id = d.made_asi_requisito
               WHERE  meu.meun_estado_logico = 1 AND
                      meu.meun_estado = 1 AND
                      mum.mumo_estado_logico = 1 AND
                      mum.mumo_estado = 1 AND
                      mac.maca_estado_logico = 1 AND
                      mac.maca_estado = 1";

        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if (($this->eaca_id) > 0) {
                    $sql = $sql . " and meu.eaca_id =" . $this->eaca_id;
                }
            } 
        }
        if ($tipo == 2) {

            if (($params['eaca_id']) > 0) {
                $sql = $sql . " and meu.eaca_id =" . $params['eaca_id'];
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

    public function getListadoMatriculados($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        $sql = "select  
                CONCAT(per.per_pri_apellido, ' ', per.per_seg_apellido, ' ', per.per_pri_nombre) as estudiante,
                per.per_cedula as cedula,
                CONCAT(baca.baca_nombre, '-', pla.pla_periodo_academico) as periodo,
                eaca.eaca_descripcion as carrera,
                moda.mod_descripcion as modalidad,
                est.est_matricula as n_matricula
            From db_academico.modalidad_estudio_unidad meun
            Inner Join db_academico.modalidad moda on moda.mod_id = meun.mod_id 
            Inner Join db_academico.planificacion pla on pla.mod_id = meun.mod_id
            Inner Join db_academico.registro_online ron on ron.per_id = pla.per_id
            Inner Join db_academico.registro_pago_matricula rpm on rpm.ron_id = ron.ron_id
            Inner Join db_academico.planificacion_estudiante pes on pes.pes_id = ron.pes_id 
            Inner Join db_asgard.persona per on per.per_id = pes.per_id 
            Inner Join db_academico.estudiante est on est.per_id = per.per_id 
            Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meun.eaca_id
            Inner Join db_academico.periodo_academico paca on paca.paca_id = pla.paca_id
            Inner Join db_academico.bloque_academico baca on baca.baca_id = paca.baca_id
            Where 
            rpm.rpm_estado_aprobacion = 1 and
                pla.pla_estado = 1 and pla.pla_estado_logico = 1
                and est.est_estado = 1 and est.est_estado_logico = 1
                and per.per_estado = 1 and per.per_estado_logico = 1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->mod_id) {
                    $sql = $sql . " and meun.mod_id =" . $this->mod_id;

                }

                /*if ($this->pla_id) {
                    $sql = $sql . " and meun.pla_id =" . $this->pla_id;
                }*/

                if ($this->eaca_id) {
                    $sql = $sql . " and meun.eaca_id =" . $this->eaca_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['mod_id']) {
                $sql = $sql . " and meun.mod_id =" . $params['mod_id'];
            }

            /*if ($params['pla_id']) {
                $sql = $sql . " and meun.pla_id =" . $params['pla_id'];
            }*/

            if ($params['eaca_id']) {
                $sql = $sql . " and meun.eaca_id =" . $params['eaca_id'];
            }

        }
        //Utilities::putMessageLogFile('sql:' . $sql);
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