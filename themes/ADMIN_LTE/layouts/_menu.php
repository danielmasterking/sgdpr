<?php 
  $controller=Yii::$app->controller->id;
  $action=Yii::$app->controller->action->id;

  $permisos = array();
  if( isset(Yii::$app->session['permisos-exito']) ){
    $permisos = Yii::$app->session['permisos-exito'];
  }

?>
<style type="text/css">
    .skin-blue .sidebar a {

      color: white !important;
    }

    .sidebar-menu > li > a:hover{
      color:white !important;
    }

    .box {
      border-top: 3px solid #Ffe701 ;
    }

    .skin-blue .main-header .navbar {

      background-color:#Ffe701  !important;



    }

    .skin-blue .main-header .logo {

      background-color: #Ffe701  !important;
    
    }

    .skin-blue .sidebar-menu > li.active > a {

      border-left-color: white !important;

    }

    .skin-blue .sidebar-menu > li:hover > a, .skin-blue .sidebar-menu > li.active > a, .skin-blue .sidebar-menu > li.menu-open > a {

      color: black !important;
      background: #Ffe701  !important;

    }

    .skin-blue .main-header .navbar .sidebar-toggle {

      color: black !important;

    }
  </style>
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar" >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $this->theme->baseUrl; ?>/dist/img/MmTwXdnv_400x400.png" class="img-circle" alt="User Image">

        </div>
        <div class="pull-left info">
          <p style="color: white;"><?= Yii::$app->session['usuario-exito']?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree" >
       <!--  <li class="header">MAIN NAVIGATION</li> -->
        
        <?php if(in_array("dependencia-ver", $permisos)){ ?>
        <li class="<?= $action=='index' && $controller=='centro-costo'?'active':'' ?>" >
          <a href="<?= Yii::$app->request->baseUrl.'/centro-costo/index'?>">
            &nbsp;<i class="fas fa-building"></i> <span>&nbsp;&nbsp;&nbsp;Dependencias</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("administrador", $permisos) || in_array("indicadores", $permisos)){ ?>
        <li class="<?= $action=='cordinadores' && $controller=='usuario'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/usuario/cordinadores'?>">
            &nbsp;<i class="fas fa-chart-pie"></i> <span>&nbsp;&nbsp;&nbsp;Indicadores</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("capacitacion", $permisos)){  ?>
        <li class="<?= $controller=='capacitacion'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/capacitacion/create'?>">
            <i class="fa fa-graduation-cap fa-fw"></i> <span>&nbsp;&nbsp;Capacitación</span>
          </a>
        </li>
        <?php } ?>

        <?php if(in_array("comite", $permisos)){ ?>
        <li class="<?= $controller=='comite'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/comite/create'?>">
            &nbsp;<i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Comite</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("visita", $permisos)){ ?>
        <li class="<?= $controller=='visita-dia'?'active':'' ?>">

          <a href="<?= Yii::$app->request->baseUrl.'/visita-dia/create'?>">
            <i class="fa fa-suitcase fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Visitas</span>
          </a>
        </li>

        <li class="<?= $controller=='visita-mensual'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/visita-mensual/index'?>">
            <i class="fa fa-suitcase fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Inspeccion Semestral</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("gestion-riesgo", $permisos)){ ?>
        <li class="<?= $controller=='gestionriesgo' && $action=='create'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/gestionriesgo/create'?>">
            &nbsp;<i class="fab fa-free-code-camp"></i> <span>&nbsp;&nbsp;&nbsp;Gestion Riesgo</span>
          </a>
        </li>

        <?php } ?>

        <?php if(in_array("desempeno-sg-sst", $permisos)){ ?>
        <li class="<?= $action=='informe-novedades' && $controller=='gestionriesgo'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/gestionriesgo/informe-novedades'?>">
            &nbsp;<i class="fab fa-free-code-camp"></i> <span>&nbsp;&nbsp;&nbsp;Desempeño SG-SST</span>
          </a>
        </li>
        <?php } ?>

        <?php if(in_array("investigacion", $permisos)){ ?>
        <li class="<?= $controller=='incidente'?'active':'' ?> text-left">
          <a href="<?= Yii::$app->request->baseUrl.'/incidente/index'?>">
            <i class="fa fa-search fa-fw"></i> 
            <span>&nbsp;&nbsp;&nbsp;Investigaciones</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("pedido", $permisos)){ ?>
        <li class="<?= $controller=='pedido' && $action=='create'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/create'?>">
            &nbsp;<i class="fa fa-shopping-cart fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Pedidos</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("revision-pedido", $permisos)){ ?>
        <li class="<?= $action=='revision' && $controller=='pedido'?'active':'' ?> text-left">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/revision'?>">
            &nbsp;&nbsp;<i class="fas fa-check-square"></i> 
            <span>&nbsp;&nbsp;&nbsp;Revisión Pedidos</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("revision-tecnica", $permisos)){ ?>
        <li class="<?= $action=='revision-tecnica' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/revision-tecnica'?>">
            &nbsp;<i class="fa fa-cog fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Revisión Técnica</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("revision-financiera", $permisos)){ ?>
        <li class="<?= $action=='revision-financiera' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/revision-financiera'?>">
            &nbsp;<i class="fas fa-money-bill-alt"></i> <span>&nbsp;&nbsp;&nbsp;Revisión Financiera</span>
          </a>
        </li>
        <?php } ?>

        <?php if(in_array("pedido", $permisos)){ ?>
        <li class="<?= $action=='historico' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/historico'?>">
            &nbsp;<i class="far fa-clock"></i> <span>&nbsp;&nbsp;&nbsp;Historico Pedidos</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("orden-compra", $permisos)){ ?>
        <li class="<?= $action=='orden-compra' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/orden-compra'?>">
            <i class="fa fa-file fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Creación OC/Solicitud</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("consolidado", $permisos)){ ?>
        <li class="<?= $action=='consolidar' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/consolidar'?>">
            <i class="fas fa-handshake"></i> <span>&nbsp;&nbsp;&nbsp;Consolidados</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("prefactura", $permisos)){ ?>
        <li class="<?= $action=='ventana_inicio' && $controller=='prefactura-fija'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/prefactura-fija/ventana_inicio'?>">
            &nbsp;<i class="far fa-file-excel"></i> <span>&nbsp;&nbsp;&nbsp;Prefactura</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("administrador", $permisos)){ ?>
        <li class="<?= $action=='prefactura-index' && $controller=='pedido'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/pedido/prefactura-index'?>">
            &nbsp;<i class="far fa-file-excel"></i> <span>&nbsp;&nbsp;&nbsp;Prefactura-pedido</span>
          </a>
        </li>
        <?php }?>
        
        <?php if(in_array("servicio-prefacturacion", $permisos)){ ?>
        <li class="<?= $action=='informedispositivos' && $controller=='prefactura-fija'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/prefactura-fija/informedispositivos'?>">
            <i class="fa fa-briefcase fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Servicios de Pre-facturación</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("presupuestos", $permisos)){ ?>
        <!-- <li class="<?= $action=='index' && $controller=='proyectos'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/proyectos/index'?>">
            &nbsp;<i class="fas fa-dollar-sign"></i> <span>&nbsp;&nbsp;&nbsp;Presupuestos Proyectos</span>
          </a>
        </li> -->
         <?php }?>
        <?php if(in_array("ver-proyectos", $permisos)){ ?>
        <li class="<?= $action=='index' && $controller=='proyecto-dependencia'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/proyecto-dependencia/index'?>">
            &nbsp;<i class="fas fa-city"></i> <span>&nbsp;&nbsp;&nbsp;Proyectos</span>
          </a>
        </li>
        <?php }?>
       
        <?php if(in_array("administrador", $permisos)){ ?>
        <li class="<?= $action=='index' && $controller=='usuario'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/usuario/index'?>">
            <i class="fa fa-user fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Usuarios</span>
          </a>
        </li>

        <li class="<?=  $controller=='rol'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/rol/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Roles</span>
          </a>
        </li>

        <li class="<?=  $controller=='permiso'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/permiso/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Permisos</span>
          </a>
        </li>

        <li class="<?=  $controller=='zona'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/zona/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Regionales</span>
          </a>
        </li>

        <li class="<?=  $controller=='distrito'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/distrito/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Distritos</span>
          </a>
        </li>
       

        <li class="<?=  $controller=='area-dependencia'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/area-dependencia/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Area Dependencia</span>
          </a>
        </li>

        <li class="<?=  $controller=='zona-dependencia'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/zona-dependencia/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Zona Dependencia</span>
          </a>
        </li>

        <!-- <li class="<?=  $controller=='categoria-visita'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/categoria-visita/index'?>">
            <i class="far fa-dot-circle"></i> <span>Categoria</span>
          </a>
        </li>

        <li class="<?=  $controller=='resultado'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/resultado/index'?>">
            <i class="far fa-dot-circle"></i> <span>Resultados</span>
          </a>
        </li>


        <li class="<?=  $controller=='novedad-categoria-visita'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/novedad-categoria-visita/index'?>">
            <i class="far fa-dot-circle"></i> <span>Novedad Categoria</span>
          </a>
        </li> -->

        <li class="<?=  $controller=='tiponovedadincidente'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/tiponovedadincidente/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Tipo Novedad Investigacion</span>
          </a>
        </li>

       <!--  <li class="<?=  $controller=='valor-novedad'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/valor-novedad/index'?>">
            <i class="far fa-dot-circle"></i> <span>Valores Novedades</span>
          </a>
        </li>

        <li class="<?=  $controller=='mensaje-novedad'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/mensaje-novedad/index'?>">
            <i class="far fa-envelope"></i> <span>Mensaje Novedades</span>
          </a>
        </li> -->

        <li class="<?=  $controller=='marca'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/marca/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Marcas</span>
          </a>
        </li>

        <li class="<?=  $controller=='novedad'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/novedad/index'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Novedades</span>
          </a>
        </li>

        <li class="<?=  $controller=='empresa'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/empresa/create'?>">
            <i class="fa fa-cogs fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Conf-Prefactura</span>
          </a>
        </li>

        <li class="<?=  $controller=='consultasgestion'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/consultasgestion/create'?>">
            <i class="far fa-dot-circle"></i> <span>&nbsp;&nbsp;&nbsp;Conf-Gestion</span>
          </a>
        </li>

        <li class="<?=  $controller=='manualapp'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/manualapp/create'?>">
            <i class="fa fa-book fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Conf-Manual</span>
          </a>
        </li>

         <li class="<?=  $controller=='categoria-visita'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/categoria-visita/index'?>">
            <i class="fa fa-book fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Conf-Visita-quincenal</span>
          </a>
        </li>

        <li class="<?=  $action=='ventana_calcular' && $controller=='centro-costo'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/centro-costo/ventana_calcular'?>">
            <i class="fa fa-calculator fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Calcular precios anual</span>
          </a>
        </li>


        <li class="<?=  $action=='reporte_ingreso' && $controller=='usuario'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/usuario/reporte_ingreso'?>">
            <i class="far fa-clock"></i> <span>&nbsp;&nbsp;&nbsp;Registro ingreso</span>
          </a>
        </li>

        <li class="<?=  $action=='index' && $controller=='tareassistema'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/tareassistema/index'?>">
            <i class="fa fa-book fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Diario</span>
          </a>
        </li>
        <?php } ?>
        <?php if(in_array("administrador", $permisos) or in_array("ver-notificaciones", $permisos)){ ?>
        <li class="<?=  $action=='index' && $controller=='notificacion'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/notificacion/index'?>">
            &nbsp;<i class="far fa-comment"></i> <span>&nbsp;&nbsp;&nbsp;Notificaciones</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("administrador", $permisos) || in_array("maestras", $permisos)){ ?>
        <li class="<?=  $action=='index' && $controller=='proveedor'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/proveedor/index'?>">
            <i class="fa fa-wrench fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Maestras</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("modificar-proyectos", $permisos)){ ?>
        <li class="<?= $action=='index' && $controller=='sistema-proyectos'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/sistema-proyectos/index'?>">
            &nbsp;<i class="fas fa-genderless"></i> <span>&nbsp;&nbsp;&nbsp;Sistema-Proyectos</span>
          </a>
        </li>

        <li class="<?= $action=='index' && $controller=='tipo-reportes'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/tipo-reportes/index'?>">
            &nbsp;<i class="fas fa-genderless"></i> <span>&nbsp;&nbsp;&nbsp;Tipo-reportes-Proyectos</span>
          </a>
        </li>
        <?php }?>

        <?php if(in_array("equivalencia-contable", $permisos)){ ?>
        <li class="<?=  $action=='create' && $controller=='equivalencia'?'active':'' ?>">
          <a href="<?= Yii::$app->request->baseUrl.'/equivalencia/create'?>">
            <i class="fa fa-balance-scale fa-fw"></i> <span>&nbsp;&nbsp;&nbsp;Equivalencia</span>
          </a>
        </li>
        <?php }?>


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>