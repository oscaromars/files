<?php
namespace app\modules\academico\models;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\DistributivoCabecera;
use yii\base\Model;
use Yii;


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DistributivoCabeceraSearch extends DistributivoCabecera
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
         [['pro_id'], 'integer'],
        
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
    
    
     public function prueba(){
         
         return "prueba";
     }
    
    
    
    public function search($params)
    {
        $query = DistributivoCabecera::find();
       
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider(['query' => $query,   ]);
        $query->andFilterWhere([ 'dcab_estado_revision' => 1, ]);
        $this->load($params);
        
         
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
        // $query->where('0=1');
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'paca_id' => $this->paca_id,
        ]);

        
        
//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}
