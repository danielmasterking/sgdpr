<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use marqu3s\summernote\Summernote;
use kartik\widgets\Select2;
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

    <?php  
      if($model->isNewRecord){
    ?>
      <div class="row">
        <div class="col-md-12">
          <!-- ****************************************************** -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Color</b></h3>
            </div>
            <div class="box-body">
              <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
                <ul class="fc-color-picker" id="color-chooser">
                  <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                  <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
                </ul>
              </div>
              <!-- /btn-group -->
              <div class="input-group">
                <input readonly="" id="new-event" type="text" class="form-control" placeholder="#Color" name="color_evento">

                <div class="input-group-btn">
                  <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Color</button>
                </div>
                <!-- /btn-group -->
              </div>
              <!-- /input-group -->
            </div>
          </div>
          <!-- ****************************************************** -->
        </div>
      </div>
      <?php } ?> 
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
                 
                <?= $form->field($model, 'encargado')->widget(Select2::classname(), [     
                   'data' => $list_usuarios,
                    'options' => [
                    'id' => 'encargado',
                    'placeholder' => 'Encargado',
                                                
                    ],
                
                  ])
                ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord?'Crear':'Actualizar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-form --> 