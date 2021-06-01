<?php

namespace app\modules\admision\models;

use Yii;

/**
 * This is the model class for table "documento_adjuntar".
 *
 * @property integer $dadj_id
 * @property string $dadj_nombre
 * @property string $dadj_descripcion
 * @property string $dadj_estado
 * @property string $dadj_fecha_creacion
 * @property string $dadj_fecha_modificacion
 * @property string $dadj_estado_logico
 *
 * @property DocNintTciudadano[] $docNintTciudadanos
 * @property SolicitudinsDocumento[] $solicitudinsDocumentos
 */
class DocumentoAdjuntar extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        //return 'documento_adjuntar';
        return \Yii::$app->db_captacion->dbname . '.documento_adjuntar';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dadj_nombre', 'dadj_descripcion', 'dadj_estado', 'dadj_estado_logico'], 'required'],
            [['dadj_fecha_creacion', 'dadj_fecha_modificacion'], 'safe'],
            [['dadj_nombre'], 'string', 'max' => 300],
            [['dadj_descripcion'], 'string', 'max' => 500],
            [['dadj_estado', 'dadj_estado_logico'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dadj_id' => 'Dadj ID',
            'dadj_nombre' => 'Dadj Nombre',
            'dadj_descripcion' => 'Dadj Descripcion',
            'dadj_estado' => 'Dadj Estado',
            'dadj_fecha_creacion' => 'Dadj Fecha Creacion',
            'dadj_fecha_modificacion' => 'Dadj Fecha Modificacion',
            'dadj_estado_logico' => 'Dadj Estado Logico',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocNintTciudadanos() {
        return $this->hasMany(DocNintTciudadano::className(), ['dadj_id' => 'dadj_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitudinsDocumentos() {
        return $this->hasMany(SolicitudinsDocumento::className(), ['dadj_id' => 'dadj_id']);
    }

    /**
     * Function desactivarDocumentosxSolicitud marca los documentos con estado = 0 
     * @author  Developer Uteg <developer@uteg.edu.ec>
     * @param   int     $sins_id        Id de la solicitud
     * @return  $resultData (Retorna true si se realizo la operacion o false si fue error).
     */
    public static function desactivarDocumentosxSolicitud($sins_id) {
        $con = \Yii::$app->db_captacion;
        $estado = 0;

        $sql = "UPDATE " . \Yii::$app->db_captacion->dbname . ".solicitudins_documento 
                SET sdoc_estado = :estado 
                WHERE sins_id = :id and dadj_id <> 7;";

        $comando = $con->createCommand($sql);
        $comando->bindParam(":id", $sins_id, \PDO::PARAM_INT);
        $comando->bindParam(":estado", $estado, \PDO::PARAM_INT);
        $resultData = $comando->execute();
        return $resultData;
    }

    /**
     * Function addLabelTimeDocumentos renombra el documento agregando una varible de tiempo 
     * @author  Developer Uteg <developer@uteg.edu.ec>
     * @param   int     $sins_id        Id de la solicitud
     * @param   string  $file           Uri del Archivo a modificar
     * @param   int     $timeSt         Parametro a agregar al nombre del archivo
     * @return  $newFile | FALSE (Retorna el nombre del nuevo archivo o false si fue error).
     */
    public static function addLabelTimeDocumentos($sins_id, $file, $timeSt) {
        $arrIm = explode(".", basename($file));
        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
        $baseFile = Yii::$app->basePath;
        $search = ".$typeFile";
        $replace = "_$timeSt" . ".$typeFile";
        $newFile = str_replace($search, $replace, $file);
        
        if (file_exists($baseFile . $file)) {
            if (rename($baseFile . $file, $baseFile . $newFile)) {
                return $newFile;
            }
        }else{
            return $newFile;
        }

        return FALSE;
    }

}
