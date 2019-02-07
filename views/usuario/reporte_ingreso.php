<?php 

 use yii\helpers\Html;
 use yii\grid\GridView;
 use yii\helpers\Url;
 use kartik\date\DatePicker;
 use kartik\widgets\Select2;

 $this->title = 'Reporte de ingreso de usuarios';


?>


    <div class="page-header">
      <h1><small><i class="fas fa-clock"></i></small> <?= Html::encode($this->title) ?></h1>
    </div>


<form id="form_excel" method="post" action="<?php echo Url::toRoute('usuario/reporte_ingreso')?>">
	<div class="row">
		<div class="col-md-3">
			<?php 
	            echo Select2::widget([
	                'id' => 'user',
	                'name' => 'user',
	                'value' => '',
	                'data' => $list_user,
	                'options' => ['multiple' => false, 'placeholder' => 'POR USUARIO...']
	            ]);
        	?>
		</div>
		
		<div class="col-md-3">
            <?= 
                DatePicker::widget([
                    'id' => 'fecha',
                    'name' => 'fecha',
                    'options' => ['placeholder' => 'Fecha'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]);
            ?>
        </div>


        <div class="col-md-3">
            <?= 
                DatePicker::widget([
                    'id' => 'fecha_hasta',
                    'name' => 'fecha_hasta',
                    'options' => ['placeholder' => 'Fecha Hasta'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ]);
            ?>
        </div>

        <div class="col-md-2">
        	 
	        <button type="button" class="btn btn-primary" onclick="consultar(0)">
	            <i class="fa fa-search fa-fw"></i> Buscar
	        </button> 

        </div>

        

	</div>


</form>

<br>

<div class="container">
    <button type="button" class="btn btn-primary" onclick="consultar(0)">
        <i class="fas fa-sync-alt"></i> Actualizar
    </button> 


    <button type="submit" class="btn btn-primary" onclick="excel()">
        <i class="fas fa-file-excel"></i> Descargar Busqueda en Excel
    </button>
    
</div>



  <div class="row">
    <hr>
    <div id="info"></div>
    <div id="partial"><?=$partial?></div>
  </div>


  <script type="text/javascript">
     $(document).on( "click", "#partial .pagination li", function() {
        var page = $(this).attr('p');
        consultar(page);
    });
     
  	function consultar(page){
        var form=document.getElementById("form_excel");
        var input=document.getElementById("excel");
        if(input!=null){
            form.removeChild(input);
        }
        var user=$('#user').val();
        var fecha=$('#fecha').val();
        var fecha_hasta=$('#fecha_hasta').val();
        var buscar=$("#buscar").val();
       
        $.ajax({
            url:"<?php echo Url::toRoute('usuario/reporte_ingreso')?>",
            type:'POST',
            dataType:"json",
            cache:false,
            data: {
                user: user,
                fecha: fecha,
                fecha_hasta:fecha_hasta,
                buscar: buscar,
                page: page,
                
            },
            beforeSend:  function() {
                $('#info').html('Cargando... <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i>');
            },
            success: function(data){
                $("#partial").html(data.respuesta);
                $("#info").html('');
            }
        });
    }


    function excel(){
        var form=document.getElementById("form_excel");
        var input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'excel';
        input.name = 'excel';
        input.value = '';
        form.appendChild(input);
        form.submit();
    }


  </script>