<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Cliente;
use app\models\VisitaMensual;
use app\models\VisitaDia;
use app\models\NovedadDependencia;
use yii\web\JsExpression;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;



/* @var $this yii\web\View */
/* @var $searchModel app\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Indicadores';

$arr_meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto',
            '09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
        );

$model_visita= new VisitaDia;

if( isset(Yii::$app->session['permisos-exito']) ){

  $permisos = Yii::$app->session['permisos-exito'];

}

$roles_usuario_actual = $active_user->roles;
$data_rol = array();

foreach($roles_usuario_actual as $key){
	
  $data_rol [] = $key->rol_id;	
	
	
}


$zonas = $active_user->zonas;
$zonaPrincipal = '';
if($zonas != null){
	
	$zonaPrincipal = $zonas[0]->zona->nombre;	
	
}

$roles = $active_user->roles;
$roles_array = array();

foreach($roles as $key){
	
	$roles_array [] = $key->rol->id;
	
	
}


// echo "<pre>";

// print_r($roles_array);
// echo "</pre>";




 /*$string = '[';
 
	foreach($consolidadoCapacitaciones as $key){

		$can = $key['TOTAL'] * 1;
		
		$datos [] = [ 'name' => $key['REGIONAL'], 'data' => [$can] ];
		
		$string .= $key['TOTAL'].',';	
		
	} 
	
	foreach($consolidadoComites as $key){

		
		$can = $key['TOTAL'] * 1;
	
		$string .= $key['TOTAL'].',';	
		
		$tamano_datos = count($datos);
		
		for($i = 0; $i < $tamano_datos; $i++ ){
			
			
			
			if($datos[$i]['name'] == $key['REGIONAL']){
				
				//$tem [] = array_push($x['data'],$can);
				
				
				$datos[$i]['data'] [] = $can;
				
				//var_dump(key($x));
				
			}
			
		}
	
	} 
	
	//Visitas Períodicas
	
	foreach($consolidadoVisitas as $key){

		
		$can = $key['TOTAL'] * 1;
	
		$string .= $key['TOTAL'].',';	
		
		$tamano_datos = count($datos);
		
		for($i = 0; $i < $tamano_datos; $i++ ){
			
			
			
			if($datos[$i]['name'] == $key['REGIONAL']){
				
				//$tem [] = array_push($x['data'],$can);
				
				
				$datos[$i]['data'] [] = $can;
				
				//var_dump(key($x));
				
			}
			
		}
	
	}*/ 
	
	//Sumar Visitas Mensuales a vi
	
/*	foreach($consolidadoSiniestros as $key){

		
		$can = $key['TOTAL'] * 1;
	
		$string .= $key['TOTAL'].',';	
		
		$tamano_datos = count($datos);
		
		for($i = 0; $i < $tamano_datos; $i++ ){
			
			
			
			if($datos[$i]['name'] == $key['REGIONAL']){
				
				//$tem [] = array_push($x['data'],$can);
				
				
				$datos[$i]['data'] [] = $can;
				
				//var_dump(key($x));
				
			}
			
		}
	
	} */
	
	/*foreach($consolidadoIncidentes as $key){

		
		$can = $key['TOTAL'] * 1;
	
		$string .= $key['TOTAL'].',';	
		
		$tamano_datos = count($datos);
		
		for($i = 0; $i < $tamano_datos; $i++ ){
			
			
			
			if($datos[$i]['name'] == $key['REGIONAL']){
				
				//$tem [] = array_push($x['data'],$can);
				
				
				$datos[$i]['data'] [] = $can;
				
				//var_dump(key($x));
				
			}
			
		}
	
	} 	*/
