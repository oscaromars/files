<?php

namespace app\modules\gfinanciero\models;

use Yii;
use yii\data\ArrayDataProvider;
use app\modules\gfinanciero\Module as financiero;

/**
 * This is the model class for table "VSDirectorio".
 *
 * @property int $IdDirectorio
 * @property int $emp_id
 * @property string|null $TipoDocumento
 * @property string|null $Descripcion
 * @property string|null $Ruta
 * @property int|null $UsuarioCreacion
 * @property string|null $FechaCreacion
 * @property string|null $Estado
 *
 * @property Empresa $emp
 */
class TipoEdoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'VSDirectorio';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_edoc');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['emp_id'], 'required'],
            [['emp_id', 'UsuarioCreacion'], 'integer'],
            [['FechaCreacion'], 'safe'],
            [['TipoDocumento'], 'string', 'max' => 3],
            [['Descripcion', 'Ruta'], 'string', 'max' => 100],
            [['Estado'], 'string', 'max' => 1],
            [['emp_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresa::className(), 'targetAttribute' => ['emp_id' => 'emp_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IdDirectorio' => 'Id Directorio',
            'emp_id' => 'Emp ID',
            'TipoDocumento' => 'Tipo Documento',
            'Descripcion' => 'Descripcion',
            'Ruta' => 'Ruta',
            'UsuarioCreacion' => 'Usuario Creacion',
            'FechaCreacion' => 'Fecha Creacion',
            'Estado' => 'Estado',
        ];
    }

    /**
     * Gets query for [[Emp]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmp()
    {
        return $this->hasOne(Empresa::className(), ['emp_id' => 'emp_id']);
    }
    
     /**
     * Get List TipoEdoc
     *
     * @return void
     */
    public static function getListEdoc() {
        $con = \Yii::$app->db_edoc;
        $sql = "SELECT 
                    IdDirectorio as id,
                    Descripcion as name
                FROM " . $con->dbname . ".VSDirectorio
                WHERE Estado=1 ";
        $comando = $con->createCommand($sql);

        return $comando->queryAll();
    }
}
