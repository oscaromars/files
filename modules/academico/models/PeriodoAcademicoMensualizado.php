<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "periodo_academico_mensualizado".
 *
 * @property int $pame_id
 * @property int $uaca_id
 * @property string $pame_mes
 * @property int $paca_id
 * @property string $pame_descripcion
 * @property int $pame_usuario_ingreso
 * @property int $pame_usuario_modifica
 * @property string $pame_estado
 * @property string $pame_fecha_creacion
 * @property string $pame_fecha_modificacion
 * @property string $pame_estado_logico
 *
 * @property PeriodoAcademico $paca
 * @property UnidadAcademica $uaca
 */

class PeriodoAcademicoMensualizado extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'periodo_academico_mensualizado';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb() {
		return Yii::$app->get('db_academico');
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[['uaca_id', 'pame_mes', 'paca_id', 'pame_usuario_ingreso', 'pame_estado', 'pame_estado_logico'], 'required'],
			[['uaca_id', 'paca_id', 'pame_usuario_ingreso', 'pame_usuario_modifica'], 'integer'],
			[['pame_fecha_creacion', 'pame_fecha_modificacion'], 'safe'],
			[['pame_mes'], 'string', 'max' => 300],
			[['pame_descripcion'], 'string', 'max' => 500],
			[['pame_estado', 'pame_estado_logico'], 'string', 'max' => 1],
			[['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
			[['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'pame_id' => 'Pam ID',
			'uaca_id' => 'Uaca ID',
			'pame_mes' => 'Pam Mes',
			'paca_id' => 'Paca ID',
			'pame_descripcion' => 'Pam Descripcion',
			'pame_usuario_ingreso' => 'Pam Usuario Ingreso',
			'pame_usuario_modifica' => 'Pam Usuario Modifica',
			'pame_estado' => 'Pam Estado',
			'pame_fecha_creacion' => 'Pam Fecha Creacion',
			'pame_fecha_modificacion' => 'Pam Fecha Modificacion',
			'pame_estado_logico' => 'Pam Estado Logico',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPaca() {
		return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUaca() {
		return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
	}

	/**
	 * Function obtener el mensualizado de los distributivos de Periodo y unidad academica
	 * @author  Luis Cajamarca <analistadesarrollo00@uteg.edu.ec>;
	 * @property
	 * @return
	 */
	public function consultarMesDistributivoPos($uaca_id, $paca_id) {
		// \app\models\Utilities::putMessageLogFile('unidad academica modelo '. $uaca_id);
		$con = \Yii::$app->db_academico;
		$estado = 1;
		$sql = "SELECT distinct pam.pame_id as id,
                           pam.pame_mes as name
                from db_academico.periodo_academico_mensualizado pam
                inner join db_academico.periodo_academico paca on paca.paca_id=pam.paca_id
                inner join db_academico.unidad_academica uaca on uaca.uaca_id = pam.uaca_id
                where paca.paca_id = :paca_id and uaca.uaca_id = :uaca_id
                    and pam.pame_estado_logico = :estado and pam.pame_estado = :estado
                    and paca.paca_estado_logico = :estado and paca.paca_estado = :estado
                    and uaca.uaca_estado_logico = :estado and uaca.uaca_estado = :estado
                    ORDER BY 1 asc";

		$comando = $con->createCommand($sql);
		$comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
		$comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
		$comando->bindParam(":paca_id", $paca_id, \PDO::PARAM_INT);
		$resultData = $comando->queryAll();
		// \app\models\Utilities::putMessageLogFile('consultarModalidad: '.$comando->getRawSql());
		return $resultData;
	}
}
