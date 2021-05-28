<?php
namespace app\modules\academico\models;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\DistributivoHorarioParalelo;
use yii\base\Model;
use Yii;


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DistributivoHorarioParaleloSearch extends DistributivoHorarioParalelo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
         [['dhpa_id','daho_id'], 'integer'],
         [['dhpa_paralelo'], 'string', 'max' => 10],   
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    
    
    public function searchByDaho($id)
    {
        $query = DistributivoHorarioParalelo::find();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

      //  $this->load($params);
        
         
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        
        $query->andFilterWhere(['=', 'daho_id', $id]);
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }
    
    
    
    
    public function search($params)
    {
        $query = DistributivoHorarioParalelo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
         
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'dhpa_id' => $this->dhpa_id,
        ]);

        $query->andFilterWhere([
            'daho_id' => $this->daho_id,
                       
        ])->andFilterWhere(['like', 'dhpa_paralelo', $this->dhpa_paralelo]);
        
//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}

