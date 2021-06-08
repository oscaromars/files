<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "enrolamiento_agreement".
 *
 * @property int $eagr_id
 * @property int $ron_id
 * @property int $pccr_id
 * @property int $per_id
 * @property int $eaca_id
 * @property int $uaca_id
 * @property int $fpag_id
 * @property int $rpm_id
 * @property string $eagr_nombre_uni
 * @property string $eagr_telefono_uni
 * @property string $eagr_direccion_uni
 * @property string $eagr_apellidos_est
 * @property string $eagr_nombres_est
 * @property string $eagr_mi_est
 * @property string $eagr_direccion_est
 * @property string $eagr_ciudad_est
 * @property string $eagr_estado_est
 * @property string $eagr_zipcode_est
 * @property string $eagr_sexo_est
 * @property string $eagr_ciudadania_est
 * @property string $eagr_educacion_est
 * @property string $eagr_periodo_academico_est
 * @property string $eagr_fnacimiento_est
 * @property string $eagr_lugar_nac_est
 * @property string $eagr_email_est
 * @property string $eagr_telefono_est
 * @property string $eagr_programa_est
 * @property string $eagr_pro_creditos_est
 * @property string $eagr_pro_titulo_est
 * @property string $eagr_fecha_inicio_est
 * @property string $eagr_fecha_graduacion_est
 * @property string $eagr_costo_carrera_est
 * @property string $eagr_porcentaje_anual_est
 * @property string $eagr_financiamiento_est
 * @property string $eagr_costo_programa_est
 * @property string $eagr_pago_programa_est
 * @property string $eagr_primera_cuota_est
 * @property string $eagr_segunda_cuota_est
 * @property string $eagr_tercera_cuota_est
 * @property string $eagr_cuarta_cuota_est
 * @property string $eagr_quinta_cuota_est
 * @property string $eagr_primera_ven_est
 * @property string $eagr_segunda_ven_est
 * @property string $eagr_tercera_ven_est
 * @property string $eagr_cuarta_ven_est
 * @property string $eagr_quinta_ven_est
 * @property string $eagr_firma_est
 * @property string $eagr_firma_par
 * @property string $eagr_firma_dec
 * @property string $eagr_firma_fecha_est
 * @property string $eagr_firma_fecha_par
 * @property string $eagr_firma_fecha_dec
 * @property string $eagr_estado
 * @property string $eagr_fecha_creacion
 * @property string $eagr_fecha_modificacion
 * @property string $eagr_estado_logico
 *
 * @property RegistroOnline $ron
 * @property ProgramaCostoCredito $pccr
 * @property UnidadAcademica $uaca
 * @property EstudioAcademico $eaca
 * @property RegistroPagoMatricula $rpm
 */
