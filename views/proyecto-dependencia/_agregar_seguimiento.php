<style type="text/css">
    #avance{
        width: 70px;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\datecontrol\DateControl;
use marqu3s\summernote\Summernote;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $model app\models\ProyectoSeguimiento */
/* @var $form ActiveForm */
$this->title = 'Crear Seguimiento';

?>
<div class="site-proyecto_seguimiento">
    <?= Html::a('<i class="fa fa-arrow-left"></i>', ['view','id'=>$id], ['class' => 'btn btn-danger']) ?>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([

        'options'=>['enctype'=>'multipart/form-data'] // important


    ]); ?>

        
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'id_sistema')->widget(Select2::classname(), [
                   
                   'data' => $sistemas,
                    'options' => [
                    'id' => 'sistema',
                    'placeholder' => 'Sistemas',
                                                
                    ],
                
                  ])
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'fecha')->widget(DateControl::classname(), [
                      'autoWidget'=>true,
                     'displayFormat' => 'php:Y-m-d',
                     'saveFormat' => 'php:Y-m-d',
                      'type'=>DateControl::FORMAT_DATE,
                      'disabled'=>'true'
         
               ]);?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'id_tipo_reporte')->widget(Select2::classname(), [
                   
                   'data' => $list_reportes,
                    'options' => [
                    'id' => 'tipo_reportes',
                    'placeholder' => 'Tipo de reporte',
                                                
                    ],
                
                  ])
                ?>
            </div>
        </div>

      
        
        <div class="row">
            <div class="col-md-12">
                <?= $form->field($model, 'reporte')->widget(Summernote::className(), [
                    'clientOptions' => [
                       
                    ]
                ]); ?>
            </div>
        </div>

        <div class="row">
           
            <div class="col-md-4">
                <?//= $form->field($model, 'avance') ?>

                <?php
                    echo $form->field($model, 'id_provedor')->dropDownList($provedores,['prompt'=>'Select...']); 
                ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'usuario')->textInput([
                'readonly'=>true
                ]) ?>
            </div>

             <div class="col-md-4">
                <?//= $form->field($model, 'avance') ?>

                <?php
                    echo $form->field($model, 'avance')->dropDownList($array_porcentaje,['id'=>'avance']); 
                ?>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <?php 
                    echo $form->field($model, 'image[]')->widget(FileInput::classname(), [
                    'options' => ['multiple'=>true],
                    'pluginOptions'=>['allowedFileExtensions'=>['jpg', 'gif', 'png','jpeg'],
                                       //'maxFileSize' => 5120,
                      ]
                     ]);
                ?>
            </div>
        </div>
        

    
        <div class="form-group">
            <?//= Html::submitButton('Crear', ['class' => 'btn btn-primary']) ?>
             <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-proyecto_seguimiento -->
<script type="text/javascript">
    $('#tipo_reportes').change(function(event) {
        /* Act on the event */
        let reporte=$(this).val();

        if(reporte==6){

            $('#avance').removeAttr('disabled')
        }else{

            $('#avance').attr('disabled', 'disabled');
             $("#avance").val('0')
        }

    });
</script>