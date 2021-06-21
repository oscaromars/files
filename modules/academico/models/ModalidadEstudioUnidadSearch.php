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

    public $unidadAcademica;
    public $modalidad;
    public $estudioAcademico;

    public function rules() {
        return [
            [['uaca_id', 'mod_id', 'eaca_id', 'emp_id', 'meun_usuario_ingreso', 'meun_usuario_modifica'], 'integer'],
            [['meun_fecha_creacion', 'meun_fecha_modificacion', 'uaca', 'mod', 'eaca'], 'safe'],
        ];
    }

    public function search($params) {
        $query = ModalidadEstudioUnidad::find()
            ->joinWith('uaca')
            ->joinWith('mod')
            ->joinWith('eaca')
            ->joinWith('maca_id');

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
            'maca_id' => $this->maca_id,
        ]);

        $query->andFilterWhere(['like', 'uaca.uaca_nombre', $this->uaca])
            ->andFilterWhere(['like', 'mod.mod_nombre', $this->mod])
            ->andFilterWhere(['like', 'eaca.uaca_nombre', $this->eaca])
            ->andFilterWhere(['like', 'maca_id.maca_nombre', $this->maca_id]);

        return $dataProvider;
    }

    public function consultarMallasacademicas($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
       
        $sql = "Select concat(mac.maca_codigo,' - ',mac.maca_nombre) AS malla,
                    -- d.made_codigo_asignatura,  
                    a.asi_nombre as asignatura,
                    d.made_semestre as semestre,
                    uaca.uaca_nombre as unidad,
                    moda.mod_nombre as modalidad,
                    eaca.eaca_nombre as carrera,
                    d.made_credito as credito,
                    u.uest_nombre as unidad_estudio,       
                    f.fmac_nombre as formacion_malla_academica,
                    ifnull(asi.asi_nombre,'') as materia_requisito
              FROM db_academico.modalidad_estudio_unidad meu  
                  inner join db_academico.unidad_academica uaca on uaca.uaca_id = meu.uaca_id
                  inner join db_academico.modalidad moda on moda.mod_id = meu.mod_id
                  Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meu.eaca_id
                  INNER JOIN db_academico.malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
                  INNER JOIN db_academico.malla_academica mac ON mac.maca_id = mum.maca_id 
                  inner join db_academico.malla_academica_detalle d on d.maca_id = mac.maca_id
                  inner join db_academico.asignatura a on a.asi_id = d.asi_id
                  inner join db_academico.unidad_estudio u on u.uest_id = d.uest_id
                  inner join db_academico.nivel_estudio n on n.nest_id = d.nest_id
                  inner join db_academico.formacion_malla_academica f on f.fmac_id = d.fmac_id
                  left join db_academico.asignatura asi on asi.asi_id = d.made_asi_requisito
               WHERE  meu.meun_estado_logico = 1 AND meu.meun_estado = 1 AND
                      uaca.uaca_estado_logico = 1 AND uaca.uaca_estado = 1 AND
                      moda.mod_estado_logico = 1 AND moda.mod_estado = 1 AND
                      eaca.eaca_estado_logico = 1 AND eaca.eaca_estado = 1 AND
                      mum.mumo_estado_logico = 1 AND mum.mumo_estado = 1 AND
                      mac.maca_estado_logico = 1 AND mac.maca_estado = 1 AND
                      d.made_estado_logico = 1 AND d.made_estado = 1";

        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {

                if (($this->uaca_id) > 0) {
                    $sql = $sql . " and meu.uaca_id =" . $this->uaca_id;
                }

                if (($this->mod_id) > 0) {
                    $sql = $sql . " and meu.mod_id =" . $this->mod_id;
                }
               
                if (($this->eaca_id) > 0) {
                    $sql = $sql . " and meu.eaca_id =" . $this->eaca_id;
                }

                /*if (($this->maca_id) > 0) {
                    $sql = $sql . " and mum.maca_id =" . $this->maca_id;
                }*/
            } 
        }
        if ($tipo == 2) {

            if (($this->uaca_id) > 0) {
                $sql = $sql . " and meu.uaca_id =" . $params['uaca_id'];
            }

            if (($this->mod_id) > 0) {
                $sql = $sql . " and meu.mod_id =" . $params['mod_id'];
            }
            if (($params['eaca_id']) > 0) {
                $sql = $sql . " and meu.eaca_id =" . $params['eaca_id'];
            }
            /*if (($params['maca_id']) > 0) {
                $sql = $sql . " and meu.maca_id =" . $params['maca_id'];
            }*/
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

    public function getListadoMallaexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "meun.uaca_id = :uaca_id AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "meun.mod_id = :mod_id AND ";
            } 
            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $str_search .= "meun.eaca_id = :eaca_id AND ";
            } 
            if ($arrFiltro['malla'] != "" && $arrFiltro['malla'] > 0) {
                $str_search .= "maca.maca_id = :maca_id AND ";
            }         
        }

            $sql = "Select concat(mac.maca_codigo,' - ',mac.maca_nombre) AS malla, 
                    a.asi_nombre as asignatura,
                    uaca.uaca_nombre as unidad,
                    moda.mod_nombre as modalidad,
                    d.made_semestre as semestre,
                    d.made_credito as credito,                    
                    eaca.eaca_nombre as carrera,
                    u.uest_nombre as unidad_estudio,       
                    f.fmac_nombre as formacion_malla_academica,
                    ifnull(asi.asi_nombre,'') as materia_requisito
              FROM db_academico.modalidad_estudio_unidad meu  
                  inner join db_academico.unidad_academica uaca on uaca.uaca_id = meu.uaca_id
                  inner join db_academico.modalidad moda on moda.mod_id = meu.mod_id
                  Inner Join db_academico.estudio_academico eaca on eaca.eaca_id = meu.eaca_id
                  INNER JOIN db_academico.malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
                  INNER JOIN db_academico.malla_academica mac ON mac.maca_id = mum.maca_id 
                  inner join db_academico.malla_academica_detalle d on d.maca_id = mac.maca_id
                  inner join db_academico.asignatura a on a.asi_id = d.asi_id
                  inner join db_academico.unidad_estudio u on u.uest_id = d.uest_id
                  inner join db_academico.nivel_estudio n on n.nest_id = d.nest_id
                  inner join db_academico.formacion_malla_academica f on f.fmac_id = d.fmac_id
                  left join db_academico.asignatura asi on asi.asi_id = d.made_asi_requisito
               WHERE  meu.meun_estado_logico = 1 AND meu.meun_estado = 1 AND
                      uaca.uaca_estado_logico = 1 AND uaca.uaca_estado = 1 AND
                      moda.mod_estado_logico = 1 AND moda.mod_estado = 1 AND
                      eaca.eaca_estado_logico = 1 AND eaca.eaca_estado = 1 AND
                      mum.mumo_estado_logico = 1 AND mum.mumo_estado = 1 AND
                      mac.maca_estado_logico = 1 AND mac.maca_estado = 1 AND
                      d.made_estado_logico = 1 AND d.made_estado = 1";
        
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $unidad = $arrFiltro["unidad"];
                $comando->bindParam(":paca_id", $periodo, \PDO::PARAM_INT);
            }
            
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $modalidad = $arrFiltro["modalidad"];
                $comando->bindParam(":mod_id", $modalidad, \PDO::PARAM_INT);
            }

            if ($arrFiltro['carrera'] != "" && $arrFiltro['carrera'] > 0) {
                $carrera = $arrFiltro["carrera"];
                $comando->bindParam(":eaca_id", $carrera, \PDO::PARAM_INT);
            }

            if ($arrFiltro['malla'] != "" && $arrFiltro['malla'] > 0) {
                $malla = $arrFiltro["malla"];
                $comando->bindParam(":maca_id", $malla, \PDO::PARAM_INT);
            }
                    
        }
        $res = $comando->queryAll();


        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                    'malla', 
                    'asignatura',
                    'unidad',
                    'modalidad',
                    'semestre',
                    'credito',
                    'formacion_malla_academica',
                    'materia_requisito'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

}