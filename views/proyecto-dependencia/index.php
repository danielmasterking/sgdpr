<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Proyectos;

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
            <table  class="table table-striped data-table" data-page-length='50'>
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
                        <th>Ultima Actualizacion</th>
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
                        <td>
                            <?php
                                echo Proyectos::Seguimiento($row->id)->fecha;
                            ?>
                        </td>    
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<script type="text/javascript">
    $(function(){
        var table3 = $('.data-table').DataTable({
        "columnDefs": [{
            "className": "dt-center",
            "targets": "_all"
        }],
        "order": [[ 8, "desc" ]],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'],
        // "order": [[0,"desc"]],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    table3.buttons().container().appendTo($('.col-sm-6:eq(0)', table3.table().container()));
    });
</script>
