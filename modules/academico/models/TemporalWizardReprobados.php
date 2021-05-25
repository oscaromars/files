<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "temporal_wizard_reprobados".
 *
 * @property int $twre_id
 * @property string $twre_nombre
 * @property string $twre_apellido
 * @property string $twre_dni
 * @property string $twre_numero
 * @property string $twre_correo
 * @property int $twre_pais
 * @property int $twre_celular
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $car_id
 * @property int $twre_metodo_ingreso
 * @property int $conuteg_id
 * @property string $ruta_doc_titulo
 * @property string $ruta_doc_dni
 * @property string $ruta_doc_certvota
 * @property string $ruta_doc_foto
 * @property string $ruta_doc_certificado
 * @property string $ruta_doc_hojavida
 * @property string $twre_mensaje1
 * @property string $twre_mensaje2
 * @property string $twre_estado
 * @property string $twre_fecha_creacion
 * @property string $twre_fecha_modificacion
 * @property string $twre_estado_logico
 */
class TemporalWizardReprobados extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temporal_wizard_reprobados';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_captacion');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['twre_nombre', 'twre_apellido', 'twre_dni', 'twre_numero', 'twre_correo', 'twre_pais', 'twre_celular', 'uaca_id', 'mod_id', 'car_id', 'twre_estado', 'twre_estado_logico'], 'required'],
            [['twre_pais', 'twre_celular', 'uaca_id', 'mod_id', 'car_id', 'twre_metodo_ingreso', 'conuteg_id'], 'integer'],
            [['twre_fecha_creacion', 'twre_fecha_modificacion'], 'safe'],
            [['twre_nombre', 'twre_apellido', 'twre_dni', 'twre_numero', 'twre_correo'], 'string', 'max' => 1000],
            [['ruta_doc_titulo', 'ruta_doc_dni', 'ruta_doc_certvota', 'ruta_doc_foto', 'ruta_doc_certificado', 'ruta_doc_hojavida'], 'string', 'max' => 200],
            [['twre_mensaje1', 'twre_mensaje2', 'twre_estado', 'twre_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'twre_id' => 'Twre ID',
            'twre_nombre' => 'Twre Nombre',
            'twre_apellido' => 'Twre Apellido',
            'twre_dni' => 'Twre Dni',
            'twre_numero' => 'Twre Numero',
            'twre_correo' => 'Twre Correo',
            'twre_pais' => 'Twre Pais',
            'twre_celular' => 'Twre Celular',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'car_id' => 'Car ID',
            'twre_metodo_ingreso' => 'Twre Metodo Ingreso',
            'conuteg_id' => 'Conuteg ID',
            'ruta_doc_titulo' => 'Ruta Doc Titulo',
            'ruta_doc_dni' => 'Ruta Doc Dni',
            'ruta_doc_certvota' => 'Ruta Doc Certvota',
            'ruta_doc_foto' => 'Ruta Doc Foto',
            'ruta_doc_certificado' => 'Ruta Doc Certificado',
            'ruta_doc_hojavida' => 'Ruta Doc Hojavida',
            'twre_mensaje1' => 'Twre Mensaje1',
            'twre_mensaje2' => 'Twre Mensaje2',
            'twre_estado' => 'Twre Estado',
            'twre_fecha_creacion' => 'Twre Fecha Creacion',
            'twre_fecha_modificacion' => 'Twre Fecha Modificacion',
            'twre_estado_logico' => 'Twre Estado Logico',
        ];
    }
}
