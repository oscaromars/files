<?php
namespace app\modules\academico\models;
use app\modules\academico\models\DistributivoCabecera;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DistributivoCabeceraSearch extends DistributivoCabecera {
	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['paca_id', 'pro_id'], 'integer'],

		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios() {
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function prueba() {

		return "prueba";
	}

	public function search($params) {
		$query = DistributivoCabecera::find();

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$query->andFilterWhere(['dcab_estado_revision' => 1]);
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

	/**
	 * Function que enlista los distributivo cabecera con estado de revision 1
	 * @author Luis Cajamarca <analistadesarrollo04@uteg.edu.ec>;
	 * @param
	 * @return
	 */
	public function consultarDistributivoRevision($params = null, $onlyData = false, $tipo = 1) {
		$con = \Yii::$app->db_academico;
		$estado = 1;
		\app\models\Utilities::putMessageLogFile('paca_id1---: ' . print_r($params, true));
		\app\models\Utilities::putMessageLogFile('paca_id2---: ' . $params[0]);

		$sql = "SELECT  dcab.dcab_id as dcab_id,
                case
                    when da.uaca_id = 1 then ifnull(CONCAT(baca.baca_nombre,'-',saca.saca_nombre,' ',saca.saca_anio),'')
                    when da.uaca_id = 2 then CONCAT(ifnull(baca.baca_nombre,''),'-',ifnull(saca.saca_nombre,''),' ',ifnull(saca.saca_anio,''),' (',ifnull(pame.pame_mes,''),')')
                end AS periodo_academico,
                dcab.paca_id as paca_id,
                upper(CONCAT(ifnull(pe.per_pri_apellido,''), ' ', ifnull(pe.per_seg_apellido,''), ' ', ifnull(pe.per_pri_nombre,''), ' ', ifnull(pe.per_seg_nombre,''))) AS profesor,
                'OK' as dcab_observacion_revision
                FROM db_academico.distributivo_cabecera dcab
                inner join db_academico.distributivo_academico da on dcab.dcab_id = da.daca_id
                INNER JOIN db_academico.periodo_academico pc on dcab.paca_id  = pc.paca_id
                INNER JOIN db_academico.semestre_academico saca on saca.saca_id = pc.saca_id
                INNER JOIN db_academico.bloque_academico baca on baca.baca_id = pc.baca_id
                LEFT JOIN db_academico.periodo_academico_mensualizado pame on pame.pame_id = da.pame_id and pame.paca_id = da.paca_id
                INNER JOIN db_academico.profesor AS p ON dcab.pro_id = p.pro_id
                INNER JOIN db_asgard.persona AS pe ON p.per_id = pe.per_id
                WHERE dcab.dcab_estado_revision = 1
                and dcab.dcab_estado = 1 and dcab.dcab_estado_logico = 1
                and da.daca_estado = 1 and da.daca_estado_logico = 1";
		if ($tipo == 1) {
			$this->load($params);
			if ($this->validate()) {
				if ($this->paca_id) {
					$sql = $sql . " and dcab.paca_id =" . $this->paca_id;
				}
			}
		}
		$sql = $sql . " order by paca_id,profesor";

		$comando = $con->createCommand($sql);
		\app\models\Utilities::putMessageLogFile($comando->getRawSql());
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$resultData = $comando->queryAll();
		if ($onlyData) {
			return $resultData;
		}
		$dataProvider = new ArrayDataProvider([
			'key' => 'dcab_id',
			'allModels' => $resultData,
			'pagination' => [
				'pageSize' => Yii::$app->params["pageSize"],
			],
			'sort' => [
				'attributes' => ["dcab_id", "periodo_academico", "paca_id", "profesor", "dcab_observacion_revision"],
			],
		]);

		return $dataProvider;
	}

}