//	var_dump($datos);


	
?>
   <div class="page-header">
	  <h1><small><i class="fas fa-chart-pie"></i></small> <?= Html::encode($this->title) ?></h1>
	</div>
	
	

	
	<p>&nbsp;</p>
	

	<div class="row">
	
			
			
			
			<form method="post" data-pjax=''> 
			   
			   
			   <div class="col-md-12">
			   
				   <div class="col-md-3">
				   
					 <?php


							 echo DateControl::widget([
							'name'=>'fecha_inicial', 
							'type'=>DateControl::FORMAT_DATE,
							'autoWidget' => true,
							'value'=>$fecha_inicial,
							'displayFormat' => 'php:Y-m-d',
							'saveFormat' => 'php:Y-m-d'

							 ]);



					 ?>
				   
				   
				   </div>
				   

				   <div class="col-md-3">
				   
					 <?php


							 echo DateControl::widget([
							'name'=>'fecha_final',
							'type'=>DateControl::FORMAT_DATE,
							'autoWidget' => true,
							'value'=>$fecha_final,
							'displayFormat' => 'php:Y-m-d',
							'saveFormat' => 'php:Y-m-d'

							 ]);



					 ?>
					 
				   </div>

				   <div class="col-md-3">
				   		<select class="form-control" name="reg" required="" id="reg" >
				   			<option value="">Selecciona una region</option>
				   			<option value="Nacional">Consolidado Nacional</option>
				   			<?php foreach($regionales as $reg): ?>
				   				<option value="<?= $reg->id?>"><?= $reg->nombre?></option>
				   			<?php endforeach;?>
				   		</select>
				   </div>

				   
				   <div class="col-md-3">
				   
					<button id='btn-send' class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Consultar</button>
					<div id="info"></div>
				   </div>
			   
			   </div>
				<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />

			</form>		
	
	</div>
	
	<br>
	<?php if($regional!=''):?>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			

				<?php 
					$total_regional_semestral=0;
				  	$total_regional_quincenal=0;
				  	$total_regional_capacitacion=0;
				  	$cantidad_regionales=0;
				  	

				  	
				  	if($regional=='Nacional'){
				  		$deps=VisitaMensual::DepsAll();
				  		$nombre_regional=$regional;
				  	}else{
				  		$deps=VisitaMensual::DependenciasZona($regional);
				  		$nombre_regional=$reg_nombre->nombre;
				  	}

				  	$total_calif_semestral=0;
	  			$total_calif_visita=0;
	  			$total_calif_capacitaciones=0;
	  			$total_deps=0;
	  			foreach ($deps as $key => $value) {
	  				if($value->indicador_semestre=='S'){
	  					$primerSemestre=VisitaMensual::CalifSemestre($value->codigo,1,$fecha_inicial,$fecha_final);
	  					$segundoSemestre=VisitaMensual::CalifSemestre($value->codigo,2,$fecha_inicial,$fecha_final);
	  					$totalAno=($primerSemestre+$segundoSemestre)/2;

	  					$total_calif_semestral+=$totalAno;
	  				}


	  				if($value->indicador_visita=='S'){
	  					$calif_ano=0;
			            foreach ($arr_meses as $key_mes => $value_mes) {
			            

			                $num_visita= $model_visita->Num_visitas($key_mes,$value->codigo);

			                if ($num_visita==0) {
			                    $calif=0;

			                }elseif($num_visita>=2){

			                   $calif=100;

			                }elseif($num_visita<2){

			                    $calif=50;
			                }


			                $calif_mes=round(($calif*8.33)/100, 2, PHP_ROUND_HALF_DOWN);

			                $calif_ano+=$calif_mes;
			            }
			            $total_calif_visita+=$calif_ano;
	  				}

	  				if($value->indicador_capacitacion=='S'){
	  					$califSegretail=NovedadDependencia::CalificacionTema(20,$value->codigo,$fecha_inicial,$fecha_final);
						$califVigia=NovedadDependencia::CalificacionTema(21,$value->codigo,$fecha_inicial,$fecha_final);
						$califAnual=($califSegretail+$califVigia)/2;
						$total_calif_capacitaciones+=$califAnual;
	  				}


	  				$total_deps++;
	  			}

	  			$inspeccion_semestral=round(($total_calif_semestral/$total_deps), 2, PHP_ROUND_HALF_DOWN);
	  			$visita_quincenal=round(($total_calif_visita/$total_deps), 2, PHP_ROUND_HALF_DOWN);
	  			$capacitaciones=round(($total_calif_capacitaciones/$total_deps), 2, PHP_ROUND_HALF_DOWN);

	  			$total_regional_semestral=$total_regional_semestral+$inspeccion_semestral;
	  			$total_regional_quincenal=$total_regional_quincenal+$visita_quincenal;
				$total_regional_capacitacion=$total_regional_capacitacion+$capacitaciones;
				$cantidad_regionales++;
	  			$thubnail='	
	  						
	  							 <div class="panel panel-warning">
								  <div class="panel-heading" ><h3 class="text-center"><i class="fa fa-map"></i> '.$nombre_regional.'</h3></div>
								  <div class="panel-body">
						        
						        <table class="table table-striped" >
						        	
						        	<tr>
						        	  <th>Isnpeccion Semestral:</th>
						        	  <td>'.$inspeccion_semestral.'%</td>
						        	</tr>

						        	<tr>
						        	  <th>Visita Quincenal:</th>
						        	  <td>'.$visita_quincenal.'%</td>
						        	</tr>

						        	<tr>
						        	  <th>Capacitaciones:</th>
						        	  <td>'.$capacitaciones.'%</td>
						        	</tr>

						        </table>
						         </div>
							</div>
						    
						      ';
						      echo $thubnail;
				
				?>

				
			</ul>
		</div>


	</div>
