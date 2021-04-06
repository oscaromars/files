<?php

namespace app\modules\academico\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\PeriodoAcademico;

class PeriodoAcademicoSearch extends PeriodoAcademico
{
  
     /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['saca_id', 'baca_id', ], 'integer'],
           
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

    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PeriodoAcademico::find();

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
            'paca_id' => $this->paca_id,
        ]);

       $query->andFilterWhere(['=', 'saca_id', $this->saca->saca_id])
            ->andFilterWhere(['=', 'baca_id', $this->baca->baca_id]);
           

        return $dataProvider;
    }
    
    
}

