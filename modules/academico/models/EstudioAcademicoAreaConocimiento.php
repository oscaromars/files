<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "estudio_academico_area_conocimiento".
 *
 * @property int $eaac_id
 * @property int $eaca_id
 * @property int $mest_id
 * @property int $acon_id
 * @property string $eaac_estado
 * @property string $eaac_fecha_creacion
 * @property string $eaac_fecha_modificacion
 * @property string $eaac_estado_logico
 *
 * @property EstudioAcademico $eaca
 * @property ModuloEstudio $mest
 * @property AreaConocimiento $acon
 */
class EstudioAcademicoAreaConocimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estudio_academico_area_conocimiento';
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
            [['eaca_id', 'mest_id', 'acon_id'], 'integer'],
            [['acon_id', 'eaac_estado', 'eaac_estado_logico'], 'required'],
            [['eaac_fecha_creacion', 'eaac_fecha_modificacion'], 'safe'],
            [['eaac_estado', 'eaac_estado_logico'], 'string', 'max' => 1],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
            [['mest_id'], 'exist', 'skipOnError' => true, 'targetClass' => ModuloEstudio::className(), 'targetAttribute' => ['mest_id' => 'mest_id']],
            [['acon_id'], 'exist', 'skipOnError' => true, 'targetClass' => AreaConocimiento::className(), 'targetAttribute' => ['acon_id' => 'acon_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eaac_id' => 'Eaac ID',
            'eaca_id' => 'Eaca ID',
            'mest_id' => 'Mest ID',
            'acon_id' => 'Acon ID',
            'eaac_estado' => 'Eaac Estado',
            'eaac_fecha_creacion' => 'Eaac Fecha Creacion',
            'eaac_fecha_modificacion' => 'Eaac Fecha Modificacion',
            'eaac_estado_logico' => 'Eaac Estado Logico',
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
    public function getMest()
    {
        return $this->hasOne(ModuloEstudio::className(), ['mest_id' => 'mest_id']);
    }
    /**
     * Function consultarEstudiosRelacionadoXEstudioId
     * @author  Kleber Loayza <analistadesarrollo03@uteg.edu.ec>
     * @param   
     * @return  
     */
    public function consultarEstudiosRelacionadoXEstudioId($eaca_id) {
        $con = \Yii::$app->db_mailing;
        //$estado = 0;
        $sql = "
                    select lis.lis_id,lis.lis_nombre
                    from db_academico.estudio_academico as eaca
                    join db_mailing.lista as lis on lis.eaca_id=eaca.eaca_id and lis.lis_estado_logico=1
                    where eaca.eaca_id in
                    (
                            select selranea.eaca_id
                            from(
                            select eaac.eaca_id, RAND() sel
                            FROM db_academico.estudio_academico_area_conocimiento as eaac
                            where eaac.acon_id = (select eaac.acon_id from db_academico.estudio_academico_area_conocimiento eaac where eaac.eaca_id=$eaca_id)
                            and eaac.eaca_id !=$eaca_id
                            order by sel desc
                            limit 3
                            ) as selranea
                    )
               ";
        $comando = $con->createCommand($sql);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcon()
    {
        return $this->hasOne(AreaConocimiento::className(), ['acon_id' => 'acon_id']);
    }
}
