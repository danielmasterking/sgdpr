<?php

namespace app\controllers;

use app\models\CategoriaVisita;
use app\models\CentroCosto;
use app\models\DetalleVisitaDia;
use app\models\DetalleVisitaSeccion;
use app\models\Seccion;
use app\models\Usuario;
use app\models\ValorNovedad;
use app\models\VisitaDia;
use app\models\Resultado;
use app\models\MensajeNovedad;
use app\models\VisitaFotos;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
/**
 * VisitaDiaController implements the CRUD actions for VisitaDia model.
 */
class VisitaDiaController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
        	'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'ViewFromCordinador', 'Pdf', 'create', 'Update', 'delete', 'DeleteFromCordinador'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'view', 'ViewFromCordinador', 'Pdf', 'create', 'Update', 'delete', 'DeleteFromCordinador'],
                        'roles'   => ['@'], //para usuarios logueados
                    ],
                ],  
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all VisitaDia models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => VisitaDia::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VisitaDia model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $dependencia)
    {
        //$this->layout = 'main_sin_menu';

        $rows_negativo = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','ct.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
        ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
        ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$dependencia."') AND (vd.id=".$id." ) ")
        ->groupBy(['ct.nombre']);

        $command_negativo = $rows_negativo->createCommand();
        
        $resultado_negativo = $command_negativo->queryAll();

        $arreglo_negativo=array();
        foreach ($resultado_negativo as $key1 => $value1) {
            
            $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
        }

        $json_negativo=json_encode($arreglo_negativo);

        $categorias=CategoriaVisita::find()->where('estado="A"')->orderBy(['nombre' => SORT_ASC])->all();
        $model_visita=new  DetalleVisitaDia;

        return $this->render('view', [
            'model'       => $this->findModel($id),
            'dependencia' => $dependencia,
            'json_negativo'=>$json_negativo,
            'id_visita'=>$id,
            'categorias'=>$categorias,
            'model_visita'=>$model_visita
        ]);
    }

    public function actionImprimir($id, $dependencia){

         $rows_negativo = (new \yii\db\Query())
        ->select(['COUNT(dvd.id)AS total','ct.nombre'])
        ->from('detalle_visita_dia AS dvd')
        ->leftJoin(['visita_dia AS vd'], 'dvd.visita_dia_id=vd.id')
        ->leftJoin(['resultado'], 'dvd.resultado_id=resultado.id')
        ->leftJoin(['novedad_categoria_visita AS nc'], 'dvd.novedad_categoria_visita_id=nc.id')
        ->leftJoin(['categoria_visita AS ct'], 'nc.categoria_visita_id=ct.id')
        ->where("( resultado.nombre='Malo' OR resultado.nombre='Regular') AND (vd.centro_costo_codigo='".$dependencia."') AND (vd.id=".$id." ) ")
        ->groupBy(['ct.nombre']);

        $command_negativo = $rows_negativo->createCommand();
        
        $resultado_negativo = $command_negativo->queryAll();

        $arreglo_negativo=array();
        foreach ($resultado_negativo as $key1 => $value1) {
            
            $arreglo_negativo[]=array('name'=>(string)$value1['nombre'],'y'=>(int)$value1['total'] );
        }

        $json_negativo=json_encode($arreglo_negativo);

        $categorias=CategoriaVisita::find()->where('estado="A"')->orderBy(['nombre' => SORT_ASC])->all();
        $model_visita=new  DetalleVisitaDia;


        $content = $this->renderPartial('_imprimir', array(
            'model'       => $this->findModel($id),
            'dependencia' => $dependencia,
            'json_negativo'=>$json_negativo,
            'id_visita'=>$id,
            'categorias'=>$categorias,
            'model_visita'=>$model_visita
        ), true);
        

       /*$pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Visita Quincenal'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Visita Quincenal-'.date('Y-m-d')], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
        
        // return the pdf output as per the destination setting
        return $pdf->render();*/



        $pdf = Yii::$app->pdf; // or new Pdf();
        $pdf->filename ='Visita-Quincenal'.date('Y-m-d').'.pdf';
        $pdf->content=$content;
        $pdf->destination = Pdf::DEST_DOWNLOAD;

        return $pdf->render();
    }


    public function actionViewFromCordinador($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),

        ]);
    }

    public function actionPdf($id)
    {

        $model = $this->findModel($id);

        $pdf = Yii::$app->pdf;

        $pdf->filename = 'Visita_Quincenal_' . $model->fecha . '_' . $model->dependencia->nombre . '.pdf';

        $pdf->content = $this->renderPartial('_pdf', ['model' => $model], true);

        $pdf->destination = Pdf::DEST_DOWNLOAD;

        return $pdf->render();

        //return $this->redirect('view', ['id' => $id,]);

    }




    /**
     * Creates a new VisitaDia model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model                          = new VisitaDia();
        $array_post                     = Yii::$app->request->post();
        Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/visita_fotos/';
        $shortPath                      = '/uploads/visita_fotos/';
        Yii::$app->session->setTimeout(5400);
        $categorias       = CategoriaVisita::find()->where('estado="A"')->orderBy(['nombre' => SORT_ASC])->all();
        $dependencias     = CentroCosto::find()->where('estado NOT IN("C") AND indicador_visita="S"')->orderBy(['nombre' => SORT_ASC])->all();
        $secciones        = Seccion::find()->orderBy(['id' => SORT_ASC])->all();
        $usuario          = Usuario::findOne(Yii::$app->session['usuario-exito']);
        $zonasUsuario     = array();
        $marcasUsuario    = array();
        $distritosUsuario = array();

        if ($usuario != null) {

            $zonasUsuario     = $usuario->zonas;
            $marcasUsuario    = $usuario->marcas;
            $distritosUsuario = $usuario->distritos;

        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $image = UploadedFile::getInstances($model, 'image');

            if ($image !== null) {
                /*$model->foto = $image->name;
                $ext         = end((explode(".", $image->name)));
                $name        = date('Ymd') . rand(1, 10000) . '' . $model->foto;
                $path        = Yii::$app->params['uploadPath'] . $name;
                $model->foto = $shortPath . $name;
                $model->save();
                $image->saveAs($path);*/
                foreach ($image as $img) {
                   

                    $archivo=new VisitaFotos();
                    $name    = date('Ymd') . rand(1, 10000) . '' . $img->name;
                    $path    = Yii::$app->params['uploadPath'] . $name;
                    $archivo->setAttribute('archivo', $shortPath . $name);
                    $archivo->setAttribute('id_visita', $model->id);
                    $img->saveAs($path);
                    $verifica_imagen=Yii::$app->verificar_imagen->esImagen($path);
                    if ($verifica_imagen) {       
                       Yii::$app->verificar_imagen->Redimenzionar($path,$img->type);
                       //unlink($path);
                    }
                    $archivo->save();
                    
                }



            }

            //Guardar Modelo relacionado

            /*obtener cantidad de novedades
             *
             * Tener en cuenta que cuando indice sea = 10 hay que almacenar sección
             *
             */
            //   echo "<pre>";
            // print_r($array_post);
            //   echo "</pre>";
            $tamanoNovedades =$array_post['categoria'];

            foreach ($tamanoNovedades as $key => $value) {
                $array=$_POST[$value];

                $contador=0;
                foreach ($array as $key1 => $value1) {
                   // echo "repite<br>";
                    // echo "<pre>";
                    // print_r($value1);
                    // echo "</pre>";
                    //print $value1['respuesta'];
                    $modelDetalle = new DetalleVisitaDia();
                    $modelDetalle->setAttribute('visita_dia_id', $model->id);

                    $modelDetalle->setAttribute('novedad_categoria_visita_id',$value1['pregunta']);
                    

                    if(isset($value1['respuesta'])){

                        $modelDetalle->setAttribute('resultado_id',$value1['respuesta']);

                    }else{

                        $resultado=Resultado::find()->where('nombre="No aplica" ')->one();

                        $modelDetalle->setAttribute('resultado_id',$resultado->id);
                    }
                    
                    if(isset($value1['mensaje'])){

                        $modelDetalle->setAttribute('mensaje_novedad_id',$value1['mensaje']);

                    }else{

                        $valor_nov_id=ValorNovedad::find()->where(' novedad_categoria_visita_id='.$value1['pregunta'].' AND resultado_id='.$resultado->id.' ')->one();

                        $mensaje=MensajeNovedad::find()->where(' valor_novedad_id='.$valor_nov_id->id.' AND (mensaje IN("N/A","No aplica")) ')->one();

                        $modelDetalle->setAttribute('mensaje_novedad_id',$mensaje->id);
                    }

                    
                    if(isset($value1['comentario'])){

                        $modelDetalle->setAttribute('observacion',$value1['comentario']);

                    }else{

                        $modelDetalle->setAttribute('observacion','');
                    }
                    
                    $modelDetalle->save();

                    if (isset($value1['secciones'])) {

                        $secciones=$value1['secciones'];

                        foreach ($secciones as $key2 => $value2) {

                            $detalleSeccion = new DetalleVisitaSeccion();
                            $detalleSeccion->setAttribute('detalle_visita_dia_id', $modelDetalle->id);
                            $detalleSeccion->setAttribute('seccion_id',$value2['seccion']);
                            $detalleSeccion->setAttribute('resultado',$value2['respuesta-seccion']);
                            //$detalleSeccion->setAttribute('mensaje',$value2['mensaje-seccion']);
                            //$detalleSeccion->setAttribute('observacion',$value2['comentario-seccion']);
                            $detalleSeccion->save();
                        }
                        
                    }



                    $contador++;
                }

            }
            /*foreach ($tamanoNovedades as $key => $value) {
                $array=$_POST[$value];
                $contador=0;
                foreach ($array as $key1 => $value1) {
                    //echo $array['text-novedad'][$contador]."<br>";
                    //echo $array['novedad_categoria'][$contador]."<br>";
                    //echo $array['mensaje-novedad'][$contador]."<br>";
                    //echo $array['valor-novedad'][$contador]."<br>";
                   $modelDetalle = new DetalleVisitaDia();
                    $modelDetalle->setAttribute('visita_dia_id', $model->id);

                    if(isset($array['text-novedad'][$contador])){

                        $modelDetalle->setAttribute('observacion', $array['text-novedad'][$contador]);

                    }else{

                        $modelDetalle->setAttribute('observacion','');
                    }

                    $modelDetalle->setAttribute('novedad_categoria_visita_id',$array['novedad_categoria'][$contador]);

                    if(isset($array['mensaje-novedad'][$contador])){

                        $modelDetalle->setAttribute('mensaje_novedad_id', $array['mensaje-novedad'][$contador]);

                    }else{

                         $resultado=Resultado::find()->where('nombre="No aplica" ')->one();
                         $mensaje=MensajeNovedad::find()->where(' valor_novedad_id='.$array['novedad_categoria'][$contador].' AND mensaje="N/A" ')->one();

                        // $modelDetalle->setAttribute('mensaje_novedad_id',$mensaje->id);
                    }

                    if(isset($array['valor-novedad'][$contador])){
                        $modelDetalle->setAttribute('resultado_id', $array['valor-novedad'][$contador]);
                    }else{
                        $modelDetalle->setAttribute('resultado_id',$resultado->id);
                    }

                    $modelDetalle->save();

                    $contador++;
                }
                 //echo "<pre>";
                // print_r($_POST[$value]);
               //  echo $value;
                //echo "</pre>";
            }*/
            /*foreach ($tamanoNovedades as $key => $value) {
               // echo "Entra en el primer foreach <br>";
                $valor_novedad= $array_post['valor-novedad'];
                $contador=0;
                foreach ($valor_novedad as $key_valnovedad => $value_valnovedad) {
                     

                    $modelDetalle = new DetalleVisitaDia();
                    $modelDetalle->setAttribute('visita_dia_id', $model->id);
                    $modelDetalle->setAttribute('observacion', $array_post['text-novedad'][$value][$contador]);
                    $modelDetalle->setAttribute('novedad_categoria_visita_id',$array_post['novedad_categoria'][$value][$contador]);
                    $modelDetalle->setAttribute('mensaje_novedad_id', $array_post['mensaje-novedad'][$value][$contador]);
                    $modelDetalle->setAttribute('resultado_id', $valor_novedad[$value][$contador]);
                    if($modelDetalle->save()){
                        echo $modelDetalle->getErrors();

                        $contador++;
                    }

                }

            }*/
           



            // $tamanoNovedades = array_key_exists('cantidad', $array_post) ? $array_post['cantidad'] : 0;

            // for ($i = 1; $i <= $tamanoNovedades; $i++) {

            //     if ($i != 10) {

            //         $obs          = array_key_exists('text-novedad-' . $i, $array_post) ? $array_post['text-novedad-' . $i] : '';
            //         $mensaje      = array_key_exists('mensaje-novedad-' . $i, $array_post) ? $array_post['mensaje-novedad-' . $i] : '';
            //         $valorNovedad = array_key_exists('valor-novedad-' . $i, $array_post) ? $array_post['valor-novedad-' . $i] : '';

            //         $valorNovedadModel = ValorNovedad::findOne($valorNovedad);

            //         if ($valorNovedadModel != null) {

            //             $modelDetalle = new DetalleVisitaDia();
            //             $modelDetalle->setAttribute('visita_dia_id', $model->id);
            //             $modelDetalle->setAttribute('observacion', $obs);
            //             $modelDetalle->setAttribute('novedad_categoria_visita_id', $i);
            //             $modelDetalle->setAttribute('mensaje_novedad_id', $mensaje);
            //             $modelDetalle->setAttribute('resultado_id', $valorNovedadModel->resultado_id);
            //             $modelDetalle->save();

            //         }

            //     } else {

            //         $obsA               = array_key_exists('txt-seccion-a', $array_post) ? $array_post['txt-seccion-a'] : '';
            //         $obsB               = array_key_exists('txt-seccion-b', $array_post) ? $array_post['txt-seccion-a'] : '';
            //         $obsC               = array_key_exists('txt-seccion-c', $array_post) ? $array_post['txt-seccion-a'] : '';
            //         $mensajeA           = array_key_exists('mensaje-seccion-a', $array_post) ? $array_post['mensaje-seccion-a'] : '';
            //         $mensajeB           = array_key_exists('mensaje-seccion-b', $array_post) ? $array_post['mensaje-seccion-b'] : '';
            //         $mensajeC           = array_key_exists('mensaje-seccion-c', $array_post) ? $array_post['mensaje-seccion-c'] : '';
            //         $valorNovedadA      = array_key_exists('valor-seccion-a', $array_post) ? $array_post['valor-seccion-a'] : '';
            //         $valorNovedadB      = array_key_exists('valor-seccion-b', $array_post) ? $array_post['valor-seccion-b'] : '';
            //         $valorNovedadC      = array_key_exists('valor-seccion-c', $array_post) ? $array_post['valor-seccion-c'] : '';
            //         $seccionA           = array_key_exists('seccion-a', $array_post) ? $array_post['seccion-a'] : '';
            //         $seccionB           = array_key_exists('seccion-b', $array_post) ? $array_post['seccion-b'] : '';
            //         $seccionC           = array_key_exists('seccion-c', $array_post) ? $array_post['seccion-c'] : '';
            //         $valorNovedadModelA = ValorNovedad::findOne($valorNovedadA);
            //         $valorNovedadModelB = ValorNovedad::findOne($valorNovedadB);
            //         $valorNovedadModelC = ValorNovedad::findOne($valorNovedadC);

            //         if ($valorNovedadModelA != null && $seccionA != '') {

            //             $modelDetalle = new DetalleVisitaDia();
            //             $modelDetalle->setAttribute('visita_dia_id', $model->id);
            //             $modelDetalle->setAttribute('observacion', $obsA);
            //             $modelDetalle->setAttribute('novedad_categoria_visita_id', $i);
            //             $modelDetalle->setAttribute('mensaje_novedad_id', $mensajeA);
            //             $modelDetalle->setAttribute('resultado_id', $valorNovedadModelA->resultado_id);
            //             $modelDetalle->save();
            //             $detalleSeccion = new DetalleVisitaSeccion();
            //             $detalleSeccion->setAttribute('detalle_visita_dia_id', $modelDetalle->id);
            //             $detalleSeccion->setAttribute('seccion_id', $seccionA);
            //             $detalleSeccion->save();
            //         }

            //         if ($valorNovedadModelB != null && $seccionB != '') {

            //             $modelDetalle = new DetalleVisitaDia();
            //             $modelDetalle->setAttribute('visita_dia_id', $model->id);
            //             $modelDetalle->setAttribute('observacion', $obsB);
            //             $modelDetalle->setAttribute('novedad_categoria_visita_id', $i);
            //             $modelDetalle->setAttribute('mensaje_novedad_id', $mensajeB);
            //             $modelDetalle->setAttribute('resultado_id', $valorNovedadModelB->resultado_id);
            //             $modelDetalle->save();
            //             $detalleSeccion = new DetalleVisitaSeccion();
            //             $detalleSeccion->setAttribute('detalle_visita_dia_id', $modelDetalle->id);
            //             $detalleSeccion->setAttribute('seccion_id', $seccionB);
            //             $detalleSeccion->save();
            //         }

            //         if ($valorNovedadModelC != null && $seccionC != '') {

            //             $modelDetalle = new DetalleVisitaDia();
            //             $modelDetalle->setAttribute('visita_dia_id', $model->id);
            //             $modelDetalle->setAttribute('observacion', $obsC);
            //             $modelDetalle->setAttribute('novedad_categoria_visita_id', $i);
            //             $modelDetalle->setAttribute('mensaje_novedad_id', $mensajeC);
            //             $modelDetalle->setAttribute('resultado_id', $valorNovedadModelC->resultado_id);
            //             $modelDetalle->save();
            //             $detalleSeccion = new DetalleVisitaSeccion();
            //             $detalleSeccion->setAttribute('detalle_visita_dia_id', $modelDetalle->id);
            //             $detalleSeccion->setAttribute('seccion_id', $seccionC);
            //             $detalleSeccion->save();
            //         }

            //     }

            // }
            // $model = new VisitaDia();
            // return $this->render('create', [
            //     'model'            => $model,
            //     //'modelDetalle' => $modelDetalle,
            //     'categorias'       => $categorias,
            //     'dependencias'     => $dependencias,
            //     'marcasUsuario'    => $marcasUsuario,
            //     'distritosUsuario' => $distritosUsuario,
            //     'periodica'        => 'active',
            //     'zonasUsuario'     => $zonasUsuario,
            //     'secciones'        => $secciones,
            //     'done'             => '200',
            //]);
            Yii::$app->session->setFlash('success','Visita creada correctamente');

            return $this->redirect(['create']);

        } else {
            return $this->render('create', [
                'model'            => $model,
                //'modelDetalle' => $modelDetalle,
                'categorias'       => $categorias,
                'dependencias'     => $dependencias,
                'marcasUsuario'    => $marcasUsuario,
                'distritosUsuario' => $distritosUsuario,
                'periodica'        => 'active',
                'zonasUsuario'     => $zonasUsuario,
                'secciones'        => $secciones,
            ]);
        }
    }

    /**
     * Updates an existing VisitaDia model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        Yii::$app->session->setTimeout(5400);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing VisitaDia model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $dependencia)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['centro-costo/visita?id=' . $dependencia]);
    }

    public function actionDeleteFromCordinador($id, $usuario)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['usuario/visita?id=' . $usuario]);
    }

    /**
     * Finds the VisitaDia model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VisitaDia the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VisitaDia::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
