<?php

namespace app\services;

use app\models\Category;
use app\models\Post;
use Yii;

class CategoryService
{
    public static function createCategory(): array {
        $response = [];

        $model = new Category();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $response['status']  = 'success';
                $response['message'] = 'Category created successfully';
            }
        }
        else {
            $response['model'] = $model;
        }

        return $response;
    }

    public function categoryFindOne($id): Category {
        return Category::findOne($id);
    }

    public function updateCategory($id): array {
        $response = [];

        $category = $this->categoryFindOne($id);
        if ($category->load(Yii::$app->request->post())) {
            if ($category->save()) {
                $response['status']  = 'success';
                $response['message'] = 'Category updated successfully';
                $response['category_id'] = $category->id;
            }
        }
        else {
            $response['model'] = $category;
        }

        return $response;
    }
    public function deletePost($id) {
        return $this->categoryFindOne($id)->delete();
    }
}