<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
///use kartik\money\MaskMoney;
?>
<?php $form = ActiveForm::begin(['id'=>'form_presupuesto','action'=>Url::to(['proyecto-dependencia/agregar-presupuesto'])]); ?>
<div class="row">
	<div class="col-md-6">
		<?php //echo $form->field($model, 'presupuesto_seguridad')->textInput(['maxlength' => true, 'id' => 'presupuesto_seguridad', 'value' => '']) ?>

		<?php echo $form->field($model, 'presupuesto_activo')->textInput(['maxlength' => true, 'id' => 'presupuesto_activo', 'value' => '']) ?>

	</div>
	<div class="col-md-6">
		<?php //echo $form->field($model, 'presupuesto_riesgo')->textInput(['maxlength' => true, 'id' => 'presupuesto_riesgo', 'value' => '']) ?>

		<?php echo $form->field($model, 'presupuesto_gasto')->textInput(['maxlength' => true, 'id' => 'presupuesto_gasto', 'value' => '']) ?>

		<input type="hidden" name="actualizar" id="actualizar">
		<input type="hidden" name="id" value="<?= $id?>">
	</div>
</div>
<div id="info_suma" style="font-size: 16px;font-weight: bold;"></div>
<br>
<?php ActiveForm::end(); ?>
<button class="btn btn-primary btn-lg" onclick="validar();"><?= $model->isNewRecord ? 'Crear' : 'Agregar'?></button>
