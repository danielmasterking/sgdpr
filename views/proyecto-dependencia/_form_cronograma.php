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

    <?php  
      if(!$model->isNewRecord){
    ?>
    <?= Html::a('<i class="fa fa-arrow-left"></i> Volver',Yii::$app->request->baseUrl.'/proyecto-dependencia/view?id='.$id, ['class'=>'btn btn-primary']) ?>
    <h1>Editar Cronograma</h1>
    <?php } ?>
    <?php $form = ActiveForm::begin([
        'action'=>$model->isNewRecord?Url::toRoute(['agregarcronograma', 'id' =>$id]):''
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
            <?= Html::submitButton($model->isNewRecord?'Crear':'Actualizar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-form --> 