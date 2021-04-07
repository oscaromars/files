<?php

namespace app\modules\academico\models;

use yii\data\ArrayDataProvider;
use Yii;

/**
 * This is the model class for table "estudio_academico".
 *
 * @property int $eaca_id
 * @property int $teac_id
 * @property string $eaca_nombre
 * @property string $eaca_descripcion
 * @property string $eaca_alias
 * @property string $eaca_alias_resumen
 * @property int $eaca_usuario_ingreso
 * @property int $eaca_usuario_modifica
 * @property string $eaca_estado
 * @property string $eaca_fecha_creacion
 * @property string $eaca_fecha_modificacion
 * @property string $eaca_estado_logico
 *
 * @property TipoEstudioAcademico $teac
 * @property MallaAcademica[] $mallaAcademicas
 * @property ModalidadEstudioUnidad[] $modalidadEstudioUnidads
 */
class TipoEstudioAcademico extends \app\modules\admision\components\CActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tipo_estudio_academico';
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
    public function rules()
    {
        return [
            [['teac_descripcion', 'teac_nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'teac_id' => 'Id',
            'teac_nombre' => 'Estudio Academico',
            'teac_descripcion' => 'Descripción',
        ];
    }

}