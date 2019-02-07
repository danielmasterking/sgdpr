<?php

use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Equivalencias';	

?>
   <?php if(isset($done) && $done === '200'):?>
   
     <p style="text-align: center;" class="alert alert-success">Equivalencia agregada.</p>
   
   <?php endif;?>
   
   
   <div class="page-header">
      <h1><small><i class="fa fa-balance-scale fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
	
        'model' => $model,
        'equivalencias' => $equivalencias,
		'productos_especiales' => $productos_especiales,
		'productos' => $productos,
    ]) ?>
<script>
  function eliminar_todo(){
    var txt;
    var r = confirm("¿Realmente desea borrar todas las Equivalencias?");
    if (r == true) {
        location.href='<?php echo Url::toRoute('equivalencia/delete-all')?>';
    }
  }
</script>