<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HelpConsultaGestion */

$this->title = 'Actualizar ayuda: ';
$this->params['breadcrumbs'][] = ['label' => 'Help Consulta Gestions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="help-consulta-gestion-update">

<?= $this->render('_tabs',['help' => 'active' ]) ?>

    <h1><?= Html::encode($this->title) ?></h1>

     <?php 

        $flashMessages = Yii::$app->session->getAllFlashes();
        if ($flashMessages) {
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
        'actualizar'=>true,
        'preguntas'=>$preguntas,
    ]) ?>

</div>
