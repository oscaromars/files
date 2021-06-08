<?php

namespace app\modules\academico\models;

use yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;
use yii\base\Model;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MateriaParaleloPeriodoSearch extends MateriaParaleloPeriodo {

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        //   $query = MateriaParaleloPeriodo::find()->orderBy("mod_id,mpp_num_paralelo ASC");
        // $query = MateriaParaleloPeriodo::find()->select(['max(mpp_num_paralelo) AS  mpp_num_paralelo, asi_id,mod_id ,paca_id'])
//->where('approved = 1')
//->groupBy(['asi_id', 'mod_id','paca_id'])
//->all();
        // add conditions that should always apply here

        $con = \Yii::$app->db_academico;
        $estado = 1;
        $this->load($params);

        $sql = "select mpp.asi_id, mod_id,paca_id,max(mpp_num_paralelo) as mpp_num_paralelo 
                from db_academico.materia_paralelo_periodo as mpp 
                INNER JOIN db_academico.asignatura a on a.asi_id=mpp.asi_id
                where  1= 1 
               ";
        if ($this->mod_id) {
            $sql = $sql . " and mod_id=" . $this->mod_id;
        }

        if ($this->paca_id) {
            $sql = $sql . " and paca_id=" . $this->paca_id;
        }

        $sql = $sql . " group by mpp.asi_id, mod_id,paca_id";
        $comando = $con->createCommand($sql);
//        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
//        $comando->bindParam(":asi_id", $asi_id, \PDO::PARAM_INT);
//        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
//        $comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);

        $resultData = $comando->queryAll();

        $data = array();
        for ($i = 0; $i < sizeof($resultData); $i++) {
            $model = new MateriaParaleloPeriodo();
            // $model->mpp_id=$i;
            $model->asi_id = $resultData[$i]['asi_id'];
            $model->paca_id = $resultData[$i]['paca_id'];
            $model->mod_id = $resultData[$i]['mod_id'];
            $model->mpp_num_paralelo = $resultData[$i]['mpp_num_paralelo'];
            $data[] = $model;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['asi_id', 'paca_id', 'mpp_num_paralelo'],
            ],
        ]);

        /* if (!$this->validate()) {
          // uncomment the following line if you do not want to return any records when validation fails
          // $query->where('0=1');
          return $dataProvider;
          } */


//         $query->andFilterWhere([
//            'mod_id' => $this->mod_id,
//        ]);
//  
//          $query->andFilterWhere([
//            'paca_id' => $this->paca_id,
//        ]); 

        return $dataProvider;
    }

    public function searchAsinaturas($params) {
        //$query = MateriaParaleloPeriodo::find();
        $asignatura_model = new Asignatura ();
        $this->load($params);

        $modelPerido = PeriodoAcademico::findOne($this->paca_id);
        $resul = $asignatura_model->getAsignaturaPara_asignar_paralelo($this->mod_id, $modelPerido->baca->baca_nombre, $this->paca_id);
        // add conditions that should always apply here

        /* $data = [
          ['id' => 1,'asi_id' => 1, 'paca_id' => 1,'mpp_num_paralelo' => 1],
          ['id' => 2, 'paca_id' => 6,'mpp_num_paralelo' => 1],
          ['id' => 3, 'paca_id' => 7,'mpp_num_paralelo' => 1],
          ]; */
        $data = array();
        for ($i = 0; $i < sizeof($resul); $i++) {
            $model = new MateriaParaleloPeriodo();
            $model->mpp_id = $i;
            $model->asi_id = $resul[$i]['id'];
            $model->paca_id = 2;
            $model->mpp_num_paralelo = 0;
            $data[] = $model;
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'attributes' => ['asi_id', 'paca_id', 'mpp_num_paralelo'],
            ],
        ]);

// grid filtering conditions
        //  $query->join('INNER JOIN', 'distributivo_academico_horario', 'distributivo_academico_horario.daho_id = distributivo_horario_paralelo.daho_id')->andFilterWhere(['=', 'uaca_id', $this->daho_id]);;
        /// $query->andFilterWhere(['like', 'dhpa_paralelo', $this->dhpa_paralelo]);
//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}
