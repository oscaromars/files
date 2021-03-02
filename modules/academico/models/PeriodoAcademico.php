<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "periodo_academico".
 *
 * @property int $paca_id
 * @property int $saca_id
 * @property int $baca_id
 * @property string $paca_activo
 * @property string $paca_fecha_inicio
 * @property string $paca_fecha_fin
 * @property int $paca_usuario_ingreso
 * @property int $paca_usuario_modifica
 * @property string $paca_estado
 * @property string $paca_fecha_creacion
 * @property string $paca_fecha_modificacion
 * @property string $paca_estado_logico
 *
 * @property DistributivoAcademico[] $distributivoAcademicos
 * @property DistributivoCabecera[] $distributivoCabeceras
 * @property EstudiantePeriodoPago[] $estudiantePeriodoPagos
 * @property SemestreAcademico $saca
 * @property BloqueAcademico $baca
 * @property PlanificacionAcademicaMalla[] $planificacionAcademicaMallas
 */
class PeriodoAcademico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_academico';
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
            [['saca_id', 'baca_id', 'paca_usuario_ingreso', 'paca_estado', 'paca_estado_logico'], 'required'],
            [['saca_id', 'baca_id', 'paca_usuario_ingreso', 'paca_usuario_modifica'], 'integer'],
            [['paca_fecha_inicio', 'paca_fecha_fin', 'paca_fecha_creacion', 'paca_fecha_modificacion'], 'safe'],
            [['paca_activo', 'paca_estado', 'paca_estado_logico'], 'string', 'max' => 1],
            [['saca_id'], 'exist', 'skipOnError' => true, 'targetClass' => SemestreAcademico::className(), 'targetAttribute' => ['saca_id' => 'saca_id']],
            [['baca_id'], 'exist', 'skipOnError' => true, 'targetClass' => BloqueAcademico::className(), 'targetAttribute' => ['baca_id' => 'baca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paca_id' => 'Paca ID',
            'saca_id' => 'Saca ID',
            'baca_id' => 'Baca ID',
            'paca_activo' => 'Paca Activo',
            'paca_fecha_inicio' => 'Paca Fecha Inicio',
            'paca_fecha_fin' => 'Paca Fecha Fin',
            'paca_usuario_ingreso' => 'Paca Usuario Ingreso',
            'paca_usuario_modifica' => 'Paca Usuario Modifica',
            'paca_estado' => 'Paca Estado',
            'paca_fecha_creacion' => 'Paca Fecha Creacion',
            'paca_fecha_modificacion' => 'Paca Fecha Modificacion',
            'paca_estado_logico' => 'Paca Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoAcademicos()
    {
        return $this->hasMany(DistributivoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistributivoCabeceras()
    {
        return $this->hasMany(DistributivoCabecera::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstudiantePeriodoPagos()
    {
        return $this->hasMany(EstudiantePeriodoPago::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaca()
    {
        return $this->hasOne(SemestreAcademico::className(), ['saca_id' => 'saca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBaca()
    {
        return $this->hasOne(BloqueAcademico::className(), ['baca_id' => 'baca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionAcademicaMallas()
    {
        return $this->hasMany(PlanificacionAcademicaMalla::className(), ['paca_id' => 'paca_id']);
    }
    
     /**
     * Function consulta el período académico actual. 
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function getPeriodoAcademicoActual() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        
        $sql = "SELECT  pera.paca_id as id,
                        ifnull(CONCAT(blq.baca_nombre,'-',sem.saca_nombre,' ',sem.saca_anio),'') as nombre,
                        blq.baca_nombre
                FROM " . $con->dbname . ".periodo_academico pera
                     inner join " . $con->dbname . ".semestre_academico sem  ON sem.saca_id = pera.saca_id
                     inner join " . $con->dbname . ".bloque_academico blq ON blq.baca_id = pera.baca_id
                WHERE pera.paca_activo = 'A' AND
                      pera.paca_fecha_inicio = (select max(paca_fecha_inicio) from db_academico.periodo_academico p where paca_activo = 'A') AND
                      pera.paca_estado = :estado AND
                      pera.paca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryOne();
        return $resultData;
    }
}
