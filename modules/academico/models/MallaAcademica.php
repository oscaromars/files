<?php

namespace app\modules\academico\models;
use yii\data\ArrayDataProvider;
use app\models\Utilities;
use Yii;

/**
 * This is the model class for table "malla_academica".
 *
 * @property int $maca_id
 * @property string $maca_tipo
 * @property string $maca_codigo
 * @property string $maca_nombre
 * @property string $maca_fecha_vigencia_inicio
 * @property string $maca_fecha_vigencia_fin
 * @property int $maca_usuario_ingreso
 * @property int $maca_usuario_modifica
 * @property string $maca_estado
 * @property string $maca_fecha_creacion
 * @property string $maca_fecha_modificacion
 * @property string $maca_estado_logico
 *
 * @property MallaAcademicaDetalle[] $mallaAcademicaDetalles
 * @property MallaUnidadModalidad[] $mallaUnidadModalidads
 * @property PlanificacionAcademicaMalla[] $planificacionAcademicaMallas
 */
class MallaAcademica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'malla_academica';
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
            [['maca_nombre', 'maca_usuario_ingreso', 'maca_estado', 'maca_estado_logico'], 'required'],
            [['maca_fecha_vigencia_inicio', 'maca_fecha_vigencia_fin', 'maca_fecha_creacion', 'maca_fecha_modificacion'], 'safe'],
            [['maca_usuario_ingreso', 'maca_usuario_modifica'], 'integer'],
            [['maca_tipo', 'maca_estado', 'maca_estado_logico'], 'string', 'max' => 1],
            [['maca_codigo'], 'string', 'max' => 50],
            [['maca_nombre'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'maca_id' => 'Maca ID',
            'maca_tipo' => 'Maca Tipo',
            'maca_codigo' => 'Maca Codigo',
            'maca_nombre' => 'Maca Nombre',
            'maca_fecha_vigencia_inicio' => 'Maca Fecha Vigencia Inicio',
            'maca_fecha_vigencia_fin' => 'Maca Fecha Vigencia Fin',
            'maca_usuario_ingreso' => 'Maca Usuario Ingreso',
            'maca_usuario_modifica' => 'Maca Usuario Modifica',
            'maca_estado' => 'Maca Estado',
            'maca_fecha_creacion' => 'Maca Fecha Creacion',
            'maca_fecha_modificacion' => 'Maca Fecha Modificacion',
            'maca_estado_logico' => 'Maca Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaAcademicaDetalles()
    {
        return $this->hasMany(MallaAcademicaDetalle::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMallaUnidadModalidads()
    {
        return $this->hasMany(MallaUnidadModalidad::className(), ['maca_id' => 'maca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanificacionAcademicaMallas()
    {
        return $this->hasMany(PlanificacionAcademicaMalla::className(), ['maca_id' => 'maca_id']);
    }
    
    
     /**
     * Function consultar mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarMallas($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
        
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "m.maca_nombre like :malla AND ";                
            }            
        }
            
        $sql = "SELECT  maca_id, maca_codigo, maca_nombre, 
                        ifnull(maca_fecha_vigencia_inicio,'') as fechainicial, 
                        ifnull(maca_fecha_vigencia_fin,'') as fechafin
                FROM " . $con->dbname . ".malla_academica m
                WHERE $str_search
                      maca_estado = '1'
                      and maca_estado_logico = '1'";

        $comando = $con->createCommand($sql);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":malla", $search_cond, \PDO::PARAM_STR);
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
     * Function consultar detalle de mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarDetallemallaXid($maca_id, $arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db_academico;
                     
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "(a.asi_nombre like :asignatura) AND ";                
            }            
        }
        $sql = "SELECT  d.made_codigo_asignatura,    
                        a.asi_nombre, 
                        d.made_semestre,
                        d.made_credito,
                        u.uest_nombre,       
                        f.fmac_nombre,
                        ifnull(asi.asi_nombre,'') as materia_requisito
                FROM " . $con->dbname . ".malla_academica m inner join " . $con->dbname . ".malla_academica_detalle d on d.maca_id = m.maca_id
                    inner join " . $con->dbname . ".asignatura a on a.asi_id = d.asi_id
                    inner join " . $con->dbname . ".unidad_estudio u on u.uest_id = d.uest_id
                    inner join " . $con->dbname . ".nivel_estudio n on n.nest_id = d.nest_id
                    inner join " . $con->dbname . ".formacion_malla_academica f on f.fmac_id = d.fmac_id
                    left join " . $con->dbname . ".asignatura asi on asi.asi_id = d.made_asi_requisito
                WHERE $str_search
                    m.maca_id = :malla
                    and m.maca_estado = '1'
                    and m.maca_estado_logico = '1'
                    and d.made_estado = '1'
                    and d.made_estado = '1'";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":malla", $maca_id, \PDO::PARAM_INT);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":asignatura", $search_cond, \PDO::PARAM_STR);
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
     * Function consultar cabecera de mallas académicas
     * @author  Grace Viteri <analistadesarrollo01@uteg.edu.ec>         
     * @property  
     * @return  
     */
    public function consultarCabeceraMalla($maca_id) {
        $con = \Yii::$app->db_academico;
        $estado = '1';                 
        $sql = "SELECT m.maca_id, m.maca_nombre malla, ua.uaca_descripcion unidad, mo.mod_descripcion modalidad, e.eaca_descripcion carrera_programa
                FROM " . $con->dbname . ".malla_academica m inner join " . $con->dbname . ".malla_unidad_modalidad u on u.maca_id = m.maca_id
                    inner join " . $con->dbname . ".modalidad_estudio_unidad mu on mu.meun_id = u.meun_id
                    inner join " . $con->dbname . ".estudio_academico e on e.eaca_id = mu.eaca_id
                    inner join " . $con->dbname . ".unidad_academica ua on ua.uaca_id = mu.uaca_id
                    inner join " . $con->dbname . ".modalidad mo on mo.mod_id = mu.mod_id
                WHERE u.maca_id = :maca_id
                    and m.maca_estado = :estado
                    and m.maca_estado_logico = :estado
                    and u.mumo_estado = :estado
                    and u.mumo_estado_logico = :estado
                    and mu.meun_estado = :estado
                    and mu.meun_estado_logico = :estado
                    and e.eaca_estado = :estado
                    and e.eaca_estado_logico = :estado
                    and ua.uaca_estado = :estado
                    and ua.uaca_estado_logico = :estado
                    and mo.mod_estado = :estado
                    and mo.mod_estado_logico = :estado";

        $comando = $con->createCommand($sql);        
        $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
       
        $resultData = $comando->queryAll();               
        return $resultData;        
    }  

     /**
     * Function obtener consultarmalla por unidad, modalidad, carrera
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property       
     * @return  
     */
    public function consultarmallasxcarrera($uaca_id, $mod_id, $eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 
                    mac.maca_id AS id,
                    concat(mac.maca_codigo,' - ',mac.maca_nombre) AS name  
               FROM " . $con->dbname . ".modalidad_estudio_unidad meu  
               INNER JOIN " . $con->dbname . ".malla_unidad_modalidad mum ON mum.meun_id = meu.meun_id                  
               INNER JOIN " . $con->dbname . ".malla_academica mac ON mac.maca_id = mum.maca_id 
               WHERE  meu.meun_estado_logico = :estado AND
                      meu.meun_estado = :estado AND
                      mum.mumo_estado_logico = :estado AND
                      mum.mumo_estado = :estado AND
                      mac.maca_estado_logico = :estado AND
                      mac.maca_estado = :estado AND
                      meu.uaca_id = :uaca_id AND
                      meu.mod_id = :mod_id AND
                      meu.eaca_id = :eaca_id";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function obtener consultarasignaturaxmalla 
     * @author   Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property       
     * @return  
     */
    public function consultarasignaturaxmalla($maca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT mad.asi_id as id, concat(mad.made_codigo_asignatura, ' - ', asi.asi_nombre) as name
                    FROM " . $con->dbname . ".malla_academica_detalle mad
                    INNER JOIN " . $con->dbname . ".asignatura asi ON asi.asi_id = mad.asi_id
                    WHERE   mad.maca_id = :maca_id AND
                            mad.made_estado = :estado AND
                            mad.made_estado_logico = :estado AND
                            asi.asi_estado = :estado AND
                            asi.asi_estado_logico = :estado
                    ORDER BY name";
        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":maca_id", $maca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }
    /**
     * Function Consultar malla segun la carrera del estudiante autenticado.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarMallaEstudiante($per_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT 	
                    mac.maca_id as idmalla,
                    mac.maca_codigo as codmalla        
                FROM " . $con->dbname . ".estudiante est
                INNER JOIN " . $con->dbname . ".estudiante_carrera_programa ecp  ON ecp.est_id = est.est_id
                INNER JOIN " . $con->dbname . ".malla_unidad_modalidad mum  ON mum.meun_id = ecp.meun_id
                INNER JOIN " . $con->dbname . ".malla_academica mac  ON mac.maca_id = mum.maca_id
                WHERE   est.per_id = :per_id                        
                AND est.est_estado = :estado
                AND est.est_estado_logico = :estado ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":per_id", $per_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
}