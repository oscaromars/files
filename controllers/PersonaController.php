<?php

namespace app\controllers;

class PersonaController extends \app\components\CController {

    public function actionIndex() {
        return $this->render('index');
    }

}
