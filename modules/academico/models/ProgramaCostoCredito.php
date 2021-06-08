<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "programa_costo_credito".
 *
 * @property int $pccr_id
 * @property int $eaca_id
 * @property int $mod_id
 * @property int $pccr_creditos
 * @property string $pccr_costo_credito
 * @property string $pccr_costo_graduacion
 * @property string $pccr_costo_carrera
 * @property int $pccr_horas_credito
 * @property int $pccr_anios_duracion
 * @property string $pccr_titulo
 * @property string $pccr_estado
 * @property string $pccr_fecha_creacion
 * @property string $pccr_fecha_modificacion
 * @property string $pccr_estado_logico
 *
 * @property EstudioAcademico $eaca
 * @property Modalidad $mod
 */
class ProgramaCostoCredito extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'programa_costo_credito';
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
            [['eaca_id', 'mod_id', 'pccr_creditos', 'pccr_horas_credito', 'pccr_anios_duracion', 'pccr_titulo', 'pccr_estado', 'pccr_estado_logico'], 'required'],
            [['eaca_id', 'mod_id', 'pccr_creditos', 'pccr_horas_credito', 'pccr_anios_duracion'], 'integer'],
            [['pccr_costo_credito', 'pccr_costo_graduacion', 'pccr_costo_carrera'], 'number'],
            [['pccr_fecha_creacion', 'pccr_fecha_modificacion'], 'safe'],
            [['pccr_titulo'], 'string', 'max' => 250],
            [['pccr_estado', 'pccr_estado_logico'], 'string', 'max' => 1],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pccr_id' => 'Pccr ID',
            'eaca_id' => 'Eaca ID',
            'mod_id' => 'Mod ID',
            'pccr_creditos' => 'Pccr Creditos',
            'pccr_costo_credito' => 'Pccr Costo Credito',
            'pccr_costo_graduacion' => 'Pccr Costo Graduacion',
            'pccr_costo_carrera' => 'Pccr Costo Carrera',
            'pccr_horas_credito' => 'Pccr Horas Credito',
            'pccr_anios_duracion' => 'Pccr Anios Duracion',
            'pccr_titulo' => 'Pccr Titulo',
            'pccr_estado' => 'Pccr Estado',
            'pccr_fecha_creacion' => 'Pccr Fecha Creacion',
            'pccr_fecha_modificacion' => 'Pccr Fecha Modificacion',
            'pccr_estado_logico' => 'Pccr Estado Logico',
        ];
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
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    public function getValores($eaca_id, $mod_id){
        $con = \Yii::$app->db_academico;
        $costozero = date(Yii::$app->params["costocreditZero"]);

        $sql = "SELECT pccr_costo_carrera, pccr_costo_credito, pccr_creditos , $costozero as 'costozero'
                from " . $con->dbname . ".programa_costo_credito
                where eaca_id = :eaca_id
                and mod_id = :mod_id
                and pccr_estado = '1'
                and pccr_estado_logico = '1';";
        
        if($eaca_id == NULL || $mod_id == NULL){
            $resultData = [];
        }else{
            $comando = $con->createCommand($sql);
            $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
            $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
            $resultData = $comando->queryAll();
        }
        return $resultData;
    }
}
