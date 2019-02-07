<?php 
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use  yii\helpers\Url;

$this->title = 'Numero de capacitaciones-'.$centro_costo->nombre;


?>
<a href="<?php echo Url::toRoute('centro-costo/capacitacion?&id='.$dependencia)?>" class="btn btn-primary " >
    <i class="fa fa-arrow-left"></i> 
</a>

<h1 class="text-center"><?php echo $this->title  ?></h1>

<?php $form = ActiveForm::begin(['id'=>'form_create']); ?>
<?php 
	echo $form->field($model, 'id_novedad')->widget(Select2::classname(), [
	    'data' =>$list_novedades,
	    'options' => ['placeholder' => 'Selecciona una novedad']
	]);

?>

<?= $form->field($model, 'cantidad')->textInput() ?>

<button type="submit" class="btn btn-primary">Guardar</button>
<?php ActiveForm::end(); ?>
<br>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th>Novedad</th>
			<th>Cantidad</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($novedad_dep as $row): ?>
			<tr>
				<td>
					<a href="<?php echo Url::toRoute('centro-costo/edit_conf_capacitacion?id='.$row['id'].'&dependencia='.$dependencia)?>" class="btn btn-primary btn-xs" >
					    <i class="fa fa-edit"></i> Editar
					</a>

					<a data-confirm='Seguro desea eliminar?' href="<?php echo Url::toRoute('centro-costo/delete_conf_capacitacion?id='.$row['id'].'&dependencia='.$dependencia)?>" class="btn btn-danger btn-xs" >
					    <i class="fa fa-trash"></i> Eliminar
					</a>
				</td>
				<td><?= $row->novedad->nombre?></td>
				<td><?= $row->cantidad?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>