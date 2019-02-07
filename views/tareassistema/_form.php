<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TareasSistema */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tareas-sistema-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

    <?php 

    echo $form->field($model, 'fecha')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Fecha','value'=>date('Y-m-d')],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true,
            'autoclose'=>true
        ]
    ]);

    ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'P' => 'En proceso', 'T' => 'Terminado', ], ['prompt' => '']) ?>


    <?= $form->field($model, 'descripcion')->widget(Summernote::className(), [
			    'clientOptions' => [
			       
			    ]
			]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
