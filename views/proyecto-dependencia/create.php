<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProyectoDependencia */

$this->title = 'Crear Proyecto Dependencia';
$this->params['breadcrumbs'][] = ['label' => 'Proyecto Dependencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$ciudades_zonas = array();

$dependencias_distritos = array();




foreach($zonasUsuario as $zona){
	
     $ciudades_zonas [] = $zona->zona->ciudades;	
	
}

$ciudades_permitidas = array();

foreach($ciudades_zonas as $ciudades){
	
	foreach($ciudades as $ciudad){
		
		$ciudades_permitidas [] = $ciudad->ciudad->codigo_dane;
		
	}
	
}

$marcas_permitidas = array();

foreach($marcasUsuario as $marca){
	
		
		$marcas_permitidas [] = $marca->marca_id;

}



foreach($distritosUsuario as $distrito){
	
     $dependencias_distritos [] = $distrito->distrito->dependencias;	
	
}

$dependencias_permitidas = array();

foreach($dependencias_distritos as $dependencias0){
	
	foreach($dependencias0 as $dependencia0){
		
		$dependencias_permitidas [] = $dependencia0->dependencia->codigo;
		
	}
	
}

$tamano_dependencias_permitidas = count($dependencias_permitidas);

$data_dependencias = array();

foreach($dependencias as $value){
	
	if(in_array($value->ciudad_codigo_dane,$ciudades_permitidas)){
		
		if(in_array($value->marca_id,$marcas_permitidas)){
			
		   if($tamano_dependencias_permitidas > 0){
			   
			   if(in_array($value->codigo,$dependencias_permitidas)){
				   
				 $data_dependencias[$value->codigo] =  $value->nombre;
				   
			   }else{
				   //temporal mientras se asocian distritos
				   $data_dependencias[$value->codigo] =  $value->nombre;
			   }
			   
			   
		   }else{
			   
			   $data_dependencias[$value->codigo] =  $value->nombre;
		   }	
       
		}

	}
}

//print_r($array_finalizados);
?>

<div class="proyecto-dependencia-create">
<?= Html::a('<i class="fa fa-arrow-left"></i>', ['index'], ['class' => 'btn btn-primary']) ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data_dependencias'=>$data_dependencias,
        'array_finalizados'=>$array_finalizados
    ]) ?>

</div>

<script type="text/javascript">
	var empresas=<?= json_encode($array_empresas) ?>;
	var tipos_finalizado=<?= json_encode($array_finalizados) ?>;
	console.log(tipos_finalizado);
	$('#btn-agregar').click(function(event) {
		var sistema=$('#sistemas option:selected').text();
		var id_sistema=$('#sistemas option:selected').val();
		var input='<input type="hidden" name="sistemas[]" value="'+id_sistema+'" >';
		var input_otro='<input type="text" name="otro[]" style="display: none;" id="otro_'+id_sistema+'">';
		var check='<input type="checkbox" name="check_otro[]" onclick="activar_otro(this,'+id_sistema+')">';
		var option='<option>--Selecciona un encargado--</option>';
		console.log(id_sistema)
		var existe=0;
		$('#tbl_encargado tbody tr').each(function(){
			var td=$(this).find("td")[0].innerHTML.replace(/<[^>]*>?/g, '');//elimininamos los espacios vacios 
			console.log(td)
			existe=td==sistema?1:existe;
		});

		var select_finalizados="<select name='tipos_finalizado["+id_sistema+"][]' id='tipo_finalizado_"+id_sistema+"' multiple='true' class='form-control'>";
		$.each( tipos_finalizado, function( key, value ) {
		//if(value=="Acta"){
		//	var checked="selected";
		//}else{
			var checked="";
		//}

		  select_finalizados+="<option value='"+key+"' "+checked+">"+value+"</option>";
		});
		select_finalizados+="</select>";

		console.log(existe)
		if(existe==0){
			if(id_sistema!=0){
				$.each(empresas, function(i,item){
					 option+="<option value='"+i+"'>"+item+"</option>";
					
				})

				var html="<tr>";
				html+="<td>"+sistema+input+"</td>";
				html+="<td><select name='encargado[]' id='encargado_"+id_sistema+"'>"+option+"</select>"+input_otro+"</td>";
				html+="<td>"+check+"</td>";
				html+="<td>"+select_finalizados+"</td>";
				html+="<td><button class='btn btn-danger btn-xs' type='button' onclick='quitar(this);'><i class='fa fa-trash'></i></button></td>";
				html+="</tr>";

				$('#tbl_encargado tbody').prepend(html);
				$('#tipo_finalizado_'+id_sistema).select2()
			}else{
				alert('Debes seleccionar un sistema')
			}
		}else{

			alert('Ya se agrego este sistema');
		}

	});

	function quitar(objeto){
		var confirmar=confirm('seguro deseas quitar este elemento?');
		if(confirmar)
			$(objeto).parent().parent().remove();
	}

	function activar_otro(objeto,id_sistema){
		if($(objeto).prop('checked')){
			$('#otro_'+id_sistema).show();
			$('#encargado_'+id_sistema).hide();
		}else{
			$('#encargado_'+id_sistema).show();
			$('#otro_'+id_sistema).hide();
		}
	}
</script>
