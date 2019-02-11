<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use marqu3s\summernote\Summernote;
/* @var $this yii\web\View */
/* @var $model app\models\CronogramaProyecto */
/* @var $form ActiveForm */
?>
<div class="site-form">

    <?php $form = ActiveForm::begin([
        'action'=>Url::toRoute(['agregarcronograma', 'id' =>$id])
    ]); ?>
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'tipo_trabajo') ?>
            </div>
            
        </div>
       
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'descripcion')->widget(Summernote::className(), [
                    'clientOptions' => [
               
                    ]
                ]); ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_inicio')->widget(DateControl::classname(), [
                              'autoWidget'=>true,
                             'displayFormat' => 'php:Y-m-d',
                             'saveFormat' => 'php:Y-m-d',
                              'type'=>DateControl::FORMAT_DATE,
                              //'disabled'=>'true'
                 
                       ]);?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'fecha_fin')->widget(DateControl::classname(), [
                              'autoWidget'=>true,
                             'displayFormat' => 'php:Y-m-d',
                             'saveFormat' => 'php:Y-m-d',
                              'type'=>DateControl::FORMAT_DATE,
                              //'disabled'=>'true'
                 
                       ]);?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                 <?= $form->field($model, 'encargado') ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Crear', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-form --> 