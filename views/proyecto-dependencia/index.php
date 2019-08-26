<style type="text/css">
    .label-default{
        background-color: black !important;
        color :white !important;
    }
</style>
<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Proyectos;
$proyectos=new Proyectos;
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

        <?php 
            if ($estado=='A') {
                echo Html::a('Finalizados', ['index','estado'=>'F'], ['class' => 'btn btn-primary']);
            }else{
                echo Html::a('Abiertos', ['index','estado'=>'A'], ['class' => 'btn btn-primary']);
            }

        ?>
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
                        <th>Fecha Fin-entrega</th>
                        <!-- <th>Estado Presupuesto</th> -->
                        <!-- <th># Seguimientos</th> -->
                        <th>Ultima Actualizacion</th>
                        <th>Presupuesto</th>
                        <th>Cronograma</th>
                        <th>% Porcentaje Total</th>
                        <th>Seguimiento en dias</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($rows as $row):
                            //$fecha_final=$proyectos->Get_fecha_finalizacion($row->id,$row->fecha_apertura);
                            if($row->fecha_inicio_trabajo!="0000-00-00"){
                                $fecha_inicio_trabajos=$proyectos->Get_fecha_inicio($row->id,$row->fecha_inicio_trabajo);
                                $fecha_final=strtotime ( '+'.(int)$row->dias_trabajo.' day' , strtotime ( $fecha_inicio_trabajos ) ) ;
                                $fecha_final=date ( 'Y-m-d' , $fecha_final );
                            }else{
                                $fecha_inicio_trabajos=null;
                                $fecha_final=null;

                            }
                    ?>
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
                        <td><?= $fecha_inicio_trabajos?></td>
                        <!-- <td><?//= $row->estado?></td> -->
                        <!-- <td><?//= $model->NumSeguimientos($row->id)?></td> -->
                        <td>
                            <?php
                                echo Proyectos::Seguimiento($row->id)->fecha;
                            ?>
                        </td>
                        <td>
                            <?php 
                                 echo $row->estado=='CERRADO'?'<span class="text-success"><i class="fas fa-check"></i> OK</span>':'<span class="text-red"><i class="fas fa-comments-dollar"></i> Pendiente</span></th>';

                            ?>
                        </td>
                        <td>
                            <?php 

                                echo $row->estado_cronograma=='C'?'<span class="text-success"><i class="fas fa-check"></i> OK</span>':'<span class="text-red"><i class="fas fa-question"></i> Pendiente</span></th>';

                            ?>
                        </td>
                        <td>
                          <?php echo Proyectos::PromedioProyecto($row->id)."%" ?>
                        </td>   
                        <td>
                            <?php 
                            if($row->fecha_inicio_trabajo!="0000-00-00"){
                               //$fecha_final=$proyectos->Get_fecha_finalizacion($row->id,$row->fecha_apertura);
                               $date1 = new DateTime(date('Y-m-d'));
                                $date2 = new DateTime($fecha_final);
                                $diff = $date1->diff($date2);
                                $dias=$row->dias_seguidos!=0 || $row->dias_seguidos!=''?$row->dias_seguidos:$diff->format('%R%a');
                                $color=$dias<=20?'label-warning':'label-info';
                                //$color=$diff->days<=15?'label-warning':$color;
                                $color=$dias<=7?'label-success':$color;
                                $color=$date2 < $date1 ?'label-danger':$color;
                                $color=$row->dias_seguidos!=0 || $row->dias_seguidos!=''?'label-default':$color;
                                //echo $diff->days;
                                
                                echo "<h4><span class='label ".$color."' >".$dias." </span></h4>";
                                
                            }else{

                                echo '<span class="text-red"><i class="fas fa-question"></i></span>';
                            }


                            ?>
                        </td> 
                        <td>
                            <?php 
                                $estado=$row->estado_proyecto=='A'?'Abierto':'Finalizado';
                                $label=$row->estado_proyecto=='A'?'label-warning':'label-success';
                            ?>
                            <span class="label <?= $label?>"><?= $estado?></span>
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
        "order": [[ 10, "asc" ]],
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
