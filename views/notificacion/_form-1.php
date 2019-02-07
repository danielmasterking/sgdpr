<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use marqu3s\summernote\Summernote;
use kartik\date\DatePicker;

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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
