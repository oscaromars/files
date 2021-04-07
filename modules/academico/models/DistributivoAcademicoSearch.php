<?php

namespace app\modules\academico\models;

use app\modules\academico\models\DistributivoAcademico;
use yii\base\Model;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DistributivoAcademicoSearch extends DistributivoAcademico {

    public function rules() {
        return [
            [['paca_id', 'tdis_id', 'asi_id',  'uaca_id', 'mod_id', 'daho_id', 'dhpa_id', 'daca_num_estudiantes_online', 'daca_usuario_ingreso', 'daca_usuario_modifica'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function getListadoDistributivoBloqueDocente($params = null, $onlyData = false, $tipo = 1) {
        $con_academico = \Yii::$app->db_academico;
        $con_db = \Yii::$app->db;
        $sql = "select (@row_number:=@row_number + 1) AS Id, UPPER(CONCAT(persona.per_pri_apellido,' ' ,persona.per_seg_apellido,' ' ,persona.per_pri_nombre,' ' ,persona.per_seg_nombre)) as docente,
                per_cedula as no_cedula,
                UPPER(pi3.pins_titulo) as  titulo_tercel_nivel,
                UPPER(pi4.pins_titulo) as  titulo_cuarto_nivel,
                persona.per_correo as correo,
                UPPER(dd.ddoc_nombre)  as  tiempo_dedicacion,
                'DICTADO' as desempeno,
                '' as materia,
                '1' as nivel,
                '2' as credito,
                '40' as horas_por_credito,
                '80' as total_horas_dictar
                from " . $con_academico->dbname . ".distributivo_academico da 
                inner join " . $con_academico->dbname . ".profesor profesor on da.pro_id = profesor.pro_id 
                inner join " . $con_db->dbname . ".persona persona on profesor.per_id = persona.per_id 
                left join " . $con_academico->dbname . ".profesor_instruccion pi3 on pi3.pro_id = profesor.pro_id and pi3.nins_id =3 and pi3.pins_estado=1 and pi3.pins_estado_logico=1
                left join " . $con_academico->dbname . ".profesor_instruccion pi4 on pi4.pro_id = profesor.pro_id and pi4.nins_id =4 and pi4.pins_estado=1 and pi4.pins_estado_logico=1
                inner join " . $con_academico->dbname . ".dedicacion_docente dd on dd.ddoc_id = profesor.ddoc_id 
                where 1=1";
        if ($tipo == 1) {
            $this->load($params);
            if ($this->validate()) {
                if ($this->paca_id) {
                    $sql = $sql . " and da.paca_id =" . $this->paca_id;
                }
                if ($this->mod_id) {
                    $sql = $sql . " and da.mod_id =" . $this->mod_id;
                }

                if ($this->tdis_id) {
                    $sql = $sql . " and da.tdis_id =" . $this->tdis_id;
                }
            }
        }
        if ($tipo == 2) {
            
             if ($params['mod_id']) {
                    $sql = $sql . " and da.mod_id =" . $params['mod_id'];
                }
                
                if ($params['paca_id']) {
                    $sql = $sql . " and da.paca_id =" . $params['paca_id'];
                }
                if ($params['tdis_id']) {
                    $sql = $sql . " and da.tdis_id =" . $params['tdis_id'];
                }
        }
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
                'attributes' => ['docente', 'no_cedula', "titulo_tercel_nivel", "titulo_cuarto_nivel", "correo", "tiempo_dedicacion", "desempeno",'materia','nivel','credito','horas_por_credito','total_horas_dictar'],
            ],
        ]);

        return $dataProvider;
    }

    public function search($params) {
        $query = DistributivoAcademico::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
                    'pagination' => [
            'pagesize' => 10 // in case you want a default pagesize
        ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
// grid filtering conditions
        $query->andFilterWhere([
            'daca_id' => $this->daca_id,
        ]);

        $query->andFilterWhere([
            'paca_id' => $this->paca_id,
            'tdis_id' => $this->tdis_id,
            'mod_id' => $this->mod_id,
            'pro_id' => $this->pro_id,
            'daho_id' => $this->daho_id,
        ]);

//        $query->andFilterWhere(['like', 'daho_descripcion', $this->daho_descripcion])
//            ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada])
//                ->andFilterWhere(['like', 'daho_jornada', $this->daho_jornada]);

        return $dataProvider;
    }

}
