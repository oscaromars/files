<?php

namespace app\modules\academico\models;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\academico\models\EstudioAcademico;

class EstudioAcademicoSearch extends EstudioAcademico
{
  
     /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
          [['eaca_nombre', 'eaca_alias',], 'string', 'max' => 300],
            [['eaca_alias_resumen'], 'string', 'max' => 30],
            [['eaca_descripcion'], 'string', 'max' => 500],
            
            [['teac_id'], 'integer'],
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
        $query = EstudioAcademico::find();

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
            'eaca_id' => $this->eaca_id,
        ]);

        $query->andFilterWhere(['like', 'eaca_nombre', $this->eaca_nombre])
            ->andFilterWhere(['like', 'eaca_descripcion', $this->eaca_descripcion])
                ->andFilterWhere(['like','eaca_alias', $this->eaca_alias])
                ->andFilterWhere(['=','teac_id', $this->teac->teac_id]);

        return $dataProvider;
    }
    
    
}

