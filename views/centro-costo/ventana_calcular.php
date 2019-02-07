<?php 

use yii\helpers\Html;
use yii\helpers\Url;

?>


<a href="<?php echo Url::toRoute('centro-costo/calcular_total')?>" class="btn btn-primary btn-lg">
<i class="fa fa-calculator"></i>
	Calcular  Dispositivos fijos
</a>


<a href="<?php echo Url::toRoute('centro-costo/calcular_monitoreo')?>" class="btn btn-primary btn-lg">
<i class="fa fa-calculator"></i>
	Calcular Monitoreos
</a>