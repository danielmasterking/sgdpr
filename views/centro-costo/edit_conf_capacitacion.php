<?php 
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use  yii\helpers\Url;

$this->title = 'Editar';


?>

<a href="<?php echo Url::toRoute('centro-costo/conf_capacitacion?&dependencia='.$dependencia)?>" class="btn btn-primary " >
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


