<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Formulario de formación o capacitación';
?>
<?php if(isset($done) && $done === '200'):?>

<p style="text-align: center;" class="alert alert-success">Capacitación creada de forma correcta.</p>
   
<?php endif;?>

    <div class="page-header">
	  <h1><small><i class="fa fa-graduation-cap fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>

    <?= $this->render('_form', [
        'model' => $model,
		'dependencias' => $dependencias,
		'novedades' => $novedades,
		'cordinadores' => $cordinadores,
		'marcasUsuario' => $marcasUsuario,
		'distritosUsuario' => $distritosUsuario,
		'zonasUsuario' => $zonasUsuario,
		
    ]) ?>