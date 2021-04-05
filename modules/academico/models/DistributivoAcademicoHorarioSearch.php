<?php
namespace app\modules\academico\models;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\DistributivoAcademicoHorario;
use yii\base\Model;
use Yii;


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DistributivoAcademicoHorarioSearch extends DistributivoAcademicoHorario
{
    
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['daho_id', 'uaca_id', 'mod_id', 'eaca_id'], 'integer'],
            [['daho_descripcion'], 'string', 'max' => 1000],
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
    
    
    
    public function search($params)
    {
        $query = DistributivoAcademicoHorario::find();

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
            'daho_id' => $this->daho_id,
        ]);

        $query->andFilterWhere([
            'daho_id' => $this->daho_id,
            'uaca_id' => $this->uaca_id,
            'mod_id' => $this->mod_id,
            'eaca_id' => $this->eaca_id,
            'daho_jornada' => $this->daho_jornada,
            
        ])->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion]);
        
//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}
