<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proveedores';

?>
<?= $this->render('_tabs',['proveedor' => $proveedor]) ?>
    <div class="page-header">
      <h1><small><i class="fa fa-wrench fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/proveedor/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	 <table  class="display my-data" data-page-length='20' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   <th>Código</th>
           <th>Nombre</th>
		   <th>Tipo de suministro</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($proveedores as $novedad):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/proveedor/update?id='.$novedad->id,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/proveedor/delete?id='.$novedad->id,['data-method'=>'post','data-confirm' => 'Está seguro de eliminar elemento?','class'=>'btn btn-danger btn-xs']);

                    ?>
				</td>
                
				<td><?= $novedad->codigo?></td>
     			<td><?= $novedad->nombre?></td>
				<td><?= $novedad->detalle?></td>
				
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>