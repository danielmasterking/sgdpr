<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Distrito */

$this->title = 'Formulario de Visita Quincenal';
?>
 <?= $this->render('_tabs',['periodica' => $periodica]) ?>

<?php if(isset($done) && $done === '200'):?>
   
   <p style="text-align: center;" class="alert alert-success">Visita períodica creada de forma satisfactoria.</p>
   
<?php endif;?>

   <div class="page-header">
      <h1><small><i class="fa fa-suitcase fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>

   <?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        echo "<br><br>";
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
	?>

    <?= $this->render('_form', [
                'model' => $model,
				//'modelDetalle' => $modelDetalle,
				'categorias' => $categorias,
				'dependencias' => $dependencias,
				'marcasUsuario' => $marcasUsuario,
				'distritosUsuario' => $distritosUsuario,
				'zonasUsuario' => $zonasUsuario,
				'secciones' => $secciones,
    ]) ?>