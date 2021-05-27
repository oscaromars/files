<?php

namespace app\modules\academico\models;

use Exception;
use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_instruccion".
 *
 * @property int $pins_id
 * @property int $pro_id
 * @property int $nins_id
 * @property string $pins_institucion
 * @property string $pins_especializacion
 * @property string $pins_titulo
 * @property string $pins_senescyt
 * @property int $pins_usuario_ingreso
 * @property int $pins_usuario_modifica
 * @property string $pins_estado
 * @property string $pins_fecha_creacion
 * @property string $pins_fecha_modificacion
 * @property string $pins_estado_logico
 *
 * @property Profesor $pro
 * @property NivelInstruccion $nins
 */
class ProfesorInstruccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_instruccion';
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
            [['pro_id', 'nins_id', 'pins_titulo', 'pins_usuario_ingreso', 'pins_estado', 'pins_estado_logico'], 'required'],
            [['pro_id', 'nins_id', 'pins_usuario_ingreso', 'pins_usuario_modifica'], 'integer'],
            [['pins_fecha_creacion', 'pins_fecha_modificacion'], 'safe'],
            [['pins_institucion', 'pins_especializacion'], 'string', 'max' => 150],
            [['pins_titulo'], 'string', 'max' => 200],
            [['pins_senescyt'], 'string', 'max' => 50],
            [['pins_estado', 'pins_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
            [['nins_id'], 'exist', 'skipOnError' => true, 'targetClass' => NivelInstruccion::className(), 'targetAttribute' => ['nins_id' => 'nins_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pins_id' => 'Pins ID',
            'pro_id' => 'Pro ID',
            'nins_id' => 'Nins ID',
            'pins_institucion' => 'Pins Institucion',
            'pins_especializacion' => 'Pins Especializacion',
            'pins_titulo' => 'Pins Titulo',
            'pins_senescyt' => 'Pins Senescyt',
            'pins_usuario_ingreso' => 'Pins Usuario Ingreso',
            'pins_usuario_modifica' => 'Pins Usuario Modifica',
            'pins_estado' => 'Pins Estado',
            'pins_fecha_creacion' => 'Pins Fecha Creacion',
            'pins_fecha_modificacion' => 'Pins Fecha Modificacion',
            'pins_estado_logico' => 'Pins Estado Logico',
        ];
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
    public function getNins()
    {
        return $this->hasOne(NivelInstruccion::className(), ['nins_id' => 'nins_id']);
    }

    function getAllInstruccionGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;

        $sql = "SELECT 
                    pins.pins_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    nins.nins_id as nins_id,
                    nins.nins_nombre as Instruccion,
                    pins.pins_institucion as NombreInstitucion,
                    pins.pins_especializacion as Especializacion, 
                    pins.pins_titulo as Titulo, 
                    pins.pins_senescyt as Registro
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_instruccion as pins on pro.pro_id = pins.pro_id
                inner JOIN " . $con_academico->dbname . ".nivel_instruccion as nins on nins.nins_id = pins.nins_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and pins.pins_estado_logico = 1 
                and pins.pins_estado = 1 and pro.pro_id =:proId";
        $comando = $con_academico->createCommand($sql);
        $comando->bindParam(':proId', $pro_id, \PDO::PARAM_INT);
        $res = $comando->queryAll();

        if($onlyData)   return $res;

        $dataProvider = new ArrayDataProvider([
            'key' => 'Ids',
            'allModels' => $res,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ['Instruccion', 'NombreInstitucion',"Especializacion","Titulo","Registro"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllInstruccionGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['nins_id'], 
                $value['NombreInstitucion'], 
                $value['Especializacion'], 
                $value['Titulo'], 
                $value['Registro'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Instruccion'], 
                $value['NombreInstitucion'], 
                $value['Especializacion'], 
                $value['Titulo'], 
                $value['Registro'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemInstitucion(this)",
                "tipo_accion" => "delete",
                "title" => Yii::t("accion","Delete")
            ]];
        }
        return [$arrData, $arrLabel, $btnactions];
    }

    public static function deleteAllInfo($pro_id){
        $con_academico = \Yii::$app->db_academico;
        $trans = $con_academico->beginTransaction();
        try{
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_instruccion 
                SET pins_estado_logico=0, pins_estado=0 
                WHERE pro_id=:proId";

            $comando = $con_academico->createCommand($sql);
            $comando->bindParam(':proId', $pro_id, \PDO::PARAM_INT);
            $res = $comando->execute();
            if($res){
                $trans->commit();
                return true;
            }else
                throw new \Exception('Error');
        }catch(\Exception $e){
            $trans->rollBack();
            return false;
        }
    }
    
}
