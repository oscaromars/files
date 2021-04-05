<?php

namespace app\modules\academico\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\SemestreAcademico;

class SemestreAcademicoSearch extends SemestreAcademico
{
  
     /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['saca_nombre', 'saca_descripcion'], 'string', 'max' => 45],
            [['saca_anio'], 'integer'],
           
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
        $query = SemestreAcademico::find();

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
            'saca_id' => $this->saca_id,
        ]);

       $query->andFilterWhere(['like', 'saca_nombre', $this->saca_nombre])
            ->andFilterWhere(['like', 'saca_descripcion', $this->saca_descripcion])
               ->andFilterWhere(['like', 'saca_anio', $this->saca_anio]);
           

        return $dataProvider;
    }
    
    
}

