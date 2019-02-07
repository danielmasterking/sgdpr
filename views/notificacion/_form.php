<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\date\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Notificacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notificacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput([]) ?>

    <?php 

    echo $form->field($model, 'fecha_inicio')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Fecha inicio'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autoclose'=>true
        ]
    ]);

    ?>

    <?php 

    echo $form->field($model, 'fecha_final')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Fecha final'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autoclose'=>true
        ]
    ]);

    ?>

    <?php //echo $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'descripcion')->widget(Summernote::className(), [
			    'clientOptions' => [
			       
			    ]
			]); ?>

    <h3>Vista de notificaciones</h3>

    <label>Usuarios asignados</label>

    <?php 
        if(!isset($actualizar)){
         echo Select2::widget([
                        'name' => 'usuarios[]',
                        //'value'=>$value_dep,
                        'data' => $usuarios,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Selecciona usuarios ...', 'multiple' => true,/*'options' =>$array,*/'id'=>'user'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
        }else{

             echo Select2::widget([
                        'name' => 'usuarios[]',
                        'value'=>$usuarios_not,
                        'data' => $usuarios,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Selecciona usuarios ...', 'multiple' => true,/*'options' =>$array,*/'id'=>'user'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
        }

    ?>

    <br>
    <label>Regionales asignadas</label>

    <?php 

        if(!isset($actualizar)){
         echo Select2::widget([
                        'name' => 'zonas[]',
                        //'value'=>$value_dep,
                        'data' => $zonas,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Selecciona zonas ...', 'multiple' => true,/*'options' =>$array,*/'id'=>'zona'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
        }else{
            echo Select2::widget([
                        'name' => 'zonas[]',
                        'value'=>$zona_not,
                        'data' => $zonas,
                        //'size' => Select2::SMALL,
                        'options' => ['placeholder' => 'Selecciona zonas ...', 'multiple' => true,/*'options' =>$array,*/'id'=>'zona'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
        }

    ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
