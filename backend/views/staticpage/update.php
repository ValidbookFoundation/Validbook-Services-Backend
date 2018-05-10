<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\backend\models\Staticpage */

$this->title = 'Update Staticpage: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Staticpages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staticpage-update">

    <h1><?= $model->title ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
