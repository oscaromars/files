<?php

namespace app\modules\gpr\models;

use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;

/**
 * This is the model class for table "objetivo_estrategico".
 *
 * @property int $oest_id
 * @property int $pped_id
 * @property int $enf_id
 * @property int $cbsc_id
 * @property string $oest_nombre
 * @property string $oest_descripcion
 * @property string|null $oest_fecha_actualizacion
 * @property int $oest_usuario_ingreso
 * @property int|null $oest_usuario_modifica
 * @property string $oest_estado
 * @property string $oest_fecha_creacion
 * @property string|null $oest_fecha_modificacion
 * @property string $oest_estado_logico
 *
 * @property EstrategiaObjestr[] $estrategiaObjestrs
 * @property ObjetivoEspecifico[] $objetivoEspecificos
 * @property PlanificacionPedi $pped
 * @property Enfoque $enf
 * @property CategoriaBsc $cbsc
 */
class ObjetivoEstrategico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'objetivo_estrategico';
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
            [['pped_id', 'enf_id', 'cbsc_id', 'oest_nombre', 'oest_descripcion', 'oest_usuario_ingreso', 'oest_estado', 'oest_estado_logico'], 'required'],
            [['pped_id', 'enf_id', 'cbsc_id', 'oest_usuario_ingreso', 'oest_usuario_modifica'], 'integer'],
            [['oest_fecha_actualizacion', 'oest_fecha_creacion', 'oest_fecha_modificacion'], 'safe'],
            [['oest_nombre'], 'string', 'max' => 300],
            [['oest_descripcion'], 'string', 'max' => 500],
            [['oest_estado', 'oest_estado_logico'], 'string', 'max' => 1],
            [['pped_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlanificacionPedi::className(), 'targetAttribute' => ['pped_id' => 'pped_id']],
            [['enf_id'], 'exist', 'skipOnError' => true, 'targetClass' => Enfoque::className(), 'targetAttribute' => ['enf_id' => 'enf_id']],
            [['cbsc_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoriaBsc::className(), 'targetAttribute' => ['cbsc_id' => 'cbsc_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'oest_id' => 'Oest ID',
            'pped_id' => 'Pped ID',
            'enf_id' => 'Enf ID',
            'cbsc_id' => 'Cbsc ID',
            'oest_nombre' => 'Oest Nombre',
            'oest_descripcion' => 'Oest Descripcion',
            'oest_fecha_actualizacion' => 'Oest Fecha Actualizacion',
            'oest_usuario_ingreso' => 'Oest Usuario Ingreso',
            'oest_usuario_modifica' => 'Oest Usuario Modifica',
            'oest_estado' => 'Oest Estado',
            'oest_fecha_creacion' => 'Oest Fecha Creacion',
            'oest_fecha_modificacion' => 'Oest Fecha Modificacion',
            'oest_estado_logico' => 'Oest Estado Logico',
        ];
    }

    /**
     * Gets query for [[EstrategiaObjestrs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEstrategiaObjestrs()
    {
        return $this->hasMany(EstrategiaObjestr::className(), ['oest_id' => 'oest_id']);
    }

    /**
     * Gets query for [[ObjetivoEspecificos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getObjetivoEspecificos()
    {
        return $this->hasMany(ObjetivoEspecifico::className(), ['oest_id' => 'oest_id']);
    }

    /**
     * Gets query for [[Pped]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPped()
    {
        return $this->hasOne(PlanificacionPedi::className(), ['pped_id' => 'pped_id']);
    }

    /**
     * Gets query for [[Enf]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEnf()
    {
        return $this->hasOne(Enfoque::className(), ['enf_id' => 'enf_id']);
    }

    /**
     * Gets query for [[Cbsc]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCbsc()
    {
        return $this->hasOne(CategoriaBsc::className(), ['cbsc_id' => 'cbsc_id']);
    }

    public function getAllObjEstGrid($search = NULL, $bsc = NULL, $enfoque = NULL, $plan = NULL, $dataProvider = false){
        $search_cond = "%".$search."%";
        $str_search = "";
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $emp_id  = Yii::$app->session->get("PB_idempresa", FALSE);
        $con = Yii::$app->db_gpr;
        $con2 = Yii::$app->db;
        if(isset($search)){
            $str_search  = "(oe.oest_nombre like :search OR ";
            $str_search .= "oe.oest_descripcion like :search) AND ";
        }
        if(isset($bsc) && $bsc > 0){
            $str_search .= "ca.cbsc_id = :categoria AND ";
        }
        if(isset($enfoque) && $enfoque > 0){
            $str_search .= "en.enf_id = :enfoque AND ";
        }
        if(isset($plan) && $plan > 0){
            $str_search .= "pl.pped_id = :plan AND ";
        }
        if(ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
            $str_search .= " em.emp_id = $emp_id AND ";
        }elseif($user_id != 1){
            $str_search .= " em.emp_id = $emp_id AND us.usu_id = $user_id AND ";
        }
        $str_search_no_admin = " pl.pped_estado_cierre = '0' AND ";
        $sql = "SELECT 
                    oe.oest_id as id,
                    oe.oest_nombre as Nombre,
                    oe.oest_descripcion as Descripcion,
                    en.enf_nombre as Enfoque,
                    ca.cbsc_nombre as CategoriaBSC,
                    pl.pped_nombre as PlanificacionPedi,
                    pl.pped_fecha_inicio as FechaInicio,
                    pl.pped_fecha_fin as FechaFin,
                    oe.oest_fecha_actualizacion as FechaActualizacion,
                    pl.pped_estado_cierre as CierrePedi,
                    oe.oest_estado as Estado
                FROM 
                    ".$con->dbname.".objetivo_estrategico AS oe
                    INNER JOIN ".$con->dbname.".enfoque AS en ON en.enf_id = oe.enf_id
                    INNER JOIN ".$con->dbname.".categoria_bsc AS ca ON ca.cbsc_id = oe.cbsc_id
                    INNER JOIN ".$con->dbname.".planificacion_pedi AS pl ON pl.pped_id = oe.pped_id
                    INNER JOIN ".$con->dbname.".entidad AS ent ON ent.ent_id = pl.ent_id
                    INNER JOIN ".$con->dbname.".unidad_gpr AS un ON un.ent_id = ent.ent_id
                    INNER JOIN ".$con->dbname.".responsable_unidad AS rp ON rp.ugpr_id = un.ugpr_id
                    INNER JOIN ".$con2->dbname.".empresa AS em ON ent.emp_id = em.emp_id
                    INNER JOIN ".$con2->dbname.".usuario AS us ON us.usu_id = rp.usu_id
                WHERE 
                    $str_search_no_admin
                    $str_search
                    oe.oest_estado_logico=1 AND
                    en.enf_estado_logico=1 AND 
                    ca.cbsc_estado_logico=1 AND
                    pl.pped_estado_logico=1 
                GROUP BY
                    oe.oest_id, oe.oest_nombre, oe.oest_descripcion, en.enf_nombre, ca.cbsc_nombre, pl.pped_nombre, pl.pped_fecha_inicio,
                    pl.pped_fecha_fin, oe.oest_fecha_actualizacion, pl.pped_estado_cierre, oe.oest_estado
                ORDER BY oe.oest_id;";
        $comando = Yii::$app->db->createCommand($sql);
        if(isset($search)){
            $comando->bindParam(":search",$search_cond, \PDO::PARAM_STR);
        }
        if(isset($bsc) && $bsc > 0){
            $comando->bindParam(":categoria",$bsc, \PDO::PARAM_INT);
        }
        if(isset($enfoque) && $enfoque > 0){
            $comando->bindParam(":enfoque",$enfoque, \PDO::PARAM_INT);
        }
        if(isset($plan) && $plan > 0){
            $comando->bindParam(":plan",$plan, \PDO::PARAM_INT);
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
                    'attributes' => ['Nombre', 'Estado', 'Descripcion', 'Enfoque', 'CategoriaBSC'],
                ],
            ]);
            return $dataProvider;
        }
        return $res;
    }
}
