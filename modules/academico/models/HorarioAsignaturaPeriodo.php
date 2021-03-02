<?php

namespace app\modules\academico\models;

use Yii;

/**
 * This is the model class for table "horario_asignatura_periodo".
 *
 * @property int $hape_id
 * @property int $asi_id
 * @property int $paca_id
 * @property int $pro_id
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $dia_id
 * @property string $hape_fecha_clase
 * @property string $hape_hora_entrada
 * @property string $hape_hora_salida
 * @property string $hape_estado
 * @property string $hape_fecha_creacion
 * @property string $hape_fecha_modificacion
 * @property string $hape_estado_logico
 *
 * @property Asignatura $asi
 * @property PeriodoAcademico $paca
 * @property Profesor $pro
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 * @property RegistroMarcacion[] $registroMarcacions
 */
class HorarioAsignaturaPeriodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'horario_asignatura_periodo';
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
            [['asi_id', 'paca_id', 'pro_id', 'uaca_id', 'mod_id', 'dia_id', 'hape_hora_entrada', 'hape_hora_salida', 'hape_estado', 'hape_estado_logico'], 'required'],
            [['asi_id', 'paca_id', 'pro_id', 'uaca_id', 'mod_id', 'dia_id'], 'integer'],
            [['hape_fecha_clase', 'hape_fecha_creacion', 'hape_fecha_modificacion'], 'safe'],
            [['hape_hora_entrada', 'hape_hora_salida'], 'string', 'max' => 10],
            [['hape_estado', 'hape_estado_logico'], 'string', 'max' => 1],
            [['asi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asi_id' => 'asi_id']],
            [['paca_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoAcademico::className(), 'targetAttribute' => ['paca_id' => 'paca_id']],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hape_id' => 'Hape ID',
            'asi_id' => 'Asi ID',
            'paca_id' => 'Paca ID',
            'pro_id' => 'Pro ID',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'dia_id' => 'Dia ID',
            'hape_fecha_clase' => 'Hape Fecha Clase',
            'hape_hora_entrada' => 'Hape Hora Entrada',
            'hape_hora_salida' => 'Hape Hora Salida',
            'hape_estado' => 'Hape Estado',
            'hape_fecha_creacion' => 'Hape Fecha Creacion',
            'hape_fecha_modificacion' => 'Hape Fecha Modificacion',
            'hape_estado_logico' => 'Hape Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsi()
    {
        return $this->hasOne(Asignatura::className(), ['asi_id' => 'asi_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaca()
    {
        return $this->hasOne(PeriodoAcademico::className(), ['paca_id' => 'paca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
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
    public function getRegistroMarcacions()
    {
        return $this->hasMany(RegistroMarcacion::className(), ['hape_id' => 'hape_id']);
    }
    
    
     /**
     * Function insertarHorario graba horario según período.
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>;     
     * @param
     * @return
     */
    public function insertarHorario($asi_id, $paca_id, $pro_id, $uaca_id, $mod_id, $dia_id, $hape_fecha_clase, $hape_hora_entrada, $hape_hora_salida) {
        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "hape_estado";
        $bdet_sql = "1";
        $param_sql .= ", hape_estado_logico";
        $bdet_sql .= ", 1";           
        if (isset($asi_id)) {
            $param_sql .= ", asi_id";
            $bdet_sql .= ", :asi_id";
        }
        if (isset($paca_id)) {
            $param_sql .= ", paca_id";
            $bdet_sql .= ", :paca_id";
        }
        if (isset($pro_id)) {
            $param_sql .= ", pro_id";
            $bdet_sql .= ", :pro_id";
        }
        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bdet_sql .= ", :uaca_id";
        }
        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bdet_sql .= ", :mod_id";
        }
        if (isset($dia_id)) {
            $param_sql .= ", dia_id";
            $bdet_sql .= ", :dia_id";
        }        
        if (isset($hape_fecha_clase)) {
            $param_sql .= ", hape_fecha_clase";
            $bdet_sql .= ", :hape_fecha_clase";
        }
        if (isset($hape_hora_entrada)) {
            $param_sql .= ", hape_hora_entrada";
            $bdet_sql .= ", :hape_hora_entrada";
        }
        if (isset($hape_hora_salida)) {
            $param_sql .= ", hape_hora_salida";
            $bdet_sql .= ", :hape_hora_salida";
        }       
        try {
            $sql = "INSERT INTO " . $con->dbname . ".horario_asignatura_periodo ($param_sql) VALUES($bdet_sql)";
            $comando = $con->createCommand($sql);

            if (isset($asi_id)) {
                $comando->bindParam(':asi_id', $asi_id, \PDO::PARAM_INT);
            }
            if (isset($paca_id)) {
                $comando->bindParam(':paca_id', $paca_id, \PDO::PARAM_INT);
            }
            if (isset($pro_id)) {
                $comando->bindParam(':pro_id', $pro_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($uaca_id)))) {
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($mod_id)))) {
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($dia_id)))) {
                $comando->bindParam(':dia_id', $dia_id, \PDO::PARAM_INT);
            }
            if (!empty((isset($hape_fecha_clase)))) {
                $comando->bindParam(':hape_fecha_clase', $hape_fecha_clase, \PDO::PARAM_STR);
            }
            if (!empty((isset($hape_hora_entrada)))) {
                $comando->bindParam(':hape_hora_entrada', $hape_hora_entrada, \PDO::PARAM_STR);
            }
            if (!empty((isset($hape_hora_salida)))) {
                $comando->bindParam(':hape_hora_salida', $hape_hora_salida, \PDO::PARAM_STR);
            }                 
            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.horario_asignatura_periodo');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
}