class EnrolamientoAgreement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'enrolamiento_agreement';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pccr_id', 'per_id', 'eaca_id', 'uaca_id', 'eagr_estado', 'eagr_estado_logico'], 'required'],
            [['ron_id', 'pccr_id', 'per_id', 'eaca_id', 'uaca_id', 'fpag_id', 'rpm_id'], 'integer'],
            [['eagr_fnacimiento_est', 'eagr_fecha_inicio_est', 'eagr_fecha_graduacion_est', 'eagr_firma_fecha_est', 'eagr_firma_fecha_par', 'eagr_firma_fecha_dec', 'eagr_fecha_creacion', 'eagr_fecha_modificacion'], 'safe'],
            [['eagr_costo_carrera_est', 'eagr_financiamiento_est', 'eagr_costo_programa_est', 'eagr_pago_programa_est', 'eagr_primera_cuota_est', 'eagr_segunda_cuota_est', 'eagr_tercera_cuota_est', 'eagr_cuarta_cuota_est', 'eagr_quinta_cuota_est'], 'number'],
            [['eagr_nombre_uni', 'eagr_telefono_uni', 'eagr_direccion_uni', 'eagr_apellidos_est', 'eagr_nombres_est', 'eagr_direccion_est'], 'string', 'max' => 250],
            [['eagr_mi_est', 'eagr_ciudad_est', 'eagr_estado_est', 'eagr_primera_ven_est', 'eagr_segunda_ven_est', 'eagr_tercera_ven_est', 'eagr_cuarta_ven_est', 'eagr_quinta_ven_est', 'eagr_firma_est', 'eagr_firma_par', 'eagr_firma_dec'], 'string', 'max' => 50],
            [['eagr_zipcode_est'], 'string', 'max' => 20],
            [['eagr_sexo_est', 'eagr_estado', 'eagr_estado_logico'], 'string', 'max' => 1],
            [['eagr_ciudadania_est', 'eagr_educacion_est', 'eagr_periodo_academico_est', 'eagr_lugar_nac_est', 'eagr_email_est', 'eagr_telefono_est', 'eagr_programa_est', 'eagr_pro_titulo_est'], 'string', 'max' => 100],
            [['eagr_pro_creditos_est'], 'string', 'max' => 5],
            [['eagr_porcentaje_anual_est'], 'string', 'max' => 10],
            [['ron_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroOnline::className(), 'targetAttribute' => ['ron_id' => 'ron_id']],
            [['pccr_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProgramaCostoCredito::className(), 'targetAttribute' => ['pccr_id' => 'pccr_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
            [['rpm_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegistroPagoMatricula::className(), 'targetAttribute' => ['rpm_id' => 'rpm_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eagr_id' => 'Eagr ID',
            'ron_id' => 'Ron ID',
            'pccr_id' => 'Pccr ID',
            'per_id' => 'Per ID',
            'eaca_id' => 'Eaca ID',
            'uaca_id' => 'Uaca ID',
            'fpag_id' => 'Fpag ID',
            'rpm_id' => 'Rpm ID',
            'eagr_nombre_uni' => 'Eagr Nombre Uni',
            'eagr_telefono_uni' => 'Eagr Telefono Uni',
            'eagr_direccion_uni' => 'Eagr Direccion Uni',
            'eagr_apellidos_est' => 'Eagr Apellidos Est',
            'eagr_nombres_est' => 'Eagr Nombres Est',
            'eagr_mi_est' => 'Eagr Mi Est',
            'eagr_direccion_est' => 'Eagr Direccion Est',
            'eagr_ciudad_est' => 'Eagr Ciudad Est',
            'eagr_estado_est' => 'Eagr Estado Est',
            'eagr_zipcode_est' => 'Eagr Zipcode Est',
            'eagr_sexo_est' => 'Eagr Sexo Est',
            'eagr_ciudadania_est' => 'Eagr Ciudadania Est',
            'eagr_educacion_est' => 'Eagr Educacion Est',
            'eagr_periodo_academico_est' => 'Eagr Periodo Academico Est',
            'eagr_fnacimiento_est' => 'Eagr Fnacimiento Est',
            'eagr_lugar_nac_est' => 'Eagr Lugar Nac Est',
            'eagr_email_est' => 'Eagr Email Est',
            'eagr_telefono_est' => 'Eagr Telefono Est',
            'eagr_programa_est' => 'Eagr Programa Est',
            'eagr_pro_creditos_est' => 'Eagr Pro Creditos Est',
            'eagr_pro_titulo_est' => 'Eagr Pro Titulo Est',
            'eagr_fecha_inicio_est' => 'Eagr Fecha Inicio Est',
            'eagr_fecha_graduacion_est' => 'Eagr Fecha Graduacion Est',
            'eagr_costo_carrera_est' => 'Eagr Costo Carrera Est',
            'eagr_porcentaje_anual_est' => 'Eagr Porcentaje Anual Est',
            'eagr_financiamiento_est' => 'Eagr Financiamiento Est',
            'eagr_costo_programa_est' => 'Eagr Costo Programa Est',
            'eagr_pago_programa_est' => 'Eagr Pago Programa Est',
            'eagr_primera_cuota_est' => 'Eagr Primera Cuota Est',
            'eagr_segunda_cuota_est' => 'Eagr Segunda Cuota Est',
            'eagr_tercera_cuota_est' => 'Eagr Tercera Cuota Est',
            'eagr_cuarta_cuota_est' => 'Eagr Cuarta Cuota Est',
            'eagr_quinta_cuota_est' => 'Eagr Quinta Cuota Est',
            'eagr_primera_ven_est' => 'Eagr Primera Ven Est',
            'eagr_segunda_ven_est' => 'Eagr Segunda Ven Est',
            'eagr_tercera_ven_est' => 'Eagr Tercera Ven Est',
            'eagr_cuarta_ven_est' => 'Eagr Cuarta Ven Est',
            'eagr_quinta_ven_est' => 'Eagr Quinta Ven Est',
            'eagr_firma_est' => 'Eagr Firma Est',
            'eagr_firma_par' => 'Eagr Firma Par',
            'eagr_firma_dec' => 'Eagr Firma Dec',
            'eagr_firma_fecha_est' => 'Eagr Firma Fecha Est',
            'eagr_firma_fecha_par' => 'Eagr Firma Fecha Par',
            'eagr_firma_fecha_dec' => 'Eagr Firma Fecha Dec',
            'eagr_estado' => 'Eagr Estado',
            'eagr_fecha_creacion' => 'Eagr Fecha Creacion',
            'eagr_fecha_modificacion' => 'Eagr Fecha Modificacion',
            'eagr_estado_logico' => 'Eagr Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRon()
    {
        return $this->hasOne(RegistroOnline::className(), ['ron_id' => 'ron_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPccr()
    {
        return $this->hasOne(ProgramaCostoCredito::className(), ['pccr_id' => 'pccr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca()
    {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEaca()
    {
        return $this->hasOne(EstudioAcademico::className(), ['eaca_id' => 'eaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRpm()
    {
        return $this->hasOne(RegistroPagoMatricula::className(), ['rpm_id' => 'rpm_id']);
    }
}
