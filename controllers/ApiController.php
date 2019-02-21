<?php
namespace app\controllers;

use yii\rest\ActiveController;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\CentroCosto';

    public function actionIndex(){


        return $this->modelClass->find()->all();
    }

    public function actionView($id){
        
        
        return $this->modelClass->findOne($id);
    }

}