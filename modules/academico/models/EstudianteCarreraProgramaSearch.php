<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\EstudianteCarreraPrograma;
use app\modules\academico\models\Estudiante;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EstudianteCarreraProgramaSearch extends EstudianteCarreraPrograma {

    /**
     * {@inheritdoc}
     */
	public function rules() {
        return [
            [['est_id', 'meun_id', 'ecpr_usuario_ingreso', 'ecpr_usuario_modifica'], 'integer'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    function search($params) {
        $query = EstudianteCarreraProgramaSearch::find();

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
            'ecpr_id' => $this->ecpr_id,

        ]);

        $query->andFilterWhere([
            'est_id' => $this->est_id,
            'meun_id' => $this->meun_id,
        ]);

        return $dataProvider;
    }

    public function getListadoPromedio($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        

        $sql = "SELECT distinct ifnull(CONCAT(per.per_pri_apellido,' ' ,per.per_seg_apellido,' ' ,per.per_pri_nombre),'') as estudiante, 
                        eaca.eaca_nombre as carrera, 
                        enac.enac_asig_estado as estado_nota, 
                        asi.asi_descripcion as asignatura, 
                        maca.maca_nombre as malla,
                        ifnull((pmac.pmac_nota),'') as promedio
                FROM db_academico.estudiante_carrera_programa ecpr
                INNER JOIN db_academico.estudiante est ON est.est_id = ecpr.est_id
                INNER JOIN db_asgard.persona per ON per.per_id = est.per_id
                INNER JOIN db_academico.malla_academico_estudiante maes ON maes.per_id = per.per_id
                INNER JOIN db_academico.promedio_malla_academico pmac on pmac.maes_id = maes.maes_id
                INNER JOIN db_academico.estado_nota_academico enac on enac.enac_id = pmac.enac_id
                INNER JOIN db_academico.asignatura asi on asi.asi_id = maes.asi_id
                INNER JOIN db_academico.malla_academica maca on maca.maca_id = maes.maca_id
                INNER JOIN db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id
                INNER JOIN db_academico.estudio_academico eaca on eaca.eaca_id = meun.eaca_id
                WHERE 
                ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND est.est_estado = 1 AND est.est_estado_logico = 1
                AND per.per_estado = 1 AND per.per_estado_logico = 1 
                AND maes.maes_estado = 1 AND maes.maes_estado_logico = 1
                AND pmac.pmac_estado = 1 AND pmac.pmac_estado_logico = 1
                AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1";

        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->est_id) {
                    $sql = $sql . " and per.per_id =" . $this->est_id;
                }

            } 
        }
        if ($tipo == 2) {

            if ($params['est_id']) {
                $sql = $sql . " and per.per_id =" . $params['est_id'];
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

    public function getListadoPromediosexcel($arrFiltro = NULL, $onlyData = false) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;

        if (isset($arrFiltro) && count($arrFiltro) > 0) { 
            if ($arrFiltro['estudiante'] != "" && $arrFiltro['estudiante'] > 0) {
                $str_search .= "est.per_id = :per_id AND ";
            }        
        }

            $sql = "SELECT distinct ifnull(CONCAT(per.per_pri_apellido,' ' ,per.per_seg_apellido,' ' ,per.per_pri_nombre),'') as estudiante, 
                        eaca.eaca_nombre as carrera, 
                        enac.enac_asig_estado as estado_nota, 
                        asi.asi_descripcion as asignatura, 
                        maca.maca_nombre as malla,
                        ifnull((pmac.pmac_nota),'') as promedio
                FROM db_academico.estudiante_carrera_programa ecpr
                INNER JOIN db_academico.estudiante est ON est.est_id = ecpr.est_id
                INNER JOIN db_asgard.persona per ON per.per_id = est.per_id
                INNER JOIN db_academico.malla_academico_estudiante maes ON maes.per_id = per.per_id
                INNER JOIN db_academico.promedio_malla_academico pmac on pmac.maes_id = maes.maes_id
                INNER JOIN db_academico.estado_nota_academico enac on enac.enac_id = pmac.enac_id
                INNER JOIN db_academico.asignatura asi on asi.asi_id = maes.asi_id
                INNER JOIN db_academico.malla_academica maca on maca.maca_id = maes.maca_id
                INNER JOIN db_academico.modalidad_estudio_unidad meun on meun.meun_id = ecpr.meun_id
                INNER JOIN db_academico.estudio_academico eaca on eaca.eaca_id = meun.eaca_id
                WHERE est.per_id = " . $arrFiltro['estudiante'] . " AND
                ecpr.ecpr_estado = 1 AND ecpr.ecpr_estado_logico = 1
                AND est.est_estado = 1 AND est.est_estado_logico = 1
                AND per.per_estado = 1 AND per.per_estado_logico = 1 
                AND maes.maes_estado = 1 AND maes.maes_estado_logico = 1
                AND pmac.pmac_estado = 1 AND pmac.pmac_estado_logico = 1
                AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1";
        
        //Utilities::putMessageLogFile('sql:' . $sql);
        $comando = $con_academico->createCommand($sql);
        //$comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
           
            if ($arrFiltro['estudiante'] != "" && $arrFiltro['estudiante'] > 0) {
                $estudiante = $arrFiltro["estudiante"];
                $comando->bindParam(":per_id", $estudiante, \PDO::PARAM_INT);
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
                    'carrera', 
                    'estudiante',
                    'asignatura',
                    'promedio'],
            ],
        ]);

        if ($onlyData) {
            return $res;
        } else {
            return $dataProvider;
        }
    }

    
}