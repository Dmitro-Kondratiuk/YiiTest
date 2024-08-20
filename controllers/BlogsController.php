<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\services\BlogService;

class BlogsController extends Controller
{
    private BlogService $BlogService;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function __construct($id, $module, $config = []) {
        $this->BlogService = new BlogService();
        parent::__construct($id, $module, $config);
    }

    public function actionIndex() {

        $query = $this->BlogService->getBlogs();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider'     => $dataProvider,
            'categoryOptions'  => $this->BlogService->getCategoryOptions(),
            'selectedCategory' => $this->BlogService->selectedCategory(),
        ]);
    }


    public function actionCreate() {
        $response = $this->BlogService->createPost();

        if (isset($response['status']) && $response['status'] === 'success') {
            Yii::$app->session->setFlash($response['status'], $response['message']);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model'           => $response['model'],
            'categoryOptions' => $response['categoryOptions'],
        ]);
    }

    public function actionView($id) {
        $post = $this->BlogService->postFindOne($id);

        if ($post === null) {
            Yii::$app->session->setFlash('error', 'The requested blog post does not exist.');

            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $post,
        ]);
    }


    public function actionUpdate($id) {
        $response = $this->BlogService->updatePost($id);
        if (isset($response['status']) && $response['status'] === 'success') {
            Yii::$app->session->setFlash($response['status'], $response['message']);

            return $this->redirect(['view', 'id' => $response['post_id']]);
        }

        return $this->render('update', [
            'model'           => $response['post'],
            'categoryOptions' => $response['categoryOptions'],
        ]);
    }

    public function actionDelete($id) {
        $this->BlogService->deletePost($id);

        Yii::$app->session->setFlash('success', 'Blog deleted successfully.');

        return $this->redirect(['index']);
    }
}