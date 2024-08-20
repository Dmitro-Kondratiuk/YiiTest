<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Blogs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table class="table table-striped table-bordered">
        <tr>
            <th>ID</th>
            <td><?= Html::encode($model->id) ?></td>
        </tr>
        <tr>
            <th>Title</th>
            <td><?= Html::encode($model->title) ?></td>
        </tr>
        <tr>
            <th>Content</th>
            <td><?= nl2br(Html::encode($model->content)) ?></td>
        </tr>
        <tr>
            <th>Category ID</th>
            <td><?= Html::encode($model->category_id) ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?= Html::encode($model->created_at) ?></td>
        </tr>
    </table>

</div>
