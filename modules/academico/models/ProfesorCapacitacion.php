<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "profesor_capacitacion".
 *
 * @property int $pcap_id
 * @property int $pro_id
 * @property string $pcap_evento
 * @property string $pcap_institucion
 * @property string $pcap_anio
 * @property string $pcap_tipo
 * @property string $pcap_duracion
 * @property int $pcap_usuario_ingreso
 * @property int $pcap_usuario_modifica
 * @property string $pcap_estado
 * @property string $pcap_fecha_creacion
 * @property string $pcap_fecha_modificacion
 * @property string $pcap_estado_logico
 *
 * @property Profesor $pro
 */
class ProfesorCapacitacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesor_capacitacion';
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
            [['pro_id', 'pcap_evento', 'pcap_institucion', 'pcap_anio', 'pcap_tipo', 'pcap_duracion', 'pcap_usuario_ingreso', 'pcap_estado', 'pcap_estado_logico'], 'required'],
            [['pro_id', 'pcap_usuario_ingreso', 'pcap_usuario_modifica'], 'integer'],
            [['pcap_fecha_creacion', 'pcap_fecha_modificacion'], 'safe'],
            [['pcap_evento', 'pcap_institucion', 'pcap_tipo'], 'string', 'max' => 200],
            [['pcap_anio'], 'string', 'max' => 4],
            [['pcap_duracion'], 'string', 'max' => 20],
            [['pcap_estado', 'pcap_estado_logico'], 'string', 'max' => 1],
            [['pro_id'], 'exist', 'skipOnError' => true, 'targetClass' => Profesor::className(), 'targetAttribute' => ['pro_id' => 'pro_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pcap_id' => 'Pcap ID',
            'pro_id' => 'Pro ID',
            'pcap_evento' => 'Pcap Evento',
            'pcap_institucion' => 'Pcap Institucion',
            'pcap_anio' => 'Pcap Anio',
            'pcap_tipo' => 'Pcap Tipo',
            'pcap_duracion' => 'Pcap Duracion',
            'pcap_usuario_ingreso' => 'Pcap Usuario Ingreso',
            'pcap_usuario_modifica' => 'Pcap Usuario Modifica',
            'pcap_estado' => 'Pcap Estado',
            'pcap_fecha_creacion' => 'Pcap Fecha Creacion',
            'pcap_fecha_modificacion' => 'Pcap Fecha Modificacion',
            'pcap_estado_logico' => 'Pcap Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPro()
    {
        return $this->hasOne(Profesor::className(), ['pro_id' => 'pro_id']);
    }

    public function getItems($format=false){
        $arr_data = [
            0 => ['id' => 1, 'nombre' => 'Curso'],
            1 => ['id' => 2, 'nombre' => 'Seminario'],
            2 => ['id' => 3, 'nombre' => 'Taller'],
            3 => ['id' => 4, 'nombre' => 'Congreso'],
            4 => ['id' => 5, 'nombre' => 'Encuentro'],
            5 => ['id' => 6, 'nombre' => 'Otro'],
        ];
        if($format){
            $arr_data = [
                1 => 'Curso',
                2 => 'Seminario',
                3 => 'Taller',
                4 => 'Congreso',
                5 => 'Encuentro',
                6 => 'Otro',
            ];
        }
        return $arr_data;
    }


    function getAllCapacitacionGrid($pro_id, $onlyData=false){
        $con_academico = \Yii::$app->db_academico;
        $sql = "SELECT 
                    p.pcap_id as Ids,
                    pro.pro_id,
                    pro.per_id,
                    p.pcap_evento as Evento,
                    p.pcap_institucion as Institucion,
                    p.pcap_anio as Anio, 
                    p.pcap_tipo as Tipo, 
                    p.pcap_duracion as Duracion
                FROM " . $con_academico->dbname . ".profesor AS pro
                inner JOIN " . $con_academico->dbname . ".profesor_capacitacion as p on pro.pro_id = p.pro_id
                WHERE pro.pro_estado_logico = 1 and pro.pro_estado = 1 and p.pcap_estado_logico = 1 
                and p.pcap_estado = 1 and pro.pro_id =:proId";
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
                'attributes' => ['Evento', 'Institucion',"Anio","Tipo","Duracion"],
            ],
        ]);

        return $dataProvider;
    }

    function getDataToStorage($pro_id){
        $data = $this->getAllCapacitacionGrid($pro_id, true);

        $arrData = array();
        $arrLabel = array();
        $btnactions = array();

        $eventosId = $this->getItems(true);

        foreach($data as $key => $value){
            $arrData[] = [ 
                $value['Ids'], 
                $value['Evento'], 
                $value['Institucion'], 
                $value['Anio'], 
                $value['Tipo'], 
                $value['Duracion'], 
            ];
        }
        foreach($data as $key => $value){
            $arrLabel[] = [ 
                $value['Ids'], 
                $value['Evento'], 
                $value['Institucion'], 
                $value['Anio'], 
                $eventosId[$value['Tipo']], 
                $value['Duracion'], 
            ];
        }
        foreach($data as $key => $value){
            $btnactions[] = [[ 
                "id" => "deleteN".$value['Ids'],
                "class" => "",
                "href" => "",
                "onclick" => "javascript:removeItemEvento(this)",
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
            $sql = "UPDATE " . $con_academico->dbname . ".profesor_capacitacion 
                SET pcap_estado_logico=0, pcap_estado=0 
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
