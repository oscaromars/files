<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "gasto_administrativo".
 *
 * @property int $gadm_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $saca_id
 * @property string $gadm_bloque
 * @property double $gadm_gastos_varios
 * @property double $gadm_asociacion
 * @property string $gadm_fecha_inicio
 * @property string $gadm_fecha_fin
 * @property string $gadm_estado_activo
 * @property int $gadm_usuario_creacion
 * @property int $gadm_usuario_modificacion
 * @property string $gadm_estado
 * @property string $gadm_fecha_creacion
 * @property string $gadm_fecha_modificacion
 * @property string $gadm_estado_logico
 *
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 */
class GastoAdministrativo extends \yii\db\ActiveRecord {
	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'gasto_administrativo';
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
			[['uaca_id', 'mod_id', 'saca_id', 'gadm_bloque', 'gadm_fecha_inicio', 'gadm_usuario_creacion', 'gadm_estado', 'gadm_estado_logico'], 'required'],
			[['uaca_id', 'mod_id', 'saca_id', 'gadm_usuario_creacion', 'gadm_usuario_modificacion'], 'integer'],
			[['gadm_gastos_varios', 'gadm_asociacion'], 'number'],
			[['gadm_fecha_inicio', 'gadm_fecha_fin', 'gadm_fecha_creacion', 'gadm_fecha_modificacion'], 'safe'],
			[['gadm_bloque'], 'string', 'max' => 5],
			[['gadm_estado_activo', 'gadm_estado', 'gadm_estado_logico'], 'string', 'max' => 1],
			[['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
			[['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
			[['saca_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemestreAcademico::className(), 'targetAttribute' => ['saca_id' => 'saca_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'gadm_id' => 'Gadm ID',
			'uaca_id' => 'Uaca ID',
			'mod_id' => 'Mod ID',
			'saca_id' => 'Saca ID',
			'gadm_bloque' => 'Gadm Bloque',
			'gadm_gastos_varios' => 'Gadm Gastos Varios',
			'gadm_asociacion' => 'Gadm Asociacion',
			'gadm_fecha_inicio' => 'Gadm Fecha Inicio',
			'gadm_fecha_fin' => 'Gadm Fecha Fin',
			'gadm_estado_activo' => 'Gadm Estado Activo',
			'gadm_usuario_creacion' => 'Gadm Usuario Creacion',
			'gadm_usuario_modificacion' => 'Gadm Usuario Modificacion',
			'gadm_estado' => 'Gadm Estado',
			'gadm_fecha_creacion' => 'Gadm Fecha Creacion',
			'gadm_fecha_modificacion' => 'Gadm Fecha Modificacion',
			'gadm_estado_logico' => 'Gadm Estado Logico',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUaca() {
		return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getMod() {
		return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSaca() {
		return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
	}
}
