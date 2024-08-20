<?php

namespace app\controllers;

use app\models\NginxLog;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
     public function actionIndex(){
         $dataProvider = new ActiveDataProvider([
             'query' => NginxLog::find(),
             'pagination' => [
                 'pageSize' => 10,
             ],
         ]);

         return $this->render('index', [
             'dataProvider' => $dataProvider,
         ]);
     }
}