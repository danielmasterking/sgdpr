<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\MensajeNovedad */
/* @var $form yii\widgets\ActiveForm */
$data_valores = array();

foreach ($valores as $value) {
    
    if($value->novedadCategoriaVisita->estado=='A'  AND $value->resultado->estado=='A'){
      $data_valores[$value->id] = $value->novedadCategoriaVisita->nombre.'-'.$value->resultado->nombre;
    }
}
?>

<div class="mensaje-novedad-form">

    <?php $form = ActiveForm::begin(); ?>

    
    <?=

       $form->field($model, 'valor_novedad_id')->widget(Select2::classname(), [
       
       'data' => $data_valores,
    
      ])


     ?>

     <button class="btn btn-success" type="button" onclick="agregar();"><i class="fa fa-plus"></i></button>
     
    <?= $form->field($model, 'mensaje')->textInput(['maxlength' => true,'id'=>'mensaje','name'=>'mensaje[]']) ?>
    <div id="mensajes">
      
    </div>
    <?//= $form->field($model, 'criterio')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
  function agregar(){

   var html="<div class='form-inline'>"+
   "<input type='text' class='form-control' name='mensaje[]' required>"+
   "<button type='button' class='btn btn-danger' onclick='eliminar(this);'><i class='fa fa-minus'></i></button><br><br>"+
   "</div>";

   $('#mensajes').append(html);

  }

  function eliminar(objeto){

    $(objeto).parent().remove();
  } 
</script>
