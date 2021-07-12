<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\models\Utilities;

/**
 * This is the model class for table "reglamento".
 *
 * @property int $reg_id
 * @property int $emp_id
 * @property string $reg_nombre
 * @property string $reg_descripcion
 * @property string $reg_archivo
 * @property int $reg_usuario_ingreso
 * @property int $reg_usuario_modifica
 * @property string $reg_estado
 * @property string $reg_fecha_creacion
 * @property string $reg_fecha_modificacion
 * @property string $reg_estado_logico
 */
class Reglamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reglamento';
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
            [['emp_id', 'reg_nombre', 'reg_archivo', 'reg_usuario_ingreso', 'reg_estado', 'reg_estado_logico'], 'required'],
            [['emp_id', 'reg_usuario_ingreso', 'reg_usuario_modifica'], 'integer'],
            [['reg_fecha_creacion', 'reg_fecha_modificacion'], 'safe'],
            [['reg_nombre'], 'string', 'max' => 100],
            [['reg_descripcion', 'reg_archivo'], 'string', 'max' => 500],
            [['reg_estado', 'reg_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reg_id' => 'Reg ID',
            'emp_id' => 'Emp ID',
            'reg_nombre' => 'Reg Nombre',
            'reg_descripcion' => 'Reg Descripcion',
            'reg_archivo' => 'Reg Archivo',
            'reg_usuario_ingreso' => 'Reg Usuario Ingreso',
            'reg_usuario_modifica' => 'Reg Usuario Modifica',
            'reg_estado' => 'Reg Estado',
            'reg_fecha_creacion' => 'Reg Fecha Creacion',
            'reg_fecha_modificacion' => 'Reg Fecha Modificacion',
            'reg_estado_logico' => 'Reg Estado Logico',
        ];
    }

    /**
     * Function consultar informacion del estudiantes
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property
     * @return
     */
    public function consultaReglamento($onlyData = false) {
        $con = \Yii::$app->db_academico;
        //$con1 = \Yii::$app->db_asgard;
        $estado = '1';
        $sql = "SELECT
                    reg_id,
                    reg_descripcion,
                    reg_archivo,
                    DATE_FORMAT(reg_fecha_creacion,'%Y-%m-%d') as reg_fecha_creacion
                FROM " . $con->dbname . ".reglamento
                WHERE
                reg_estado = :estado AND
                reg_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);

        $resultData = $comando->queryAll();
        $dataProvider = new ArrayDataProvider([
            'key' => 'id',
            'allModels' => $resultData,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => [
                ],
            ],
        ]);
        if ($onlyData) {
            return $resultData;
        } else {
            return $dataProvider;
        }
    }
}
