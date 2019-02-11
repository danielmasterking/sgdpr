<?php 
use yii\helpers\Html;
?>
<table class="table table-striped">
    <thead>
        <tr>
            <th></th>
            <th>Tipo trabajo</th>
            <th>Descripcion</th>
            <th>Fecha inicio</th>
            <th>Fecha Fin</th>
            <th>Encargado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($cronograma as $cr): ?>
        <tr>
            <td>
                <?= Html::a('<i class="fa fa-edit"></i>', ['editarcronogrma','id'=>$cr->id,'id_proyecto'=>$id], ['class' => 'btn btn-primary btn-xs']) ?>
                <?= Html::a('<i class="fa fa-trash"></i>', ['deletecronograma','id'=>$cr->id,'id_proyecto'=>$id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Seguro desea eliminar?']) ?>
            </td>
            <td><?= $cr->tipo_trabajo?></td>
            <td><?= $cr->descripcion?></td>
            <td><?= $cr->fecha_inicio?></td>
            <td><?= $cr->fecha_fin?></td>
            <td><?= $cr->encargado?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>