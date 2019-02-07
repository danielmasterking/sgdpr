
<?php 
 use yii\helpers\Url;
$hora_inicio_nocturna='00:00';
$hora_fin_nocturna='00:00';
$hora_inicio_diurna='00:00';
$hora_fin_diurna='00:00';
foreach ($jornada as $key) {
	if ($key->nocturna=='S') {
		$hora_inicio_nocturna=$key->hora_inicio;
		$hora_fin_nocturna=$key->hora_fin;
	}else if ($key->nocturna=='N') {
		$hora_inicio_diurna=$key->hora_inicio;
		$hora_fin_diurna=$key->hora_fin;
	}
}


$data_servicios = array();
$servicios2 = array();
foreach($servicios as $value){
	$label = $value->servicio->nombre.'-'.$value->descripcion;
	$data_servicios [] = array('id' => $value->id,'ano' => $value->ano,'precio' => $value->precio,'precio_nocturno' => $value->precio_nocturno,'codigo' => $value->servicio->id, 'nombre' => $label, 'servicio_id' => $value->servicio_id);
	$servicios2 [$value->id] = $label;
}

/*echo "<pre>";
print_r($data_servicios);
echo "</pre>";*/
?>

<a href="<?php echo Url::toRoute('centro-costo/ventana_calcular')?>" class="btn btn-primary">
        <i class="fa fa-reply"></i> 
</a>


<h1>
	<i class="fa fa-calculator"></i>
	Calcular valor dispositivos año <?php echo date('Y')?>
		
</h1>


<button class="btn btn-primary" onclick="calcular();" id="btn_calcular">
	<i class="fa fa-usd" aria-hidden="true"></i>CALCULAR
</button>
<div id="body_ayuda"></div>

<br>
<br>

<table class="table table-bordered" id="dispositivos">
	<thead>
		<tr>
			<th>id</th>
			<th>Horas</th>
			<th>Servicio</th>
			<th>Cantidad</th>
			<th>Hora inicio</th>
			<th>Hora Fin</th>
			<th>Horas</th>
			<th>Dias</th>
			<th>Porcentaje</th>
			<th>Calculo</th>
			<th>Dependencia</th>
			<th>Ftes_diurnos</th>
			<th>Ftes_nocturnos</th>
			<th>Ftes_total</th>

		</tr>
	</thead>
	<tbody>
		
		<?php foreach($query as $row): ?>
			<tr>
				<td><?= $row->id ?></td>
				<td><?= $row->horas ?></td>
				<td><?= $row->detalle_servicio_id ?></td>
				<td><?= $row->cantidad_servicios ?></td>
				<td><?= $row->hora_inicio ?></td>
				<td><?= $row->hora_fin ?></td>
				<td><?= $row->horas ?></td>
				<td><?= $row->total_dias ?></td>
				<td><?= $row->porcentaje ?></td>
				<td></td>
				<td><?= $row->centroCostoCodigo->nombre ?></td>
				<td></td>
				<td></td>
				<td></td>

			</tr>
		<?php endforeach;?>
	</tbody>

</table>