<?php else: ?>
	<div class="alert alert-info" role="alert"><i class="fa  fa-info"></i> Selecciona una regional para observar la estadistica</div>
<?php endif;?>
	
	<div class="col-md-12">
		<div class="table-responsive">
	<table  class="display my-data" data-page-length='50' cellspacing="0" width="100%">
	 
       <thead>

       <tr>
           
           <th></th>

           <th>Nombre</th>
		   <th>Cargo</th>
		   <th>Regional</th>
   
           
       </tr>
           

       </thead>	 
	   
	   <tbody>
	   
             <?php foreach($usuarios as $usuario):?>	  
			   
			   <?php if($usuario->usuario != 'admin'): ?>
			   
			   <?php
			       //validar roles
				   if( in_array("administrador", $permisos) || in_array("ver_cordinadores_nacional", $permisos)){
				
				?>							   			   
				  <tr>			   
				   <td><?php
					//echo $usuario->area;

					//if($usuario->area=='Seguridad'){
						echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/usuario/indicador-capacitaciones?id='.$usuario->usuario,['class'=>'btn btn-info btn-xs']);
					//}


					// //if($usuario->area=='Riesgos'){
					// 	echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/usuario/gestiones?id='.$usuario->usuario);
					// }

					?>
					</td>
					
					<td><?= $usuario->nombres.' '.$usuario->apellidos?></td>
					<td><?= $usuario->cargo?></td>
					
					<?php 
					
					   $tmp = $usuario->zonas;
					   $zona_tmp = '';
					   if($tmp != null){
						   
						   $zona_tmp = $tmp[0]->zona->nombre;
						   
					   }
					?>
					
					<td><?= $zona_tmp?></td>
					

				  </tr>
				
				<?php
					   
				   }else{
					   
					   $zonasCurrentUser = $usuario->zonas;
			   					   
					   if($zonasCurrentUser != null){
						   
						   if($zonasCurrentUser[0]->zona->nombre == $zonaPrincipal && ( in_array(2,$data_rol) || in_array(19,$data_rol) ) ){
							   
							   ?>
							   
								  <tr>			   
								   <td><?php
									
									echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/usuario/capacitacion?id='.$usuario->usuario);

										?>
									</td>
									
									<td><?= $usuario->nombres.' '.$usuario->apellidos?></td>
									<td><?= $usuario->cargo?></td>
									
									<?php 
									
									   $tmp = $usuario->zonas;
									   $zona_tmp = '';
									   if($tmp != null){
										   
										   $zona_tmp = $tmp[0]->zona->nombre;
										   
									   }
									?>
									
									<td><?= $zona_tmp?></td>
									

								  </tr>							   
							   
							   
							   <?php
							   
							   
						   }else{
							   
							   if($usuario->usuario == $active_user->usuario){
								   
								   ?>
								
								<tr>			   
								   <td><?php
									
									echo Html::a('<i class="fa fa-eye"></i>',Yii::$app->request->baseUrl.'/usuario/capacitacion?id='.$usuario->usuario);

										?>
									</td>
									
									<td><?= $usuario->nombres.' '.$usuario->apellidos?></td>
									<td><?= $usuario->cargo?></td>
									
									<?php 
									
									   $tmp = $usuario->zonas;
									   $zona_tmp = '';
									   if($tmp != null){
										   
										   $zona_tmp = $tmp[0]->zona->nombre;
										   
									   }
									?>
									
									<td><?= $zona_tmp?></td>
									

								  </tr>										   
								   
								   
								   <?php
								   
								   
							   }
							   
						   }
						   
					   }
					   
					   
				   }
			   
			   ?>

			  <?php endif;?>
        	 <?php endforeach; ?>			 
	   
	   </tbody>
	 
	 </table>
	 </div>
	</div>
<script type="text/javascript">
	$('#btn-send').click(function(event) {

		if($('#reg option:selected').val()!='')
			$('#info').html('<i class="fa fa-gear fa-spin"></i> Cargando Por favor espere.....');
	});
</script>
	 <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

