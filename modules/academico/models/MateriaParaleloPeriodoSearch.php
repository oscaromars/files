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

class MateriaParaleloPeriodoSearch extends MateriaParaleloPeriodo{
    
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    
    public function search($params)
    {
        $query = MateriaParaleloPeriodo::find();
        $asignatura_model = new Asignatura ();
        $this->load($params);
        
        $resul = $asignatura_model->getAsignaturaPara_asignar_paralelo($this->mod_id,2,"B1");
        // add conditions that should always apply here

       /*$data = [
        ['id' => 1,'asi_id' => 1, 'paca_id' => 1,'mpp_num_paralelo' => 1],
        ['id' => 2, 'paca_id' => 6,'mpp_num_paralelo' => 1],      
        ['id' => 3, 'paca_id' => 7,'mpp_num_paralelo' => 1],
    ];*/
        $data = array();
        for($i=0;$i<sizeof($resul);$i++){
        $model= new MateriaParaleloPeriodo();
        $model->asi_id=$resul[$i]['id'];
        $model->paca_id=2;
        $model->mpp_num_paralelo=0;
        $data[]=$model;
        }
        
       $dataProvider = new ArrayDataProvider([
        'allModels' => $data,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['asi_id', 'paca_id'],
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