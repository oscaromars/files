<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "estrategia_objestr".
 *
 * @property int $eoet_id
 * @property int $oest_id
 * @property string $eoet_nombre
 * @property string $eoet_descripcion
 * @property int $eoet_usuario_ingreso
 * @property int|null $eoet_usuario_modifica
 * @property string $eoet_estado
 * @property string $eoet_fecha_creacion
 * @property string|null $eoet_fecha_modificacion
 * @property string $eoet_estado_logico
 *
 * @property ObjetivoEstrategico $oest
 */
class EstrategiaObjEstr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estrategia_objestr';
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
            [['oest_id', 'eoet_nombre', 'eoet_descripcion', 'eoet_usuario_ingreso', 'eoet_estado', 'eoet_estado_logico'], 'required'],
            [['oest_id', 'eoet_usuario_ingreso', 'eoet_usuario_modifica'], 'integer'],
            [['eoet_fecha_creacion', 'eoet_fecha_modificacion'], 'safe'],
            [['eoet_nombre'], 'string', 'max' => 300],
            [['eoet_descripcion'], 'string', 'max' => 500],
            [['eoet_estado', 'eoet_estado_logico'], 'string', 'max' => 1],
            [['oest_id'], 'exist', 'skipOnError' => true, 'targetClass' => ObjetivoEstrategico::className(), 'targetAttribute' => ['oest_id' => 'oest_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eoet_id' => 'Eoet ID',
            'oest_id' => 'Oest ID',
            'eoet_nombre' => 'Eoet Nombre',
            'eoet_descripcion' => 'Eoet Descripcion',
            'eoet_usuario_ingreso' => 'Eoet Usuario Ingreso',
            'eoet_usuario_modifica' => 'Eoet Usuario Modifica',
            'eoet_estado' => 'Eoet Estado',
            'eoet_fecha_creacion' => 'Eoet Fecha Creacion',
            'eoet_fecha_modificacion' => 'Eoet Fecha Modificacion',
            'eoet_estado_logico' => 'Eoet Estado Logico',
        ];
    }

    /**
     * Gets query for [[Oest]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOest()
    {
        return $this->hasOne(ObjetivoEstrategico::className(), ['oest_id' => 'oest_id']);
    }

    public function getAllEstrategiasEstrGrid($search = NULL, $objetivo = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(es.eoet_nombre like :search OR ";
            $str_search .= "es.eoet_descripcion like :search) AND ";
        }
        if(isset($objetivo) && $objetivo > 0){
            $str_search .= "oe.oest_id = :objetivo AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    es.eoet_id as id,
                    es.eoet_nombre as Nombre,
                    es.eoet_descripcion as Descripcion,
                    oe.oest_nombre as Objetivo,
                    es.eoet_estado as Estado
                FROM 
                    ".$con->dbname.".estrategia_objestr as es
                    INNER JOIN ".$con->dbname.".objetivo_estrategico as oe ON oe.oest_id = es.oest_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id
                    INNER JOIN ".$con->dbname.".entidad as e ON e.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = e.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON e.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = rp.usu_id
                WHERE 
                    $str_search_no_admin
                    $str_search
                    oe.oest_estado_logico=1 AND
                    es.eoet_estado_logico=1 
                GROUP BY
                    es.eoet_id, es.eoet_nombre, es.eoet_descripcion, oe.oest_nombre, es.eoet_estado
                ORDER BY es.eoet_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($objetivo) && $objetivo > 0){
            $comando->bindParam(":objetivo",$objetivo, \PDO::PARAM_INT);
        }
        $res = $comando->queryAll();
        if($dataProvider){
            $dataProvider = new ArrayDataProvider([
                'key' => 'id',
                'allModels' => $res,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ['Nombre', 'Estado', 'Descripcion'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
