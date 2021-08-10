<?php

namespace app\modules\academico\models;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use Yii;

/**
 * This is the model class for table "distributivo_academico_horario".
 *
 * @property int $daho_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $eaca_id
 * @property string $daho_jornada
 * @property string $daho_descripcion
 * @property string $daho_horario
 * @property int $daho_total_horas
 * @property string $daho_estado
 * @property string $daho_fecha_creacion
 * @property string $daho_fecha_modificacion
 * @property string $daho_estado_logico
 *
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 * @property EstudioAcademico $eaca
 */
class DistributivoAcademicoHorario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        $prueba="prueba de git";
        return 'distributivo_academico_horario';
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
            [['uaca_id', 'mod_id', 'daho_jornada', 'daho_estado', 'daho_estado_logico'], 'required'],
            [['uaca_id', 'mod_id', 'eaca_id', 'daho_total_horas'], 'integer'],
            [['daho_fecha_creacion', 'daho_fecha_modificacion'], 'safe'],
            [['daho_jornada', 'daho_estado', 'daho_estado_logico'], 'string', 'max' => 1],
            [['daho_descripcion'], 'string', 'max' => 1000],
            [['daho_horario'], 'string', 'max' => 10],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'daho_id' => 'Id',
            'uaca_id' => 'Unidad Academica',
            'mod_id' => 'Modadilidad',
            'eaca_id' => 'Estudio Academico',
            'daho_jornada' => 'Jornada',
            'daho_descripcion' => 'Descripción',
            'daho_horario' => 'Horario',
            'daho_total_horas' => 'Total Horas',
            'daho_estado' => 'Estado',
            'daho_fecha_creacion' => '',
            'daho_fecha_modificacion' => '',
            'daho_estado_logico' => '',
        ];
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
    public function getMod()
    {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEaca()
    {
        return $this->hasOne(EstudioAcademico::className(), ['eaca_id' => 'eaca_id']);
    }

     /**
     * Gets query for [[Usuarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDhp()
    {
        return $this->hasMany(DistributivoHorarioParalelo::className(), ['daho_id' => 'daho_id']);
    }

    /**
     * Function consulta las jornadas.
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarJornadahorario() {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT distinct daho_jornada as id,
                  CASE daho_jornada
                    WHEN 1 THEN 'Matutino'
                    WHEN 2 THEN 'Nocturno'
                    WHEN 3 THEN 'Semipresencial'
                    WHEN 4 THEN 'Distancia'
		  END AS name
                  FROM " . $con->dbname . ".distributivo_academico_horario
                  WHERE daho_estado = :estado AND
                  daho_estado_logico = :estado;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    public function consultarParaleloHorario($daho_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "select dhpa_id id, dhpa_paralelo name
                from " . $con->dbname . ".distributivo_horario_paralelo
                where daho_id = :daho_id AND
                  dhpa_estado = :estado AND
                  dhpa_estado_logico = :estado;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":daho_id", $daho_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}