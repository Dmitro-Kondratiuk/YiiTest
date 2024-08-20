<?php

namespace app\controllers;

use app\services\CategoryService;
use Yii;
use app\models\Category;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;

class CategoryController extends Controller
{
    private CategoryService $CategoryService;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['create', 'update', 'delete', 'index'],
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
        $this->CategoryService = new CategoryService();
        parent::__construct($id, $module, $config);
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query'      => Category::find(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate() {
        $response = $this->CategoryService->createCategory();
        if (isset($response['status']) && $response['status'] == 'success') {
            Yii::$app->session->setFlash($response['status'], $response['message']);

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $response['model'],
        ]);

    }

    /**
     * Displays a single Category model.
     *
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        $category = $this->CategoryService->categoryFindOne($id);

        if ($category === null) {
            Yii::$app->session->setFlash('error', 'The requested blog post does not exist.');

            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $category,
        ]);
    }


    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $response = $this->CategoryService->updateCategory($id);

        if (isset($response['status']) && $response['status'] == 'success') {
            Yii::$app->session->setFlash('success', 'Category updated successfully.');

            return $this->redirect(['view', 'id' => $response['category_id']]);
        }


        return $this->render('update', [
            'model' => $response['model'],
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->CategoryService->deletePost($id);

        Yii::$app->session->setFlash('success', 'Category deleted successfully.');

        return $this->redirect(['index']);
    }


}
