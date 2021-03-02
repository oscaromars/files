<?php

namespace app\modules\financiero\models;

use Yii;

/**
 * This is the model class for table "persona_solicitud".
 *
 * @property int $psol_id
 * @property string $psol_pri_nombre
 * @property string $psol_seg_nombre
 * @property string $psol_pri_apellido
 * @property string $psol_seg_apellido
 * @property string $psol_cedula
 * @property string $psol_ruc
 * @property string $psol_pasaporte
 * @property string $psol_genero
 * @property string $psol_celular
 * @property string $psol_correo
 * @property string $psol_domicilio_sector
 * @property string $psol_domicilio_cpri
 * @property string $psol_domicilio_csec
 * @property string $psol_domicilio_num
 * @property string $psol_domicilio_ref
 * @property string $psol_domicilio_telefono
 * @property int $pai_id_domicilio
 * @property int $pro_id_domicilio
 * @property int $can_id_domicilio
 * @property string $psol_estado
 * @property string $psol_fecha_creacion
 * @property string $psol_fecha_modificacion
 * @property string $psol_estado_logico
 *
 * @property SolicitudGeneral[] $solicitudGenerals
 */
class PersonaSolicitud extends \app\modules\financiero\components\CActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona_solicitud';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_facturacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['psol_cedula', 'psol_estado', 'psol_estado_logico'], 'required'],
            [['pai_id_domicilio', 'pro_id_domicilio', 'can_id_domicilio'], 'integer'],
            [['psol_fecha_creacion', 'psol_fecha_modificacion'], 'safe'],
            [['psol_pri_nombre', 'psol_seg_nombre', 'psol_pri_apellido', 'psol_seg_apellido', 'psol_correo', 'psol_domicilio_sector'], 'string', 'max' => 250],
            [['psol_cedula', 'psol_ruc'], 'string', 'max' => 15],
            [['psol_pasaporte', 'psol_celular', 'psol_domicilio_telefono'], 'string', 'max' => 50],
            [['psol_genero', 'psol_estado', 'psol_estado_logico'], 'string', 'max' => 1],
            [['psol_domicilio_cpri', 'psol_domicilio_csec', 'psol_domicilio_ref'], 'string', 'max' => 500],
            [['psol_domicilio_num'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'psol_id' => 'Psol ID',
            'psol_pri_nombre' => 'Psol Pri Nombre',
            'psol_seg_nombre' => 'Psol Seg Nombre',
            'psol_pri_apellido' => 'Psol Pri Apellido',
            'psol_seg_apellido' => 'Psol Seg Apellido',
            'psol_cedula' => 'Psol Cedula',
            'psol_ruc' => 'Psol Ruc',
            'psol_pasaporte' => 'Psol Pasaporte',
            'psol_genero' => 'Psol Genero',
            'psol_celular' => 'Psol Celular',
            'psol_correo' => 'Psol Correo',
            'psol_domicilio_sector' => 'Psol Domicilio Sector',
            'psol_domicilio_cpri' => 'Psol Domicilio Cpri',
            'psol_domicilio_csec' => 'Psol Domicilio Csec',
            'psol_domicilio_num' => 'Psol Domicilio Num',
            'psol_domicilio_ref' => 'Psol Domicilio Ref',
            'psol_domicilio_telefono' => 'Psol Domicilio Telefono',
            'pai_id_domicilio' => 'Pai Id Domicilio',
            'pro_id_domicilio' => 'Pro Id Domicilio',
            'can_id_domicilio' => 'Can Id Domicilio',
            'psol_estado' => 'Psol Estado',
            'psol_fecha_creacion' => 'Psol Fecha Creacion',
            'psol_fecha_modificacion' => 'Psol Fecha Modificacion',
            'psol_estado_logico' => 'Psol Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudGenerals()
    {
        return $this->hasMany(SolicitudGeneral::className(), ['psol_id' => 'psol_id']);
    }
}
