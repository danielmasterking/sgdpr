<?php 
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped my-data">
            <thead>
                <tr>
                    
                    <th style="text-align: center;width: 60px;">Tipo trabajo</th>
                    <th>Descripcion</th>
                    <th>Fecha inicio</th>
                    <th>Fecha Fin</th>
                    <th>Encargado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cronograma as $cr): ?>
                <tr>
                    
                    <td style="text-align: center;width: 60px;"><?= $cr->tipo_trabajo?></td>
                    <td><?= $cr->descripcion?></td>
                    <td><?= $cr->fecha_inicio?></td>
                    <td><?= $cr->fecha_fin?></td>
                    <td><?= $cr->encargado?></td>
                    <td>
                        <?php if($estado_crono=="A"): ?>
                        <?= Html::a('<i class="fa fa-edit"></i>', ['editarcronograma','id'=>$cr->id,'id_proyecto'=>$id], ['class' => 'btn btn-primary btn-xs']) ?>
                        <?= Html::a('<i class="fa fa-trash"></i>', ['deletecronograma','id'=>$cr->id,'id_proyecto'=>$id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Seguro desea eliminar?']) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
