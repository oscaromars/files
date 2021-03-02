<?php

namespace app\modules\academico\models;

use Yii;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "promocion_programa".
 *
 * @property int $ppro_id
 * @property string $ppro_anio
 * @property string $ppro_mes
 * @property string $ppro_codigo
 * @property int $uaca_id
 * @property int $mod_id
 * @property int $eaca_id
 * @property int $ppro_num_paralelo
 * @property int $ppro_cupo
 * @property int $ppro_usuario_ingresa
 * @property string $ppro_estado
 * @property string $ppro_fecha_creacion
 * @property int $ppro_usuario_modifica
 * @property string $ppro_fecha_modificacion
 * @property string $ppro_estado_logico
 *
 * @property MatriculacionProgramaInscrito[] $matriculacionProgramaInscritos
 * @property ParaleloPromocionPrograma[] $paraleloPromocionProgramas
 * @property UnidadAcademica $uaca
 * @property Modalidad $mod
 * @property EstudioAcademico $eaca
 */
class PromocionPrograma extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'promocion_programa';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb() {
        return Yii::$app->get('db_academico');
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['ppro_anio', 'ppro_mes', 'ppro_codigo', 'uaca_id', 'mod_id', 'eaca_id', 'ppro_num_paralelo', 'ppro_cupo', 'ppro_estado', 'ppro_estado_logico'], 'required'],
            [['uaca_id', 'mod_id', 'eaca_id', 'ppro_num_paralelo', 'ppro_cupo', 'ppro_usuario_ingresa', 'ppro_usuario_modifica'], 'integer'],
            [['ppro_fecha_creacion', 'ppro_fecha_modificacion'], 'safe'],
            [['ppro_anio'], 'string', 'max' => 4],
            [['ppro_mes'], 'string', 'max' => 2],
            [['ppro_codigo'], 'string', 'max' => 20],
            [['ppro_estado', 'ppro_estado_logico'], 'string', 'max' => 1],
            [['uaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadAcademica::className(), 'targetAttribute' => ['uaca_id' => 'uaca_id']],
            [['mod_id'], 'exist', 'skipOnError' => true, 'targetClass' => Modalidad::className(), 'targetAttribute' => ['mod_id' => 'mod_id']],
            [['eaca_id'], 'exist', 'skipOnError' => true, 'targetClass' => EstudioAcademico::className(), 'targetAttribute' => ['eaca_id' => 'eaca_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'ppro_id' => 'Ppro ID',
            'ppro_anio' => 'Ppro Anio',
            'ppro_mes' => 'Ppro Mes',
            'ppro_codigo' => 'Ppro Codigo',
            'uaca_id' => 'Uaca ID',
            'mod_id' => 'Mod ID',
            'eaca_id' => 'Eaca ID',
            'ppro_num_paralelo' => 'Ppro Num Paralelo',
            'ppro_cupo' => 'Ppro Cupo',
            'ppro_usuario_ingresa' => 'Ppro Usuario Ingresa',
            'ppro_estado' => 'Ppro Estado',
            'ppro_fecha_creacion' => 'Ppro Fecha Creacion',
            'ppro_usuario_modifica' => 'Ppro Usuario Modifica',
            'ppro_fecha_modificacion' => 'Ppro Fecha Modificacion',
            'ppro_estado_logico' => 'Ppro Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatriculacionProgramaInscritos() {
        return $this->hasMany(MatriculacionProgramaInscrito::className(), ['ppro_id' => 'ppro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParaleloPromocionProgramas() {
        return $this->hasMany(ParaleloPromocionPrograma::className(), ['ppro_id' => 'ppro_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUaca() {
        return $this->hasOne(UnidadAcademica::className(), ['uaca_id' => 'uaca_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMod() {
        return $this->hasOne(Modalidad::className(), ['mod_id' => 'mod_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEaca() {
        return $this->hasOne(EstudioAcademico::className(), ['eaca_id' => 'eaca_id']);
    }

    /**
     * Function getPromocion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (información del aspirante)
     */
    public static function getPromocion($arrFiltro = array(), $onlyData = false) {
        $con = \Yii::$app->db;
        $con1 = \Yii::$app->db_academico;
        $estado = 1;
        $columnsAdd = "";

        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $str_search .= "ppr.ppro_codigo like :search AND ";
            }
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $str_search .= "ppr.uaca_id = :unidad AND ";
            }
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $str_search .= "ppr.mod_id = :modalidad AND ";
            }
            if ($arrFiltro['programa'] != "" && $arrFiltro['programa'] > 0) {
                $str_search .= "ppr.eaca_id = :programa AND ";
            }
        }
        if ($onlyData == false) {
            $columnsAdd = "ppr.ppro_id as id, ";
        }
        $sql = " SELECT ";
        $sql .= $columnsAdd;
        $sql .= " ppr.ppro_codigo as codigo,
                    ppr.ppro_anio as anio,
                    CASE ppro_mes
                        WHEN  1 THEN 'Enero'
                        WHEN  2 THEN 'Febrero'
                        WHEN  3 THEN 'Marzo'
                        WHEN  4 THEN 'Abril'
                        WHEN  5 THEN 'Mayo'
                        WHEN  6 THEN 'Junio'
                        WHEN  7 THEN 'Julio'
                        WHEN  8 THEN 'Agosto'
                        WHEN  9 THEN 'Septiembre'
                        WHEN  10 THEN 'Octubre'
                        WHEN  11 THEN 'Noviembre'
                        WHEN  12 THEN 'Diciembre' 
                        ELSE ' ' END as mes,
                    uaca.uaca_nombre as unidad,
                    moda.mod_nombre as modalidad,
                    ea.eaca_nombre as programa,
                    ppr.ppro_num_paralelo as paralelo
                FROM " . $con1->dbname . ".promocion_programa ppr
                    INNER JOIN " . $con1->dbname . ".unidad_academica uaca on uaca.uaca_id = ppr.uaca_id
                    INNER JOIN " . $con1->dbname . ".modalidad moda on moda.mod_id = ppr.mod_id
                    INNER JOIN " . $con1->dbname . ".estudio_academico ea on ea.eaca_id = ppr.eaca_id
                WHERE 
                $str_search
                ppr.ppro_estado = :estado AND
                ppr.ppro_estado_logico = :estado AND                 
                uaca.uaca_estado = :estado AND
                uaca.uaca_estado_logico = :estado AND                
                moda.mod_estado = :estado AND
                moda.mod_estado_logico = :estado AND                
                ea.eaca_estado = :estado AND
                ea.eaca_estado_logico = :estado
                ORDER BY ppro_fecha_creacion DESC ";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        if (isset($arrFiltro) && count($arrFiltro) > 0) {
            if ($arrFiltro['search'] != "") {
                $search_cond = "%" . $arrFiltro["search"] . "%";
                $comando->bindParam(":search", $search_cond, \PDO::PARAM_STR);
            }
            $unidad = $arrFiltro["unidad"];
            if ($arrFiltro['unidad'] != "" && $arrFiltro['unidad'] > 0) {
                $comando->bindParam(":unidad", $unidad, \PDO::PARAM_INT);
            }
            $modalidad = $arrFiltro["modalidad"];
            if ($arrFiltro['modalidad'] != "" && $arrFiltro['modalidad'] > 0) {
                $comando->bindParam(":modalidad", $modalidad, \PDO::PARAM_INT);
            }
            $programa = $arrFiltro["programa"];
            if ($arrFiltro['programa'] != "" && $arrFiltro['programa'] > 0) {
                $comando->bindParam(":programa", $programa, \PDO::PARAM_INT);
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
                    'ppr.ppro_codigo',
                    ' ppr.ppro_anio',
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
     * Function consulta las promociones por programa. 
     * @author Grace Viteri <analistadesarrollo01@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPromocionxPrograma($eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT ppro_id id, ppro_codigo name                 
                FROM 
                " . $con->dbname . ".promocion_programa  pp 
                WHERE eaca_id = :eaca_id AND
                   pp.ppro_estado = :estado AND
                   pp.ppro_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryAll();
        return $resultData;
    }

    /**
     * Function consultar si existe ya el programa con los mismo datos antes de guardar
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el id de promocion).
     */
    public function consultarPromocion($ppro_anio, $ppro_mes, $uaca_id, $mod_id, $eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT ppro_id 
                   FROM " . $con->dbname . ".promocion_programa 
                   WHERE ppro_anio = :ppro_anio 
                        AND ppro_mes = :ppro_mes 
                        AND uaca_id = :uaca_id
                        AND mod_id = :mod_id 
                        AND eaca_id = :eaca_id 
                        AND ppro_estado = :estado
                        AND ppro_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ppro_anio", $ppro_anio, \PDO::PARAM_INT);
        $comando->bindParam(":ppro_mes", $ppro_mes, \PDO::PARAM_INT);
        $comando->bindParam(":uaca_id", $uaca_id, \PDO::PARAM_INT);
        $comando->bindParam(":mod_id", $mod_id, \PDO::PARAM_INT);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function guardar promocion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código de promocion).
     */
    public function insertarPromocion($ppro_anio, $ppro_mes, $ppro_codigo, $uaca_id, $mod_id, $eaca_id, $ppro_num_paralelo, $ppro_cupo, $ppro_usuario_ingresa, $ppro_fecha_creacion) {

        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "ppro_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", ppro_estado";
        $bsol_sql .= ", 1";
        if (isset($ppro_anio)) {
            $param_sql .= ", ppro_anio";
            $bsol_sql .= ", :ppro_anio";
        }

        if (isset($ppro_mes)) {
            $param_sql .= ", ppro_mes";
            $bsol_sql .= ", :ppro_mes";
        }

        if (isset($ppro_codigo)) {
            $param_sql .= ", ppro_codigo";
            $bsol_sql .= ", :ppro_codigo";
        }

        if (isset($uaca_id)) {
            $param_sql .= ", uaca_id";
            $bsol_sql .= ", :uaca_id";
        }

        if (isset($mod_id)) {
            $param_sql .= ", mod_id";
            $bsol_sql .= ", :mod_id";
        }
        if (isset($eaca_id)) {
            $param_sql .= ", eaca_id";
            $bsol_sql .= ", :eaca_id";
        }
        if (isset($ppro_num_paralelo)) {
            $param_sql .= ", ppro_num_paralelo";
            $bsol_sql .= ", :ppro_num_paralelo";
        }
        if (isset($ppro_cupo)) {
            $param_sql .= ", ppro_cupo";
            $bsol_sql .= ", :ppro_cupo";
        }
        if (isset($ppro_usuario_ingresa)) {
            $param_sql .= ", ppro_usuario_ingresa";
            $bsol_sql .= ", :ppro_usuario_ingresa";
        }
        if (isset($ppro_fecha_creacion)) {
            $param_sql .= ", ppro_fecha_creacion";
            $bsol_sql .= ", :ppro_fecha_creacion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".promocion_programa ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($ppro_anio))
                $comando->bindParam(':ppro_anio', $ppro_anio, \PDO::PARAM_INT);

            if (isset($ppro_mes))
                $comando->bindParam(':ppro_mes', $ppro_mes, \PDO::PARAM_INT);

            if (isset($ppro_codigo))
                $comando->bindParam(':ppro_codigo', $ppro_codigo, \PDO::PARAM_STR);

            if (isset($uaca_id))
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);

            if (isset($mod_id))
                $comando->bindParam(':mod_id', $mod_id, \PDO::PARAM_INT);

            if (isset($uaca_id))
                $comando->bindParam(':uaca_id', $uaca_id, \PDO::PARAM_INT);

            if (isset($eaca_id))
                $comando->bindParam(':eaca_id', $eaca_id, \PDO::PARAM_INT);

            if (isset($ppro_num_paralelo))
                $comando->bindParam(':ppro_num_paralelo', $ppro_num_paralelo, \PDO::PARAM_INT);

            if (isset($ppro_cupo))
                $comando->bindParam(':ppro_cupo', $ppro_cupo, \PDO::PARAM_INT);

            if (isset($ppro_usuario_ingresa))
                $comando->bindParam(':ppro_usuario_ingresa', $ppro_usuario_ingresa, \PDO::PARAM_INT);

            if (isset($ppro_fecha_creacion))
                $comando->bindParam(':ppro_fecha_creacion', $ppro_fecha_creacion, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.promocion_programa');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function guardar paralelo
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código de paralelo).
     */
    public function insertarParalelo($ppro_id, $pppr_cupo, $pppr_cupo_actual, $pppr_descripcion, $pppr_usuario_ingresa, $pppr_fecha_creacion) {

        $con = \Yii::$app->db_academico;
        $trans = $con->getTransaction(); // se obtiene la transacción actual
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }
        $param_sql = "pppr_estado_logico";
        $bsol_sql = "1";

        $param_sql .= ", pppr_estado";
        $bsol_sql .= ", 1";
        if (isset($ppro_id)) {
            $param_sql .= ", ppro_id";
            $bsol_sql .= ", :ppro_id";
        }
        if (isset($pppr_cupo)) {
            $param_sql .= ", pppr_cupo";
            $bsol_sql .= ", :pppr_cupo";
        }
        if (isset($pppr_cupo_actual)) {
            $param_sql .= ", pppr_cupo_actual";
            $bsol_sql .= ", :pppr_cupo_actual";
        }
        if (isset($pppr_descripcion)) {
            $param_sql .= ", pppr_descripcion";
            $bsol_sql .= ", :pppr_descripcion";
        }
        if (isset($pppr_usuario_ingresa)) {
            $param_sql .= ", pppr_usuario_ingresa";
            $bsol_sql .= ", :pppr_usuario_ingresa";
        }
        if (isset($pppr_fecha_creacion)) {
            $param_sql .= ", pppr_fecha_creacion";
            $bsol_sql .= ", :pppr_fecha_creacion";
        }

        try {
            $sql = "INSERT INTO " . $con->dbname . ".paralelo_promocion_programa ($param_sql) VALUES($bsol_sql)";
            $comando = $con->createCommand($sql);

            if (isset($ppro_id))
                $comando->bindParam(':ppro_id', $ppro_id, \PDO::PARAM_INT);

            if (isset($pppr_cupo))
                $comando->bindParam(':pppr_cupo', $pppr_cupo, \PDO::PARAM_INT);

            if (isset($pppr_cupo_actual))
                $comando->bindParam(':pppr_cupo_actual', $pppr_cupo_actual, \PDO::PARAM_INT);

            if (isset($pppr_descripcion))
                $comando->bindParam(':pppr_descripcion', $pppr_descripcion, \PDO::PARAM_STR);

            if (isset($pppr_usuario_ingresa))
                $comando->bindParam(':pppr_usuario_ingresa', $pppr_usuario_ingresa, \PDO::PARAM_INT);

            if (isset($pppr_fecha_creacion))
                $comando->bindParam(':pppr_fecha_creacion', $pppr_fecha_creacion, \PDO::PARAM_STR);

            $result = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $con->getLastInsertID($con->dbname . '.paralelo_promocion_programa');
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }

    /**
     * Function Consultar datos de promocion programa.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @property       
     * @return  
     */
    public function consultarPromocionxid($ppro_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT 	ppro.ppro_id as ppro_id,
                        ppro.ppro_codigo as ppro_codigo,
                        ppro.ppro_anio as ppro_anio,
                        ppro.ppro_mes as ppro_mes,
                        ppro.uaca_id as uaca_id,
                        ppro.mod_id as mod_id,
                        ppro.eaca_id as eaca_id,
                        ppro.ppro_num_paralelo as ppro_num_paralelo,
                        ppro.ppro_cupo as ppro_cupo,
                        uaca.uaca_nombre as uaca_nombre,
                        moda.mod_nombre as mod_nombre,
                        eaca.eaca_nombre as eaca_nombre,
                        CASE ppro_mes
                            WHEN  1 THEN 'Enero'
                            WHEN  2 THEN 'Febrero'
                            WHEN  3 THEN 'Marzo'
                            WHEN  4 THEN 'Abril'
                            WHEN  5 THEN 'Mayo'
                            WHEN  6 THEN 'Junio'
                            WHEN  7 THEN 'Julio'
                            WHEN  8 THEN 'Agosto'
                            WHEN  9 THEN 'Septiembre'
                            WHEN  10 THEN 'Octubre'
                            WHEN  11 THEN 'Noviembre'
                            WHEN  12 THEN 'Diciembre' 
                        ELSE ' ' END as nombre_mes
                FROM " . $con->dbname . ".promocion_programa ppro
                    INNER JOIN " . $con->dbname . ".unidad_academica uaca ON uaca.uaca_id =  ppro.uaca_id
                    INNER JOIN " . $con->dbname . ".modalidad moda ON moda.mod_id =  ppro.mod_id
                    INNER JOIN " . $con->dbname . ".estudio_academico eaca ON eaca.eaca_id =  ppro.eaca_id
                WHERE   ppro.ppro_id = :ppro_id
                        AND ppro.ppro_estado = :estado
                        AND ppro.ppro_estado_logico = :estado
                        AND uaca.uaca_estado = :estado
                        AND uaca.uaca_estado_logico = :estado        
                        AND moda.mod_estado = :estado
                        AND moda.mod_estado_logico = :estado        
                        AND eaca.eaca_estado = :estado
                        AND eaca.eaca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }

    /**
     * Function actualizar promocion
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @property integer $ppro_id
     * @return  
     */
    public function actualizarPromocion($con, $id, $parameters, $keys, $name_table) {
        $trans = $con->getTransaction();
        $params_sql = "";
        for ($i = 0; $i < (count($parameters) - 1); $i++) {
            if (isset($parameters[$i])) {
                $params_sql .= $keys[$i] . " = '" . $parameters[$i] . "',";
            }
        }
        $params_sql .= $keys[count($parameters) - 1] . " = '" . $parameters[count($parameters) - 1] . "'";
        try {
            $sql = "UPDATE " . $con->dbname . '.' . $name_table .
                    " SET $params_sql" .
                    " WHERE ppro_id=$id";
            $comando = $con->createCommand($sql);
            $result = $comando->execute();
            if ($trans !== null) {
                return true;
            } else {
                $transaction->commit();
                return true;
            }
        } catch (Exception $ex) {
            if ($trans !== null) {
                $trans->rollback();
            }
            return 0;
        }
    }

    /**
     * Function modifica numeros de paralelo en promocion.
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;     *          
     * @param
     * @return
     */
    public function actualizarPromocionparalelo($ppro_id, $ppro_num_paralelo, $ppro_usuario_modifica) {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if ($trans !== null) {
            $trans = null; // si existe la transacción entonces no se crea una
        } else {
            $trans = $con->beginTransaction(); // si no existe la transacción entonces se crea una
        }

        try {
            $comando = $con->createCommand
                    ("UPDATE " . $con->dbname . ".promocion_programa		       
                      SET ppro_num_paralelo = :ppro_num_paralelo - 1,                       
                          ppro_fecha_modificacion = :ppro_fecha_modificacion,
                          ppro_usuario_modifica = :ppro_usuario_modifica
                      WHERE ppro_id = :ppro_id AND                        
                            ppro_estado = :estado AND
                            ppro_estado_logico = :estado");

            $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
            $comando->bindParam(":ppro_fecha_modificacion", $fecha_modificacion, \PDO::PARAM_STR);
            $comando->bindParam(":ppro_usuario_modifica", $ppro_usuario_modifica, \PDO::PARAM_INT);
            $comando->bindParam(":ppro_id", $ppro_id, \PDO::PARAM_INT);
            $comando->bindParam(":ppro_num_paralelo", $ppro_num_paralelo, \PDO::PARAM_INT);
            $response = $comando->execute();
            if ($trans !== null)
                $trans->commit();
            return $response;
        } catch (Exception $ex) {
            if ($trans !== null)
                $trans->rollback();
            return FALSE;
        }
    }
        /**
     * Function consultar el codigo de estudio academico
     * @author  Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>
     * @param   
     * @return  $resultData (Retornar el código de estudio academico).
     */
    public function consultarCodigoestudioaca($eaca_id) {
        $con = \Yii::$app->db_academico;
        $estado = 1;

        $sql = "SELECT eaca_codigo 
                   FROM " . $con->dbname . ".estudio_academico 
                   WHERE eaca_id = :eaca_id 
                        AND eaca_estado = :estado
                        AND eaca_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);
        $comando->bindParam(":eaca_id", $eaca_id, \PDO::PARAM_INT);
        $resultData = $comando->queryOne();
        return $resultData;
    }
    
    /**
     * Function consulta las promociones por programa. 
     * @author Giovanni Vergara <analistadesarrollo02@uteg.edu.ec>;
     * @param
     * @return
     */
    public function consultarPromocionxProgramagen() {
        $con = \Yii::$app->db_academico;
        $estado = 1;
        $sql = "SELECT ppro_id id, ppro_codigo name                 
                FROM 
                " . $con->dbname . ".promocion_programa  pp 
                WHERE 
                   pp.ppro_estado = :estado AND
                   pp.ppro_estado_logico = :estado";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_STR);        
        $resultData = $comando->queryAll();
        return $resultData;
    }

}
