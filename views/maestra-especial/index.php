<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Maestra Especial';
?>
<?= $this->render('_tabs',['maestraEspecial' => $maestraEspecial]) ?>
    <div class="page-header">
      <h1><small><i class="fa fa-wrench fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/maestra-especial/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   <th>Material</th>
           <th>Texto Breve</th>
		   <th>Precio Sugerido</th>
		   <th>Imputación</th>
		   <th>Estado</th>
		   <th></th>
		   <th></th>
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($maestras as $maestra):?>	  
			   
              <tr>			   
			   <td><?php
             
     			 echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/maestra-especial/update?id='.$maestra->id,['class'=>'btn btn-primary btn-xs']);
                 echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/maestra-especial/delete?id='.$maestra->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento?','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
				<td><?= $maestra->material?></td>
     			<td><?= $maestra->texto_breve?></td>
				<td><?= $maestra->precio?></td>
				<td><?= $maestra->imputacion?></td>
				<td><?= $maestra->estado?></td>
				<td>
				<?php Pjax::begin(); ?>
				  <?= Html::a('<i class="fa fa-check" aria-hidden="true"></i>', ['maestra-especial/activar-producto?id_detalle_producto='.$maestra->id], ['class' => 'btn btn-primary']);?>
				<?php Pjax::end(); ?>
				
				</td>
				<td>
				<?php Pjax::begin(); ?>
				<?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>', ['maestra-especial/desactivar-producto?id_detalle_producto='.$maestra->id], ['class' => 'btn btn-primary']);?>
				<?php Pjax::end(); ?>
				</td>
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>