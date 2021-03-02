<?php

namespace app\modules\gpr\models;

use Yii;

/**
 * This is the model class for table "nivel".
 *
 * @property int $niv_id
 * @property string $niv_nombre
 * @property string $niv_descripcion
 * @property int $niv_usuario_ingreso
 * @property int|null $niv_usuario_modifica
 * @property string $niv_estado
 * @property string $niv_fecha_creacion
 * @property string|null $niv_fecha_modificacion
 * @property string $niv_estado_logico
 */
class Nivel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nivel';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_gpr');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['niv_nombre', 'niv_descripcion', 'niv_usuario_ingreso', 'niv_estado', 'niv_estado_logico'], 'required'],
            [['niv_usuario_ingreso', 'niv_usuario_modifica'], 'integer'],
            [['niv_fecha_creacion', 'niv_fecha_modificacion'], 'safe'],
            [['niv_nombre'], 'string', 'max' => 300],
            [['niv_descripcion'], 'string', 'max' => 500],
            [['niv_estado', 'niv_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'niv_id' => 'Niv ID',
            'niv_nombre' => 'Niv Nombre',
            'niv_descripcion' => 'Niv Descripcion',
            'niv_usuario_ingreso' => 'Niv Usuario Ingreso',
            'niv_usuario_modifica' => 'Niv Usuario Modifica',
            'niv_estado' => 'Niv Estado',
            'niv_fecha_creacion' => 'Niv Fecha Creacion',
            'niv_fecha_modificacion' => 'Niv Fecha Modificacion',
            'niv_estado_logico' => 'Niv Estado Logico',
        ];
    }

    public static function getLevel($usu_id, $emp_id, $ugpr_id){
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        
        $sql = "SELECT 
                    ru.runi_id as rid,
                    n.niv_id as id,
                    n.niv_nombre as Nivel
                FROM 
                    ".$con->dbname.".responsable_unidad as ru
                    INNER JOIN ".$con->dbname.".unidad_gpr as u ON u.ugpr_id = ru.ugpr_id
                    INNER JOIN ".$con->dbname.".nivel as n ON n.niv_id = ru.niv_id
                    INNER JOIN ".$con2->dbname.".empresa as e ON e.emp_id = ru.emp_id
                    INNER JOIN ".$con2->dbname.".usuario as us ON us.usu_id = ru.usu_id
                    INNER JOIN ".$con2->dbname.".persona as p ON p.per_id = us.per_id
                WHERE 
                    ru.usu_id = :usu_id AND ru.emp_id = :emp_id AND ru.ugpr_id = :ugpr_id AND 
                    n.niv_estado_logico=1 AND
                    u.ugpr_estado_logico=1 AND
                    e.emp_estado_logico=1 AND
                    us.usu_estado_logico=1 AND
                    ru.runi_estado_logico=1 
                ORDER BY ru.runi_id desc;";
        $comando = Yii::$app->db->createCommand($sql);
        $comando->bindParam(":usu_id",$usu_id, \PDO::PARAM_INT);
        $comando->bindParam(":emp_id",$emp_id, \PDO::PARAM_INT);
        $comando->bindParam(":ugpr_id",$ugpr_id, \PDO::PARAM_INT);
        $res = $comando->queryOne();
        return $res['id'];
    }
}
