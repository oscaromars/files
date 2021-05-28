<?php

namespace app\modules\academico\models;

use Yii;
use \yii\data\ActiveDataProvider;
use \yii\data\ArrayDataProvider;

/**
 * This is the model class for table "registro_configuracion".
 *
 * @property int $rco_id
 * @property int $pla_id
 * @property string $rco_fecha_inicio
 * @property string $rco_fecha_fin
 * @property int $rco_num_bloques
 * @property string $rco_estado
 * @property string $rco_fecha_creacion
 * @property int $rco_usuario_modifica
 * @property string $rco_fecha_modificacion
 * @property string $rco_estado_logico
 *
 * @property Planificacion $pla
 */
class RegistroConfiguracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_configuracion';
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
            [['pla_id', 'rco_num_bloques', 'rco_fecha_fin', 'rco_fecha_inicio', 'rco_estado', 'rco_estado_logico'], 'required'],
            [['pla_id', 'rco_num_bloques', 'rco_usuario_modifica'], 'integer'],
            [['rco_fecha_inicio', 'rco_fecha_fin', 'rco_fecha_creacion', 'rco_fecha_modificacion'], 'safe'],
            [['rco_estado', 'rco_estado_logico'], 'string', 'max' => 1],
            [['pla_id'], 'exist', 'skipOnError' => true, 'targetClass' => Planificacion::className(), 'targetAttribute' => ['pla_id' => 'pla_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rco_id' => 'Rco ID',
            'pla_id' => 'Pla ID',
            'rco_fecha_inicio' => 'Rco Fecha Inicio',
            'rco_fecha_fin' => 'Rco Fecha Fin',
            'rco_num_bloques' => 'Num Bloques',
            'rco_estado' => 'Ron Estado',
            'rco_fecha_creacion' => 'Ron Fecha Creacion',
            'rco_usuario_modifica' => 'Ron Usuario Modifica',
            'rco_fecha_modificacion' => 'Ron Fecha Modificacion',
            'rco_estado_logico' => 'Ron Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPla()
    {
        return $this->hasOne(Planificacion::className(), ['pla_id' => 'pla_id']);
    }

    public function getRegistroConfList($periodo_academico, $mod_id, $onlyData=false){
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $where = "";
        
        if($mod_id != 0)    $where .= "m.mod_id =:mod_id AND ";
        if(isset($periodo_academico) && $periodo_academico != "")   $where .= "p.pla_periodo_academico =:periodo AND ";

        $sql = "
            SELECT 
                r.rco_id AS id, 
                p.pla_periodo_academico AS PeriodoAcademico,
                m.mod_nombre AS Modalidad,
                r.rco_fecha_inicio AS Inicio,
                r.rco_fecha_fin AS Fin,
                r.rco_num_bloques AS Bloque
            FROM 
                " . $con->dbname . ".registro_configuracion as r 
                INNER JOIN " . $con->dbname . ".planificacion as p ON r.pla_id = p.pla_id
                INNER JOIN " . $con->dbname . ".modalidad as m ON m.mod_id = p.mod_id
            WHERE 
                $where
                r.rco_estado =:estado AND r.rco_estado_logico =:estado AND 
                p.pla_estado =:estado AND p.pla_estado_logico =:estado AND 
                m.mod_estado =:estado AND m.mod_estado_logico =:estado
            ORDER BY 
                r.rco_fecha_inicio DESC;
        ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if($mod_id != 0)    $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        if(isset($periodo_academico) && $periodo_academico != "")   $comando->bindParam(":periodo", $periodo_academico, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();

        if($onlyData)
            return $resultData;

        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Periodo', 'Modalidad'],
            ],
        ]);
        return $dataProvider;
    }

}