<script type="text/javascript">
	var servicios = JSON.parse('<?php echo json_encode($data_servicios);?>');
	var inicio_nocturno='<?=$hora_inicio_nocturna?>'//'22:00';
   	var fin_nocturno='<?=$hora_fin_nocturna?>'//'05:59';
   	var inicio_diurno='<?=$hora_inicio_diurna?>'//'06:00';
   	var fin_diurno='<?=$hora_fin_diurna?>'//'21:59';



 function calcular(){
 	//var $btn = $('#btn_calcular').button('loading');
 	var confirmar=confirm('Seguro desea actualizar estos dispositivos');

 	if(confirmar){
 		try {
		 	$('#dispositivos tbody tr').each(function(index, el) {

		 	 	var id=$(this).find('td').eq(0).html();

		 	 	//var calulo=$(this).find('td').eq(8).html();

		 	 	var inicio=$(this).find('td').eq(4).html();
		 	 	var fin=$(this).find('td').eq(5).html();
		 		var servicio=$(this).find('td').eq(2).html();
		 		var cantidad=$(this).find('td').eq(3).html();
		 		var dias=$(this).find('td').eq(7).html();
		 		var porcentaje=$(this).find('td').eq(8).html();
		 		// var hora_inicio='';
		   // 		var hora_fin='';
		   // 		var tiempo_diurna='';
		   // 		var tiempo_nocturno='';
		   // 		var tiempo_diurna2=0;
		   // 		var tiempo_nocturno2=0;


		   		var precio_minuto=precios_minuto(servicio);

		 		var tiempos=calcularHorasDiurnasNocturnas(inicio,fin);

		 		var total=calcularPrecio(cantidad,precio_minuto[0],precio_minuto[1],dias,tiempos[0],tiempos[1],porcentaje);

		 		var calculo=$(this).find('td').eq(9).html('$ '+total.formatPrice()+' COP');

		 		var calculo_ftes=calcularFtes(dias,cantidad,tiempos[0],tiempos[1],porcentaje);

		 		var ftes_diurno=calculo_ftes[0];

		 		var ftes_nocturno=calculo_ftes[1];

		 		var total_ftes=calculo_ftes[2];


		 		$(this).find('td').eq(11).html(ftes_diurno);
		 		$(this).find('td').eq(12).html(ftes_nocturno);
		 		$(this).find('td').eq(13).html(total_ftes);


		 		$.ajax({
		            url:"<?php echo Yii::$app->request->baseUrl . '/centro-costo/actualizar_precio'; ?>",
		            type:'POST',
		            dataType:"json",
		            cache:false,
		            async:false,
		            data: {
		                id: id,
		                total:total,
		                ftes_diurno:ftes_diurno,
		                ftes_nocturno:ftes_nocturno,
		                total_ftes:total_ftes
		                
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

		}catch(err) {
		    
		    alert(err.message);
		    alert('Ocurrio un error contacte con el desarrollador del sitio');

		    $('#body_ayuda').html('ERROR <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');
		}
 		
 	}else{
 		return false;
 	}
 	//$btn.button('reset');
 }


 function precios_minuto(serv){
 	var value=serv;
 	 for ( var index=0; index < servicios.length; index++ ) {
    	console.log("servicio id= "+value)
    	//alert('entro aqui')
        if(servicios[index]['id']==value){
        	
        	servicio_id=servicios[index]['servicio_id'];
        	precio_servicio=servicios[index]['precio'];
        	precio_servicio_minuto=(((precio_servicio/30))/8)/60;
        	precio_servicio_nocturno=servicios[index]['precio_nocturno'];
        	precio_servicio_nocturno_minuto=(((precio_servicio_nocturno/30))/8)/60;
        	console.log("precio_servicio= "+precio_servicio)
        	console.log("precio_servicio_nocturno= "+precio_servicio_nocturno)
        	//alert('entro aqui')
        	break;
        }
    }

    var array=[precio_servicio_minuto,precio_servicio_nocturno_minuto];
    return array;

 }


 function calcularHorasDiurnasNocturnas(hora_ini,horario_fin){

 	


   		var jornada=moment.utc(hora_ini, "hh:mm");
   		hora_inicio=moment.utc(hora_ini, "hh:mm");
   	    hora_fin=moment.utc(horario_fin, "hh:mm");
   		inicio_nocturno=moment.utc(inicio_nocturno, "hh:mm");
   	    fin_nocturno=moment.utc(fin_nocturno, "hh:mm");
   		inicio_diurno=moment.utc(inicio_diurno, "hh:mm");
   		fin_diurno=moment.utc(fin_diurno, "hh:mm");
   		tiempo_nocturno=moment.utc('00:00', "hh-mm")
   		tiempo_diurna=moment.utc('00:00', "hh-mm")
   		var hora00=moment.utc('00:00', "hh:mm");
   		var hora24=moment.utc('24:00', "hh:mm");
   		var hi=hora_inicio;//.hour();
   		//console.log(hi)
   		var hf=hora_fin;//.hour();
   		//console.log(hf)
   		var muere=false;
   		if (hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)) {// desde las 00 a las 06
   			if (hf.isSameOrAfter(hora00) && hf.isBefore(inicio_diurno) && hi.isBefore(hf)) {
	   			tiempo_nocturno2=hora_inicio.diff(hora_fin);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
		   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
		   		//console.log("+N 0-6= "+tiempo_nocturno.format("HH:mm"))
			   	muere=true;
	   		}else{
	   			tiempo_nocturno2=hora_inicio.diff(inicio_diurno);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
		   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
			   	//console.log("+N 0-6= "+tiempo_nocturno.format("HH:mm"))
	   		}
   		}else if (hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)) {// desde las 06 hasta las 21
   			if (hf.isSameOrAfter(inicio_diurno) && hf.isBefore(inicio_nocturno) && hi.isBefore(hf)) {
	   			tiempo_diurna2=hora_inicio.diff(hora_fin);
	   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
			   	//console.log("+D 6= "+tiempo_diurna.format("HH:mm"))
			   	muere=true;
	   		}else{
	   			tiempo_diurna2=hora_inicio.diff(inicio_nocturno);
	   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
			   	//console.log("+D 6= "+tiempo_diurna.format("HH:mm"))
	   		}
   		}else if (hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)) {// desde las 21 a las 00
   			if(hf.isSameOrAfter(inicio_nocturno) && hf.isBefore(hora24) && hi.isBefore(hf)){
   				tiempo_nocturno2=hora_inicio.diff(hora_fin);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
			   	muere=true;
			   	//console.log("+N 22= "+tiempo_nocturno.format("HH:mm"))
   			}else{
   				tiempo_nocturno2=hora_inicio.diff(hora24);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
			   	//console.log("+N 22= "+tiempo_nocturno.format("HH:mm"))
   			}
   		}
   		//buscando la hora final
   		if (!muere) {
   			if (hf.isSameOrAfter(hora00) && hf.isBefore(inicio_diurno)) {// desde las 00 a las 06
   				if(hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)){
   					//sumar diurno y de 21 a 24
   					tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
		   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
				   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
				   	//console.log("+D muere 0-6= "+tiempo_diurna.format("HH:mm"))
				   	tiempo_nocturno2=inicio_nocturno.diff(hora24);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
   				}else if(hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)){
   					//sumar de 22 a 24
   					tiempo_nocturno2=inicio_nocturno.diff(hora24);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
   				}
	   			tiempo_nocturno2=hora00.diff(hora_fin);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
		   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
			   	//console.log("+N muere 0-6= "+tiempo_nocturno.format("HH:mm"))
	   		}else if (hf.isSameOrAfter(inicio_diurno) && hf.isBefore(inicio_nocturno)) {//desde las 06 a las 21
	   			if(hi.isSameOrAfter(inicio_diurno) && hi.isBefore(inicio_nocturno)){
   					//sumar de 22 a 24 y de 0 a 6
   					tiempo_nocturno2=inicio_nocturno.diff(hora24);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
				   	tiempo_nocturno2=hora00.diff(inicio_diurno);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
   				}else if(hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)){
   					//sumar de 0 a 6
				   	tiempo_nocturno2=hora00.diff(inicio_diurno);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 6-22= "+tiempo_nocturno.format("HH:mm"))
   				}
	   			tiempo_diurna2=inicio_diurno.diff(hora_fin);
	   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
			   	//console.log("+D muere 6-22= "+tiempo_diurna.format("HH:mm"))
	   		}else if (hf.isSameOrAfter(inicio_nocturno) && hf.isBefore(hora24)) {//desde las 21 a las 00
   				if(hi.isSameOrAfter(inicio_nocturno) && hi.isBefore(hora24)){
   					//sumar de 0 a 6 y sumar diurno
				   	tiempo_nocturno2=hora00.diff(inicio_diurno);
		   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
			   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
				   	//console.log("+N muere 22-24= "+tiempo_nocturno.format("HH:mm"))
				   	tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
		   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
				   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
				   	//console.log("+D muere 22-24= "+tiempo_diurna.format("HH:mm"))
   				}else if(hi.isSameOrAfter(hora00) && hi.isBefore(inicio_diurno)){
   					// sumar diurno
   					tiempo_diurna2=inicio_diurno.diff(inicio_nocturno);
		   			tiempo_diurna.add(0+Math.abs(moment.duration(tiempo_diurna2).hours()), 'hours')
				   			.add(0+Math.abs(moment.duration(tiempo_diurna2).minutes()), 'minutes');
				   	//console.log("+D muere 22-24= "+tiempo_diurna.format("HH:mm"))
   				}
	   			tiempo_nocturno2=inicio_nocturno.diff(hora_fin);
	   			tiempo_nocturno.add(0+Math.abs(moment.duration(tiempo_nocturno2).hours()), 'hours')
		   			.add(0+Math.abs(moment.duration(tiempo_nocturno2).minutes()), 'minutes');
			   	//console.log("+N muere 22= "+tiempo_nocturno.format("HH:mm"))
	   		}
   		}
   		//rectificacion por reseteo de calculos hasta 24 horas
   		if (jornada.isSame(hora00) && (hi.duration().asMinutes()-hf.duration().asMinutes())==0) {
   			tiempo_nocturno=moment.utc('00:00', "hh-mm")
   			tiempo_diurna=moment.utc('00:00', "hh-mm")
   			tiempo_nocturno.add(8, 'hours')
		   			.add(0, 'minutes');
		   	tiempo_diurna.add(16, 'hours')
		   			.add(0, 'minutes');
   		}
   		
   		console.log("D= "+tiempo_diurna.format("HH:mm"));
	   	console.log("N= "+tiempo_nocturno.format("HH:mm"));

	   	var array = [tiempo_diurna, tiempo_nocturno];

	   	return array;

   	}


   	function calcularPrecio(cantidad,precio_servicio_minuto,precio_servicio_nocturno_minuto,dias,tiempo_diurna,tiempo_nocturno,porcentaje){
   		//console.log("precio_servicio_minuto= "+precio_servicio_minuto)
   		var cantidad =parseInt(cantidad);
   		//console.log("Precio nocturno= "+col_mes_nocturno);
   		var dias_prestados=parseInt(dias);
   		var dia1diurno=(moment.duration(tiempo_diurna.format("HH:mm")).asMinutes())*precio_servicio_minuto;
   		var total_diurno=((dia1diurno*dias_prestados)*parseInt(porcentaje))/100;
   		col_mes_diurno=Number(Math.round(total_diurno+'e2')+'e-2');
   		//console.log("Precio diurno= "+col_mes_diurno)
   		var dia1nocturno=(moment.duration(tiempo_nocturno.format("HH:mm")).asMinutes())*precio_servicio_nocturno_minuto;
   		var total_nocturno=((dia1nocturno*dias_prestados)*parseInt(porcentaje))/100;
   		col_mes_nocturno=Number(Math.round(total_nocturno+'e2')+'e-2');
   		//console.log("Precio nocturno= "+col_mes_nocturno)
   		suma_valor_servicio=(col_mes_diurno+col_mes_nocturno)*cantidad;

   		return suma_valor_servicio;
   		//$('#valor_servicio').html('$ '+suma_valor_servicio.formatPrice()+' COP');
   	}

   	function calcularFtes(dias,cantidad,tiempo_diurna,tiempo_nocturno,porcentaje){
   		var dias_prestados=parseInt(dias)
	   	var cantidad=parseInt(cantidad)
	   
	   	//ftes_diurno=(((tiempo_diurna.hours()+(tiempo_diurna.minutes()/60))*dias_prestados*cantidad)/240);
	   ftes_diurno=(((tiempo_diurna.hours()+(tiempo_diurna.minutes()/60))*dias_prestados*cantidad)/240)*parseInt(porcentaje)/100;

	   //ftes_nocturno=(((tiempo_nocturno.hours()+(tiempo_nocturno.minutes()/60))*dias_prestados*cantidad)/240);

	   ftes_nocturno=(((tiempo_nocturno.hours()+(tiempo_nocturno.minutes()/60))*dias_prestados*cantidad)/240)*parseInt(porcentaje)/100;
	   
	   	//var total_ftes=((ftes_diurno + ftes_nocturno)*parseInt($('#modeloprefactura-porcentaje').val()))/100;
	   
	   
	   	var str_diurno=ftes_diurno.toString();
	   	if(str_diurno.indexOf('.') != -1){
	   		var arr_diurno=str_diurno.split(".");     
	   	    var cant_diurno=arr_diurno[1].length;

	   	    if(cant_diurno>3){
	   	    	//ftes_diurno=Number(Math.round(ftes_diurno+'e3')+'e-3')
	   	    	ftes_diurno=myRound(ftes_diurno,3);
	   	    }


	   	 console.log('str_diurno:'+cant_diurno);
	    }

	    var str_nocturno=ftes_nocturno.toString();
	   	if(str_nocturno.indexOf('.') != -1){
	   		var arr_nocturno=str_nocturno.split(".");
	   	    var cant_nocturno=arr_nocturno[1].length;

	   	    if(cant_nocturno>3){
	   	    	//ftes_nocturno=Number(Math.round(ftes_nocturno+'e3')+'e-3')
	   	    	ftes_nocturno=myRound(ftes_nocturno,3);
	   	    }


	   	 console.log('str_diurno:'+cant_nocturno);
	    }

	    

	    console.log("ftes_diurno= "+ftes_diurno)
	   	console.log("ftes_nocturno= "+ftes_nocturno)
	   	console.log("*******************************")
	   	
	   	// ftes_diurno=Number(Math.round(ftes_diurno+'e2')+'e-2')
	   	// ftes_nocturno=Number(Math.round(ftes_nocturno+'e2')+'e-2')

	   	var total_ftes=(ftes_diurno + ftes_nocturno);
	   	//alert("Diurno:"+ftes_diurno+" -  Nocturno:"+ftes_nocturno);
	   	//alert(total_ftes);
	   	//total_ftes=Number(Math.round(total_ftes+'e3')+'e-3')
	   	//$('#ftes').html(total_ftes)
	   	console.log("ftes_diurno= "+ftes_diurno)
	   	console.log("ftes_nocturno= "+ftes_nocturno)
	   	console.log("total_ftes= "+total_ftes)


	   	return [ftes_diurno,ftes_nocturno,total_ftes];
	   //	calcularPrecio();
   	}

   	Number.prototype.formatPrice = function(n, x) {
        var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&.');
    };

    function myRound(num, dec) {
 	 	var exp = Math.pow(10, dec || 2); // 2 decimales por defecto
  		return parseInt(num * exp, 10) / exp;
	}
</script>