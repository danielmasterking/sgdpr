<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProyectoDependenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proyectos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proyecto-dependencia-index">

    <h1><i class="fas fa-city"></i> <?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Crear', ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="row">
        <div class="col-md-12">
            <table  class="table table-striped my-data" data-page-length='50'>
                <thead>
                    <tr>
                        <th></th>
                        <th>Dependencia</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Regional</th>
                        <th>Fecha de apertura</th>
                        <th>Estado Presupuesto</th>
                        <th># Seguimientos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row):?>
                    <tr>
                        <td>
                            <?= Html::a('<i class="fa fa-eye"></i>', ['view','id'=>$row->id], ['class' => 'btn btn-primary btn-xs']) ?>
                            <?php if(in_array("administrador", $permisos)){ ?>
                            <?= Html::a('<i class="fa fa-trash"></i>', ['delete','id'=>$row->id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Seguro desea eliminar?']) ?>
                            <?php } ?>
                        </td>    
                        <td><?= $row->cecoo->nombre?></td>
                        <td><?= $row->nombre?></td>
                        <td><?= $row->cecoo->marca->nombre?></td>   
                        <td><?= $row->cecoo->ciudad->zona->zona->nombre?></td>    
                        <td><?= $row->fecha_apertura?></td>
                        <td><?= $row->estado?></td>
                        <td><?= $model->NumSeguimientos($row->id)?></td>    
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>
