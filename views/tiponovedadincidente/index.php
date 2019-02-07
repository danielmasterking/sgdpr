<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TipoNovedadIncidenteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tipo Novedad Investigaciones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tipo-novedad-incidente-index">

    <div class="page-header">
      <h1><small><i class="far fa-dot-circle"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nombre',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
