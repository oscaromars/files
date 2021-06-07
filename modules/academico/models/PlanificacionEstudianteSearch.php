<?php

namespace app\modules\academico\models;

use app\models\Utilities;
use app\modules\academico\models\PlanificacionEstudiante;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PlanificacionEstudianteSearch extends PlanificacionEstudiante {

	public function rules() {
        return [
            [['pla_id', 'per_id', 'pes_usuario_modifica'], 'integer'],
        ];
    }

    function search($params) {
        $query = PlanificacionEstudiante::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 10 
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pes_id' => $this->pes_id,
        ]);

        $query->andFilterWhere([
            'pla_id' => $this->pla_id,
            'per_id' => $this->per_id,
        ]);

        return $dataProvider;
    }

    public function getListadoPromedios($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        

        $sql = "SELECT  CONCAT(per.per_pri_apellido,' ' ,per.per_seg_apellido,' ' ,per.per_pri_nombre) as nombres,
			enac.enac_asig_estado as estado_nota, 
			asi.asi_descripcion as asignatura, 
			pmac.pmac_nota as promedio
	FROM db_academico.planificacion_estudiante pes
    INNER JOIN db_asgard.persona per ON per.per_id = per.per_id
	INNER JOIN db_academico.estudiante est ON est.per_id = per.per_id
	INNER JOIN db_academico.malla_academico_estudiante maes ON maes.per_id = per.per_id
	INNER JOIN db_academico.promedio_malla_academico pmac on pmac.maes_id = maes.maes_id
	INNER JOIN db_academico.estado_nota_academico enac on enac.enac_id = pmac.enac_id
	INNER JOIN db_academico.asignatura asi on asi.asi_id = maes.asi_id
	WHERE
	pes.pes_estado = 1 AND pes.pes_estado_logico = 1
	AND est.est_estado = 1 AND est.est_estado_logico = 1
	AND per.per_estado = 1 AND per.per_estado_logico = 1 
	AND pmac.pmac_estado = 1 AND pmac.pmac_estado_logico = 1
	AND asi.asi_estado = 1 AND asi.asi_estado_logico = 1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
               
                if ($this->per_id) {
                    $sql = $sql . " and pes.per_id =" . $this->per_id;
                }
            } 
        }
        if ($tipo == 2) {

            if ($params['per_id']) {
                $sql = $sql . " and pes.per_id =" . $params['per_id'];
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

    public function getEstudiantesporpersona() {
        $con = \Yii::$app->db_academico;
        $con1 = \Yii::$app->db_asgard;
        $estado = 1;
        
        $sql = "SELECT 
CONCAT(per.per_pri_apellido,'-',per.per_seg_apellido,' ',per.per_pri_nombre) as estudiante      
FROM db_academico.planificacion_estudiante pes
inner join db_asgard.persona per on per.per_id = pes.per_id
inner join db_academico.estudiante est ON est.per_id = per.per_id
WHERE per.per_id = est.per_id and 
pes.pes_estado = 1 and pes.pes_estado_logico = 1 and
per.per_estado = 1 AND per.per_estado_logico = 1 and
est.est_estado = 1 AND est.est_estado_logico = 1;
";

          $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}