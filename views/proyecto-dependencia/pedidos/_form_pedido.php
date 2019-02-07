<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
Select2::widget([
	'id' => 'xxx',
    'name' => 'xxx',
    'value' => '',
    'options' => ['multiple' => false]
]);
?>
<?php $form = ActiveForm::begin(['id'=>'form_create','action'=>Url::to(['proyecto-dependencia/pedidos-create?id='.$id.'&ceco='.$ceco.''])]); ?>
	<div class="row">
		<div class="col-md-12">
			<label for="tipo_presupuesto">Elija a que presupuesto se va a descontar el pedido</label>
			<select id="tipo_presupuesto" name="tipo_presupuesto" class="form-control">
				<option value="0">[Elegir tipo del Presupuesto]</option>
				<option value="seguridad">Seguridad</option>
				<option value="riesgo">Riesgo</option>
			</select>
		</div>
	</div>
	<div class="col-md-12">
		<p>&nbsp;</p>
		<button type="button" id="btn-add-producto" class="btn btn-default btn-primary pull-right" aria-label="Left Align">
			<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
		</button>
	</div>	
	<div class="col-md-12">
		<table class = "table table-responsive">
		  <thead>
		    <tr>
			   	<th></th>
			   	<th>Producto</th>
			   	<th>Cantidad</th>
			   	<th>Observación</th>
	        </tr>
		  </thead>
		  <tbody id = "lastRow">
		  </tbody>
		</table>
	</div>
	<input type="hidden" name="cantidad-productos" id="cantidad-productos" value="0"/>
<?php ActiveForm::end(); ?>
<button class="btn btn-primary btn-lg" onclick="enviar();">Crear</button>
<script type="text/javascript">
	function QuitarProducto(objeto){
		$(objeto).parent().parent().remove();

	}
</script>
