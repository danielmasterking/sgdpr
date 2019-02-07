<?php 
use yii\helpers\Url;

?>

<a href="<?php echo Url::toRoute('centro-costo/ventana_calcular')?>" class="btn btn-primary">
        <i class="fa fa-reply"></i> 
</a>

<h1>
	<i class="fa fa-calculator"></i>
	Calcular Precios Monitoreos <?php echo date('Y')?>
</h1>

<button class="btn btn-primary" onclick="calcular();" id="btn_calcular">
 <i class="fa fa-usd" aria-hidden="true"></i>
 CALCULAR
</button>

<div id="body_ayuda"></div>

<br><br>

<table class="table table-bordered" id="monitoreos">
	<thead>
		<tr>
			<th>Id</th>
			<th>Id_sistema</th>
			<th>Dependencia_codigo</th>
			<th>Dependencia_nombre</th>
			<th>Cantidad servicios</th>
			<th>Fecha Inicio</th>
			<th>Fecha Fin</th>
			<th>Empresa</th>
			<th>Calculo</th>

		</tr>
	</thead>
	<tbody>
		<?php 

		foreach($query as $row){
		?>
		<tr>
			<td><?= $row->id ?></td>
			<td><?= $row->id_sistema_monitoreo ?></td>
			<td><?= $row->centro_costo_codigo ?></td>
			<td><?= $row->dep->nombre ?></td>
			<td><?= $row->cantidad_servicios ?></td>
			<td><?= $row->fecha_inicio ?></td>
			<td><?= $row->fecha_fin ?></td>
			<td><?= $row->dep->emp_seg->nombre ?></td>
			<td></td>
		</tr>
		<?php 
		}
	    ?>
	</tbody>

</table>


<script type="text/javascript">
	
function calcular(){

	var confirmar=confirm('Seguro desea actualizar estos Monitoreos');

	if(confirmar){
		try {
		/******************************************************/	
			$('#monitoreos tbody tr').each(function(index, el) {
				
				var id=$(this).find('td').eq(0).html();
				var sistema=$(this).find('td').eq(1).html();
				var dependencia=$(this).find('td').eq(2).html();
				var cantidad=$(this).find('td').eq(4).html();
				var fecha_inicio=$(this).find('td').eq(5).html();
				var fecha_fin=$(this).find('td').eq(6).html();

				var precio=buscar_precio(sistema,dependencia);
				var calculo=calculo_total(fecha_inicio,fecha_fin,cantidad,precio);

				$(this).find('td').eq(8).html('$ '+calculo.formatPrice()+' COP');

				$.ajax({
		            url:"<?php echo Yii::$app->request->baseUrl . '/centro-costo/actualizar_precio_monitoreo'; ?>",
		            type:'POST',
		            dataType:"json",
		            cache:false,
		            async:false,
		            data: {
		                id: id,
		                calculo:calculo,
		                valor_unitario:precio
		                
		            },
		            beforeSend:  function() {
		            	$('#btn_calcular').hide();
		                $('#body_ayuda').html('Actualizando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
		            },
		            success: function(data){
		              
		            }
		        });








			});


			$('#btn_calcular').show();
 			$('#body_ayuda').html('Actualizado <i class="fa fa-check" aria-hidden="true"></i>');
		/******************************************************/
		}catch(err) {
		    
		    //alert(err.message);
		    alert('Ocurrio un error contacte con el desarrollador del sitio');

		    $('#body_ayuda').html('ERROR <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');
		}


	}else{

		return false;
	}
}



function calculo_total(fecha_i,fecha_f,cantidad_serv,valor_unitario){
		var fecha_inicio=moment(fecha_i);
		var fecha_final=moment(fecha_f);

		// if ($('#fecha_inicio').val()==$('#fecha_fin').val()) {
		// 	alert('entro en el if');
		// 	var dias=1;
		// }else{
			
			var dias=fecha_final.diff(fecha_inicio, 'days')+1;

			
		// }


		var cantidad_servicios=cantidad_serv;
		var valor_unitario=valor_unitario;

		var total=(dias)*(cantidad_servicios*valor_unitario)/(30);

		
		return total;
	}

	function buscar_precio(sistema,dependencia){
		var precio=0;
		$.ajax({
	            url:"<?php echo Yii::$app->request->baseUrl . '/centro-costo/preciomonitoreo'; ?>",
	            type:'POST',
	            dataType:"json",
	            cache:false,
	            async:false,
	            data: {
	                sistema:sistema,
	                centro_costo:dependencia
	               //empresa:$('#modelomonitoreo-id_empresa').val()
	            },
	            beforeSend:  function() {
	               // $('#loading').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
	            },
	            success: function(data){
	               // $('#valor_unitario').val(data.precio);

	               // if (data.precio==null) {
	               // 		$('#loading').html('<p class="text-danger">No se encontro precio para este sistema en esta empresa</p>');
	               // }else{
	               // 		$('#loading').html('');
	               // }

	               // if ($('#fecha_inicio').val()!='' && $('#fecha_fin').val()!='' ) {
	               // 	 calculo_total();	
	               // }


	               precio=data.precio;
	               
	              // document.write(data);
	            }
        	});

		return precio;
	}

	Number.prototype.formatPrice = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
    };

</script>













