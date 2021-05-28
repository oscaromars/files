<?php

namespace app\modules\academico\models;

use yii\data\ActiveDataProvider;
use app\modules\academico\models\BloqueAcademico;
use yii\base\Model;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BloqueAcademicoSearch extends BloqueAcademico {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['baca_descripcion', 'baca_nombre'], 'string'],
            [['baca_anio'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = BloqueAcademico::find();

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
            'baca_id' => $this->baca_id,
        ]);

        $query->andFilterWhere(['=', 'baca_anio', $this->baca_anio])
                ->andFilterWhere(['like', 'baca_descripcion', $this->baca_descripcion])
                ->andFilterWhere(['like', 'baca_nombre', $this->baca_nombre]);

//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}
