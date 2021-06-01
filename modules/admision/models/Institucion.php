<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "institucion".
 *
 * @property int $ins_id
 * @property string $ins_categoria
 * @property int $pai_id
 * @property int $pro_id
 * @property int $can_id
 * @property string $ins_nombre
 * @property string $ins_abreviacion
 * @property string $ins_direccion_institucion
 * @property string $ins_telefono_institucion
 * @property string $ins_enlace
 * @property string $ins_estado
 * @property string $ins_fecha_creacion
 * @property string $ins_fecha_modificacion
 * @property string $ins_estado_logico
 *
 * @property DetalleExperienciaDocencia[] $detalleExperienciaDocencias
 * @property DetalleInformacionCurricular[] $detalleInformacionCurriculars
 */
class Institucion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'institucion';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_general');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pai_id', 'pro_id', 'can_id'], 'integer'],
            [['ins_nombre', 'ins_estado', 'ins_estado_logico'], 'required'],
            [['ins_fecha_creacion', 'ins_fecha_modificacion'], 'safe'],
            [['ins_categoria', 'ins_estado', 'ins_estado_logico'], 'string', 'max' => 1],
            [['ins_nombre'], 'string', 'max' => 100],
            [['ins_abreviacion'], 'string', 'max' => 10],
            [['ins_direccion_institucion', 'ins_telefono_institucion'], 'string', 'max' => 50],
            [['ins_enlace'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ins_id' => 'Ins ID',
            'ins_categoria' => 'Ins Categoria',
            'pai_id' => 'Pai ID',
            'pro_id' => 'Pro ID',
            'can_id' => 'Can ID',
            'ins_nombre' => 'Ins Nombre',
            'ins_abreviacion' => 'Ins Abreviacion',
            'ins_direccion_institucion' => 'Ins Direccion Institucion',
            'ins_telefono_institucion' => 'Ins Telefono Institucion',
            'ins_enlace' => 'Ins Enlace',
            'ins_estado' => 'Ins Estado',
            'ins_fecha_creacion' => 'Ins Fecha Creacion',
            'ins_fecha_modificacion' => 'Ins Fecha Modificacion',
            'ins_estado_logico' => 'Ins Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleExperienciaDocencias()
    {
        return $this->hasMany(DetalleExperienciaDocencia::className(), ['ins_id' => 'ins_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDetalleInformacionCurriculars()
    {
        return $this->hasMany(DetalleInformacionCurricular::className(), ['ins_id' => 'ins_id']);
    }
    
    /**
     * Function consulta las instituciones
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarInstituciones($pais_id) {
        $con = \Yii::$app->db_general;
        $estado = 1;

        $sql = "SELECT 
                    i.ins_id as id,
                    i.ins_nombre as name                   
                FROM 
                   " . $con->dbname . ".institucion i
                WHERE (i.pai_id = 1 OR i.pai_id = 11) AND 
                      i.ins_estado = :estado AND
                      i.ins_estado_logico = :estado
                ORDER BY 2 asc  ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":pai_id", $pais_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $resultData = $comando->queryAll();
        return $resultData;
    }
}
