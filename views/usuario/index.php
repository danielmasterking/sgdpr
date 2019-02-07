<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';

?>
    <div class="page-header">
	  <h1><small><i class="fa fa-user fa-fw"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>
	
	<div class="form-group">

	<?= Html::a('<i class="fa fa-plus"></i>',Yii::$app->request->baseUrl.'/usuario/create',['class'=>'btn btn-primary']) ?>
		
	</div>	
    
	<?php 

    $flashMessages = Yii::$app->session->getAllFlashes();
    if ($flashMessages) {
        echo "<br><br>";
        foreach($flashMessages as $key => $message) {
            echo "<div class='alert alert-" . $key . " alert-dismissible' role='alert'>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                    $message
                </div>";   
        }
    }
	?>

	 <table  class="display my-data" data-page-length='50' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>
		   <th>Usuario</th>
           <th>Nombre</th>
		   <th>Email</th>
		   <th>Cargo</th>
		   <th>Regional</th>
		   <th>Area</th>
		   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($usuarios as $usuario):?>	  
			   
              <tr>			   
			   <td><?php
                echo Html::a('<i class="fas fa-edit"></i>',Yii::$app->request->baseUrl.'/usuario/update?id='.$usuario->usuario,['class'=>'btn btn-primary btn-xs']);
                echo Html::a('<i class="fa fa-plus fa-fw"></i>',Yii::$app->request->baseUrl.'/usuario/actividades-macro?id='.$usuario->usuario,['class'=>'btn btn-info btn-xs']);
                echo Html::a('<i class="fa fa-key fa-fw"></i>',Yii::$app->request->baseUrl.'/usuario/rescontrasena?id='.$usuario->usuario,['data-confirm'=>'Seguro desea reestablecer la clave de este usuario','class'=>'btn btn-success btn-xs','title'=>'Reestablecer clave']);

				echo Html::a('<i class="fa fa-trash"></i>',Yii::$app->request->baseUrl.'/usuario/delete?id='.$usuario->usuario,['data-method'=>'post','class'=>'btn btn-danger btn-xs','data-confirm'=>'Seguro desea eliminar?']);

                    ?>
				</td>
                
     			<td><?= $usuario->usuario?></td>
				<td><?= $usuario->nombres.' '.$usuario->apellidos?></td>
				<td><?= $usuario->email?></td>
				<td><?= $usuario->cargo?></td>
				<td><?php 
				
				    $zonas = $usuario->zonas;
					
					if($zonas != null){
						
						echo $zonas[0]->zona->nombre;
						
					}
				   
				
				?>
				
				</td>
				<td><?= $usuario->area?></td>
              </tr>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>