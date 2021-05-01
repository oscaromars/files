<?php
namespace app\modules\academico\models;

use yii\base\Exception;
use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
 
class MateriaParaleloPeriodo extends \app\modules\admision\components\CActiveRecord {
     
     /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'materia_paralelo_periodo';
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
            [['asi_id', 'mod_id', 'paca_id','mpp_num_paralelo' ], 'required'],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
             [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsig()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'mpp_id' => 'Id',
            'paca_id' => 'Periodo',
            'asi_id' => 'Asignatura',
            'mod_id'=>'Modalidad',
            'mpp_num_paralelo'=>'Número de Paralelos',
            'mpp_usuario_ingreso' => '',
            'mpp_usuario_modifica' => '',
            'mpp_estado' => 'Estado',
            'mpp_fecha_creacion' => '',
            'mpp_fecha_modificacion' => '',
            'mpp_estado_logico' => '',
            
        ];
    }
}