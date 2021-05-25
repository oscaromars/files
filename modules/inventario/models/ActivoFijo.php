<?php

namespace app\modules\inventario\models;
use yii\data\ArrayDataProvider;

use Yii;

/**
 * This is the model class for table "activo_fijo".
 *
 * @property int $afij_id
 * @property int $einv_id
 * @property int $are_id
 * @property int $cat_id
 * @property int $afij_secuencia
 * @property string $afij_codigo
 * @property string $afij_custodio
 * @property string $afij_descripcion
 * @property string $afij_marca
 * @property string $afij_modelo
 * @property string $afij_num_serie
 * @property int $afij_cantidad
 * @property string $afij_ram
 * @property string $afij_disco_hdd
 * @property string $afij_disco_ssd
 * @property string $afij_procesador
 * @property string $afij_ip
 * @property string $afij_estado
 * @property string $afij_fecha_creacion
 * @property string $afij_fecha_modificacion
 * @property string $afij_estado_logico
 *
 * @property Categoria $cat
 * @property EmpresaInventario $einv
 */
class ActivoFijo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activo_fijo';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_inventario');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['einv_id', 'are_id', 'cat_id', 'afij_secuencia', 'afij_codigo', 'afij_custodio', 'afij_estado', 'afij_estado_logico'], 'required'],
            [['einv_id', 'are_id', 'cat_id', 'afij_secuencia', 'afij_cantidad'], 'integer'],
            [['afij_fecha_creacion', 'afij_fecha_modificacion'], 'safe'],
            [['afij_codigo'], 'string', 'max' => 50],
            [['afij_custodio'], 'string', 'max' => 200],
            [['afij_descripcion'], 'string', 'max' => 1000],
            [['afij_marca', 'afij_modelo', 'afij_num_serie', 'afij_ram', 'afij_disco_hdd', 'afij_disco_ssd', 'afij_procesador', 'afij_ip'], 'string', 'max' => 100],
            [['afij_estado', 'afij_estado_logico'], 'string', 'max' => 1],
            [['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
            [['einv_id'], 'exist', 'skipOnError' => true, 'targetClass' => EmpresaInventario::className(), 'targetAttribute' => ['einv_id' => 'einv_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'afij_id' => 'Afij ID',
            'einv_id' => 'Einv ID',
            'are_id' => 'Are ID',
            'cat_id' => 'Cat ID',
            'afij_secuencia' => 'Afij Secuencia',
            'afij_codigo' => 'Afij Codigo',
            'afij_custodio' => 'Afij Custodio',
            'afij_descripcion' => 'Afij Descripcion',
            'afij_marca' => 'Afij Marca',
            'afij_modelo' => 'Afij Modelo',
            'afij_num_serie' => 'Afij Num Serie',
            'afij_cantidad' => 'Afij Cantidad',
            'afij_ram' => 'Afij Ram',
            'afij_disco_hdd' => 'Afij Disco Hdd',
            'afij_disco_ssd' => 'Afij Disco Ssd',
            'afij_procesador' => 'Afij Procesador',
            'afij_ip' => 'Afij Ip',
            'afij_estado' => 'Afij Estado',
            'afij_fecha_creacion' => 'Afij Fecha Creacion',
            'afij_fecha_modificacion' => 'Afij Fecha Modificacion',
            'afij_estado_logico' => 'Afij Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCat()
    {
        return $this->hasOne(Categoria::className(), ['cat_id' => 'cat_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEinv()
    {
        return $this->hasOne(EmpresaInventario::className(), ['einv_id' => 'einv_id']);
    }
    
    /**
     * Function consultarInventario consultar inventario
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param 
     * @return
     */
    public function consultarInventario($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_inventario;        
        $con1 = \Yii::$app->db_general;        
        $estado = 1;
        $str_search = "";   
        if (isset($arrFiltro) && count($arrFiltro) > 0) {                            
            if ($arrFiltro['search'] != "") {
                $str_search = " (af.afij_codigo like :codigo  
                                or af.afij_custodio like :codigo) AND ";
            }            
            if (($arrFiltro['tipobien_id'] != "") && ($arrFiltro['tipobien_id'] > 0)){
                $str_search .= "c.tbie_id = :tipobien_id AND ";
            }
            if (($arrFiltro['categoria_id'] != "") && ($arrFiltro['categoria_id'] > 0)){
                $str_search .= "af.cat_id = :categoria_id AND ";
            }
            if (($arrFiltro['departamento_id'] != "") && ($arrFiltro['departamento_id'] > 0)){
                $str_search .= "(a.dep_id = :departamento_id or ed.dep_id = :departamento_id) AND ";
            }
            if (($arrFiltro['area_id'] != "") && ($arrFiltro['area_id'] > 0)){
                $str_search .= "af.are_id = :area_id AND ";
            }
        }
        $sql = "SELECT 	case when af.are_id is null then
                            dep.dep_nombre else d.dep_nombre end as departamento,
                        ifnull(a.are_descripcion,'N/A') as area, 
                        ifnull(ed.edep_descripcion,'N/A') as espacio, 
                        c.cat_descripcion as categoria,
                        af.afij_codigo, af.afij_custodio,                                       
                        ifnull(af.afij_cantidad,0) as afij_cantidad
                FROM " . $con->dbname . ".activo_fijo af 
                     inner join " . $con->dbname . ".empresa_inventario ei on af.einv_id = ei.einv_id
                     left join " . $con1->dbname . ".area a on a.are_id = af.are_id
                     left join " . $con->dbname . ".espacio_departamento ed on ed.edep_id = af.edep_id
                     left join " . $con1->dbname . ".departamento d on d.dep_id = a.dep_id 
                     inner join " . $con->dbname . ".categoria c on c.cat_id = af.cat_id
                     left join " . $con1->dbname . ".departamento dep on dep.dep_id = ed.dep_id
                WHERE $str_search
                      afij_estado = :estado and
                      afij_estado_logico = :estado
                ORDER BY 1,2,3";        
                       
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {           
            $codigo = "%" . $arrFiltro["search"] . "%";              
            $tipobien_id = $arrFiltro["tipobien_id"];
            $categoria_id = $arrFiltro["categoria_id"];
            $departamento_id = $arrFiltro["departamento_id"];
            $area_id = $arrFiltro["area_id"];
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":codigo", $codigo, \PDO::PARAM_STR);
            }             
            if (($arrFiltro['tipobien_id'] != "") && ($arrFiltro['tipobien_id'] > 0)){
                $comando->bindParam(":tipobien_id", $tipobien_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['categoria_id'] != "") && ($arrFiltro['categoria_id'] > 0)){
                $comando->bindParam(":categoria_id", $categoria_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['departamento_id'] != "") && ($arrFiltro['departamento_id'] > 0)){
                $comando->bindParam(":departamento_id", $departamento_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['area_id'] != "") && ($arrFiltro['area_id'] > 0)){
                $comando->bindParam(":area_id", $area_id, \PDO::PARAM_INT);
            }
        }
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
    
    /**
     * Function consultarInventario consultar inventario
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param 
     * @return
     */
    public function consultarInventarioExcel($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_inventario;        
        $con1 = \Yii::$app->db_general;        
        $estado = 1;
        $str_search = "";   
        if (isset($arrFiltro) && count($arrFiltro) > 0) {                            
            if ($arrFiltro['search'] != "") {
                $str_search = " (af.afij_codigo like :codigo  
                                or af.afij_custodio like :codigo) AND ";
            }            
            if (($arrFiltro['tipobien_id'] != "") && ($arrFiltro['tipobien_id'] > 0)){
                $str_search .= "c.tbie_id = :tipobien_id AND ";
            }
            if (($arrFiltro['categoria_id'] != "") && ($arrFiltro['categoria_id'] > 0)){
                $str_search .= "af.cat_id = :categoria_id AND ";
            }
            if (($arrFiltro['departamento_id'] != "") && ($arrFiltro['departamento_id'] > 0)){
                $str_search .= "(a.dep_id = :departamento_id or ed.dep_id = :departamento_id) AND ";
            }
            if (($arrFiltro['area_id'] != "") && ($arrFiltro['area_id'] > 0)){
                $str_search .= "af.are_id = :area_id AND ";
            }
        }
        $sql = "SELECT 	af.afij_codigo, 
                        case when af.are_id is null then
                            dep.dep_nombre else d.dep_nombre end as departamento,
                        ifnull(a.are_descripcion,'N/A') as area,                         
                        ed.edep_descripcion as espacio,
                        case when (ed.edep_id > 0) then
                                (select edi_descripcion from " . $con1->dbname . ".edificio e where e.edi_id = ed.edi_id)
                            else (select edi_descripcion from " . $con1->dbname . ".edificio e where e.edi_id = a.edi_id) end as edificio,
                        af.afij_custodio, 
                        t.tbie_descripcion as tipo_bien,
                        c.cat_descripcion as categoria,
                        af.afij_secuencia,                                             
                        ifnull(af.afij_cantidad,0) as afij_cantidad,
                        ifnull(af.afij_descripcion,'') as descripcion,
                        ifnull(af.afij_marca,'') as marca,
                        ifnull(af.afij_modelo,'') as modelo,
                        ifnull(af.afij_num_serie,'') as serie,
                        ifnull(af.afij_ram,'') as ram,
                        ifnull(af.afij_disco_hdd,'') as discoh,
                        ifnull(af.afij_disco_ssd,'') as discos,
                        ifnull(af.afij_procesador,'') as procesador                      
                FROM " . $con->dbname . ".activo_fijo af 
                     inner join " . $con->dbname . ".empresa_inventario ei on af.einv_id = ei.einv_id
                     left join " . $con1->dbname . ".area a on a.are_id = af.are_id
                     left join " . $con->dbname . ".espacio_departamento ed on ed.edep_id = af.edep_id
                     inner join " . $con->dbname . ".categoria c on c.cat_id = af.cat_id
                     left join " . $con1->dbname . ".departamento d on d.dep_id = a.dep_id 
                     inner join " . $con->dbname . ".tipo_bien t on t.tbie_id = c.tbie_id                                                                   
                     left join " . $con1->dbname . ".departamento dep on dep.dep_id = ed.dep_id
                WHERE $str_search
                      afij_estado = :estado and 
                      afij_estado_logico = :estado
                ORDER BY 1";                
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {           
            $codigo = "%" . $arrFiltro["search"] . "%";              
            $tipobien_id = $arrFiltro["tipobien_id"];
            $categoria_id = $arrFiltro["categoria_id"];
            $departamento_id = $arrFiltro["departamento_id"];
            $area_id = $arrFiltro["area_id"];
            if ($arrFiltro['search'] != "") {
                $comando->bindParam(":codigo", $codigo, \PDO::PARAM_STR);
            }             
            if (($arrFiltro['tipobien_id'] != "") && ($arrFiltro['tipobien_id'] > 0)){
                $comando->bindParam(":tipobien_id", $tipobien_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['categoria_id'] != "") && ($arrFiltro['categoria_id'] > 0)){
                $comando->bindParam(":categoria_id", $categoria_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['departamento_id'] != "") && ($arrFiltro['departamento_id'] > 0)){
                $comando->bindParam(":departamento_id", $departamento_id, \PDO::PARAM_INT);
            }
            if (($arrFiltro['area_id'] != "") && ($arrFiltro['area_id'] > 0)){
                $comando->bindParam(":area_id", $area_id, \PDO::PARAM_INT);
            }
        }
        $resultData = $comando->queryAll();
        return $resultData;
        /*$dataProvider = new ArrayDataProvider([
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
        }*/
    } 
}
