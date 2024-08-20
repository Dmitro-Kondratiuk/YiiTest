<?php

namespace app\services;

use app\models\Category;
use app\models\Post;
use Yii;

class BlogService
{
    public function getBlogs() {
        $query = Post::find();

        $selectedCategory = $this->selectedCategory();
        if ($selectedCategory) {
            $query->andWhere(['category_id' => $selectedCategory]);
        }

        return $query;
    }

    public function selectedCategory() {
        return Yii::$app->request->get('category_id');
    }

    public function getCategoryOptions(): array {
        $categories = Category::find()->all();

        return \yii\helpers\ArrayHelper::map($categories, 'id', 'name');
    }

    public function updatePost($id) {
        $response        = [];
        $post            = $this->postFindOne($id);
        $categoryOptions = $this->getCategoryOptions();
        if ($post->load(Yii::$app->request->post())) {
            if ($post->save()) {
                $response['message'] = 'Post updated successfully.';
                $response['status']  = 'success';
                $response['post_id'] = $post->id;
            }
        }
        else {
            $response['post']            = $post;
            $response['categoryOptions'] = $categoryOptions;
        }

        return $response;
    }

    public function postFindOne($id): Post {
        return Post::findOne($id);
    }

    public function createPost(): array {
        $response        = [];
        $model           = new Post();
        $categoryOptions = $this->getCategoryOptions();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $response['message'] = 'Post created successfully.';
                $response['status']  = 'success';
            }
        }
        else {
            $response['model']           = $model;
            $response['categoryOptions'] = $categoryOptions;
        }

        return $response;
    }

    public function deletePost($id) {
        return $this->postFindOne($id)->delete();
    }

}