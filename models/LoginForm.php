<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Usuario;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model {

    public $username;
    public $password;
    public $empresa_id;
    private $_user = false;
    private $_errorSession = false;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username'], 'trim'],
        ];
    }

    public function attributeLabels() {
        return [
            'username' => Yii::t('login', 'Email'),
            'password' => Yii::t('login', 'Password'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t("login", 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */

    /**
     * Function login
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function login($empresa_alias = NULL) {
        if ($this->validate()) {
            $usuario = Usuario::findByUsername($this->username);
            if (isset($usuario)) {
                $status = $usuario->validatePassword($this->password);
                $status_activo = $usuario->usu_estado;
                if ($status_activo == 1) { // si es usuario activo
                    $status=true;
                    if ($status && isset($status)) {
                        //$usuario->init();
                        $emp_id = NULL;
                        if(isset($empresa_alias)){
                            $empresa_model = Empresa::findOne(['emp_alias' => $empresa_alias, 'emp_estado' => 1, 'emp_estado_logico' => 1]);
                            $emp_id = isset($empresa_model->emp_id)?$empresa_model->emp_id:NULL;
                        }
                        $usuario->createSession($emp_id);
                        // agregar link dash session
                        $ws = new \app\webservices\WsEducativa();
                        $arr_out = $ws->autenticar_usuario($this->username, $this->password);
                        if($arr_out["status"] == "OK")
                            Usuario::addVarSession("PB_educativa", $arr_out["data"]->result);
                        else
                            Usuario::addVarSession("PB_educativa", Yii::$app->params['url_educativa']);
                        
                        Yii::$app->user->login($usuario, 0);
                        Yii::$app->user->setIdentity($usuario);
                    } else { // error password
                        $this->setErrorSession(true);
                        Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Incorrect username or password."));
                        $usuario->destroySession();
                        return false;
                    }
                } else { // account disabled
                    $this->setErrorSession(true);
                    Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Incorrect username or password."));
                    $usuario->destroySession();
                    return false;
                }
                return $status;
            } else {
                $this->setErrorSession(true);
                Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Incorrect username or password."));
                return false;
            }
        } else {
            $this->setErrorSession(true);
            Yii::$app->session->setFlash('error', Yii::t("login", "<h4>Error</h4>Incorrect username or password."));
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */

    /**
     * Function getUser
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = Usuario::findByUsername($this->username);
        }
        return $this->_user;
    }

    /**
     * Function getErrorSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function getErrorSession() {
        return $this->_errorSession;
    }

    /**
     * Function setErrorSession
     * @author  Diana Lopez <dlopez@uteg.edu.ec>
     * @param      
     * @return  
     */
    public function setErrorSession($error) {
        $this->_errorSession = $error;
    }

}
