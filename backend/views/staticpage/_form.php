<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;
use backend\models\WidgetPage;


/* @var $this yii\web\View */
/* @var $model app\backend\models\Staticpage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staticpage-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php


    if(!$model->widgets){

        echo $form->field($model, 'story')->widget(Widget::className(), [
            'settings' => [
                'lang' => 'en',
                'minHeight' => 200,
                'plugins' => [
                    'clips',
                    'fullscreen'
                ]
            ]
        ]);

    }else{
    ?>

        <?php foreach ($model->widgets as $key => $widget):?>
            <?= $form->field($widget, 'story')->textarea([
                'id' => "WidgetPage{$key}",
                'name' => "WidgetPage[$key]",
            ])->label($widget->title); ?>
        <?php endforeach;?>

    <?php } ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'meta_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
