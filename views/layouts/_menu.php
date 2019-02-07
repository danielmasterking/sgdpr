<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$permisos = array();
if( isset(Yii::$app->session['permisos-exito']) ){
	$permisos = Yii::$app->session['permisos-exito'];
}
//$this->title = 'Exito';
?>
<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php 
			$MENU=array();
			array_push(
				$MENU, 
				array('label' => '<i class="fa fa-home fa-fw"></i> Home', 'url' => ['site/inicio'])
				);
			
			if(in_array("dependencia-ver", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-building-o fa-fw"></i> Dependencias', 'url' => ['centro-costo/index'])
					);
			}
			if(in_array("administrador", $permisos) || in_array("coordinador", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-user fa-fw"></i> Indicadores', 'url' => ['usuario/cordinadores'])
					);
			}
			if(in_array("capacitacion", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-graduation-cap fa-fw"></i> Capacitación', 'url' => ['capacitacion/create'])
					);
			}
			if(in_array("comite", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Comite', 'url' => ['comite/create'])
					);
			}
			if(in_array("siniestro", $permisos)){}
			if(in_array("visita", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-suitcase fa-fw"></i> Visitas', 'url' => ['visita-dia/create'])
					);
			}

			if(in_array("inspeccion-semestral", $permisos) || in_array("administrador", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-suitcase fa-fw"></i> Inspeccion Semestral', 'url' => ['visita-mensual/index'])
					);
			}

			//if (Yii::$app->session['area-usuario']=='Riesgos' or Yii::$app->session['ambas-areas-usuario']=='S' ) {
			if(in_array("gestion-riesgo", $permisos)){	
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-free-code-camp fa-fw"></i> Gestion Riesgo', 'url' => ['gestionriesgo/create'])
					);
			}

			if(in_array("desempeno-sg-sst", $permisos)){				
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-free-code-camp fa-fw"></i> Desempeño SG-SST', 'url' => ['gestionriesgo/informe-novedades'])
					);
			}


			if(in_array("investigacion", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-search fa-fw"></i> Investigaciones', 'url' => ['incidente/index'])
					);
			}
			if(in_array("pedido", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-shopping-cart fa-fw"></i> Pedidos', 'url' => ['pedido/create'])
					);
			}
			if(in_array("revision-pedido", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-check-square-o fa-fw"></i> Revisión Pedidos', 'url' => ['pedido/revision'])
					);
			}
			if(in_array("revision-tecnica", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-cog fa-fw"></i> Revisión Técnica', 'url' => ['pedido/revision-tecnica'])
					);
			}
			if(in_array("revision-financiera", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-money fa-fw"></i> Revisión Financiera', 'url' => ['pedido/revision-financiera'])
					);
			}
			if(in_array("pedido", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-clock-o fa-fw"></i> Historico Pedidos', 'url' => ['pedido/historico'])
					);
			}
			if(in_array("orden-compra", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-file fa-fw"></i>Creación OC/Solicitud', 'url' => ['pedido/orden-compra'])
					);
			}
			if(in_array("consolidado", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-handshake-o fa-fw"></i> Consolidados', 'url' => ['pedido/consolidar'])
					);
			}
			if(in_array("prefactura", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-file-excel-o fa-fw"></i> Prefactura', 'url' => ['prefactura-fija/ventana_inicio'])
					);
			}

			if(in_array("servicio-prefacturacion", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-briefcase fa-fw"></i> Servicios de Pre-facturación', 'url' => ['prefactura-fija/informedispositivos'])
					);
			}
			


			
			if(in_array("presupuestos", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-usd fa-fw"></i>Presupuestos Proyectos', 'url' => ['proyectos/index'])
					);
			}
			if(in_array("administrador", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-user fa-fw"></i> Usuarios', 'url' => ['usuario/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Roles', 'url' => ['rol/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Permisos', 'url' => ['permiso/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Regionales', 'url' => ['zona/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Distritos', 'url' => ['distrito/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Area Dependencia', 'url' => ['area-dependencia/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Zona Dependencia', 'url' => ['zona-dependencia/index']),
					// array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Categoria', 'url' => ['categoria-visita/index']),
					// array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Resultados', 'url' => ['resultado/index']),
					//array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Novedad Categoria', 'url' => ['novedad-categoria-visita/index']),

					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Tipo Novedad Investigacion', 'url' => ['tiponovedadincidente/index']),
					//array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Valores Novedades', 'url' => ['valor-novedad/index']),
					//array('label' => '<i class="fa fa-commenting-o fa-fw"></i> Mensaje Novedades', 'url' => ['mensaje-novedad/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Marcas', 'url' => ['marca/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Novedades', 'url' => ['novedad/index']),
					//array('label' => '<i class="fa fa-wrench fa-fw"></i> Maestras', 'url' => ['proveedor/index']),
					array('label' => '<i class="fa fa-cogs fa-fw"></i> Conf-Prefactura', 'url' => ['empresa/create']),
					array('label' => '<i class="fa fa-pie-chart fa-fw"></i> indicadores', 'url' => ['indicador/index']),
					
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Conf-Gestion', 'url' => ['consultasgestion/create']),
					array('label' => '<i class="fa fa-book fa-fw"></i> Conf-Manual', 'url' => ['manualapp/create']),
					array('label' => '<i class="fa fa-gear fa-fw"></i> Conf-Visita-quincenal', 'url' => ['categoria-visita/index']),
					//array('label' => '<i class="fa fa-suitcase fa-fw"></i> Visitas', 'url' => ['visita-dia/create']),
					array('label' => '<i class="fa fa-calculator fa-fw"></i> Calcular precios anual', 'url' => ['centro-costo/ventana_calcular']),
					array('label' => '<i class="fa fa-clock-o fa-fw"></i> Registro ingreso', 'url' => ['usuario/reporte_ingreso']),
					//array('label' => '<i class="fa fa-commenting-o fa-fw"></i> Notificaciones', 'url' => ['notificacion/index']),
					array('label' => '<i class="fa fa-book fa-fw"></i> Diario', 'url' => ['tareassistema/index']),
					array('label' => '<i class="fa fa-dot-circle-o fa-fw"></i> Tipo Infractor', 'url' => ['tipoinfractor/index'])
					
					);
			}

			if(in_array("administrador", $permisos) or in_array("ver-notificaciones", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-commenting-o fa-fw"></i> Notificaciones', 'url' => ['notificacion/index'])
					);
			}

			if(in_array("administrador", $permisos) || in_array("maestras", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-wrench fa-fw"></i> Maestras', 'url' => ['proveedor/index'])
					);

			}


			if(in_array("equivalencia-contable", $permisos)){
				array_push(
					$MENU, 
					array('label' => '<i class="fa fa-balance-scale fa-fw"></i> Equivalencia', 'url' => ['equivalencia/create'])
					);
			}
			?>
			<?php 
			array_push(
				$MENU, 
				array('label' => '<i class="fa fa-sign-out fa-fw"></i> Salir', 'url' => ['site/logout'])
				);
			echo Nav::widget([
				'options' => ['class' => 'nav nav-pills nav-stacked'],
				'items' => $MENU,
				'encodeLabels' => false,
				]);
			?>
		</div>
	</div>
</nav>

