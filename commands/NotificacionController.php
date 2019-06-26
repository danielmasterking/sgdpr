<?php 
namespace app\commands;
use Yii;
use yii\console\Controller;
use app\models\Notificacion;
use app\models\DetallePedido;

class NotificacionController extends Controller
{

	public function actionPedido(){
		date_default_timezone_set ( 'America/Bogota');
		$fecha=date('Y-m-d');
		$pendiente = DetallePedido::find()->where('fecha_revision_coordinador="'.$fecha.'"')->all();

		$titulo="Revision de pedidos ";
		$tr="";
		foreach ($pendiente as $key => $value) {
			$tr.="<tr>";
			$tr.="<td>".$value->pedido->fecha."</td>
                        <td>".$value->pedido->dependencia->nombre."</td>
                        <td>".$value->producto->maestra->proveedor->nombre."</td>
                        <td>".$value->producto->material."</td>
                        <td>".$value->producto->texto_breve."</td>
                        <td>".$value->cantidad."</td>
                        <td>".$value->observaciones."</td>
                        <td>".$value->ordinario."</td>
                        <td>".strtoupper($value->pedido->solicitante)."</td>
                        <td>".$value->usuario_aprobador_revision."</td>
                        <td>".$value->fecha_revision_coordinador."</td>
                        ";
            $tr.="</tr>";
		}
        $descripcion="
            <table class='table table-bordered my-data'>
                <thead>
                    <tr>
                      <th>Fecha Pedido</th>
                      <th>Dependencia</th>
                      <th>Proveedor</th>
                      <th>Material</th>
                      <th>Texto breve</th>
                      <th>Cantidad</th>
                      <th>Observaciones</th>
                      <th>Ordinario</th>
                      <th>Solicitante</th>
                      <th>Usuario aprueba</th>
                      <th>Fecha aprueba</th>


                    </tr>
                </thead>
                <tbody>
                    ".$tr."
                </tbody>
            </table>


        ";
        $solicitantes=[$pendiente->pedido->solicitante];
        Notificacion::CrearNotificacion($titulo,$descripcion,$solicitantes);
		/*$model = new Notificacion();
	    $model->setAttribute('titulo','Creada por cron');
	    $model->setAttribute('descripcion','Fue creada por cron');
	    $model->setAttribute('fecha_inicio',date('Y-m-d'));
	    $model->setAttribute('fecha_final',date('Y-m-d'));
	    $model->save();

	    $model2=new NotificacionUsuario;
	    $model2->setAttribute('id_notificacion',$model->id);
	    $model2->setAttribute('usuario','administrador');
	    $model2->save();*/
	    //echo "entra aqui";
	}


}

?>