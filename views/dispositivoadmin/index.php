<?php

use yii\helpers\Html;
use yii\grid\GridView;
use  yii\grid\DataColumn;
/* @var $this yii\web\View */
/* @var $searchModel app\models\DispositivoAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Dispositivo Administracion y Supervision';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispositivo-admin-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
         <?= Html::a('<i class="fa fa-reply"></i> Atras', ['adminsupervision/index'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-plus"></i> Crear Nuevo', ['create'], ['class' => 'btn btn-primary']) ?>

    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'empresa.nombre',
            [
                'class' => DataColumn::className(), // this line is optional
                'attribute' => 'empresa.nombre',
                'format' => 'text',
                'label' => 'Empresa',
            ],
            'nombre',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>
</div>
