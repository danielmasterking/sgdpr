<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use app\models\ProyectoSeguimiento;
use app\models\SistemaProyectos;
use app\models\LogFechaProyectos;
/**
 * This is the model class for table "proyectos".
 *
 * @property integer $id
 * @property string $nombre
 * @property string $ceco
 * @property string $solicitante
 * @property string $orden_interna_gasto
 * @property string $orden_interna_activo
 * @property string $presupuesto_total
 * @property string $presupuesto_seguridad
 * @property string $presupuesto_riesgo
 * @property string $presupuesto_heas
 * @property string $suma_total
 * @property string $suma_seguridad
 * @property string $suma_riesgo
 * @property string $suma_heas
 * @property string $fecha_finalizacion
 * @property string $created_on
 * @property string $modificado_por
 * @property string $modified_in
 *
 * @property ProyectoPedidoEspecial[] $proyectoPedidoEspecials
 * @property ProyectoPedidos[] $proyectoPedidos
 * @property CentroCosto $ceco0
 * @property ProyectosPresupuesto[] $proyectosPresupuestos
 */
class Proyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
   public $image2;//imagen que acompaña el detalle.


    public static function tableName()
    {
        return 'proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ceco', 'solicitante', /*'presupuesto_total', 'presupuesto_seguridad', 'presupuesto_riesgo', 'presupuesto_activo', 'presupuesto_gasto', 'fecha_finalizacion', */'created_on'/*,'nombre'*/], 'required'],
            [['presupuesto_total', 'suma_total', 'suma_seguridad', 'suma_riesgo', 'suma_activo', 'suma_gasto'], 'number'],
            [['fecha_finalizacion', 'created_on', 'modified_in','presupuesto_seguridad', 'presupuesto_riesgo'], 'safe'],
            [['nombre', 'orden_interna_gasto', 'orden_interna_activo'], 'string', 'max' => 64],
            [['ceco', 'modificado_por'], 'string', 'max' => 15],
            [['solicitante'], 'string', 'max' => 50],
            [['ceco'], 'exist', 'skipOnError' => true, 'targetClass' => CentroCosto::className(), 'targetAttribute' => ['ceco' => 'codigo']],
            [['image2'],'safe'],
            [['image2'],'file','extensions'=>'jpg, gif, png, pdf, jpeg, pdf, docx, xlsx', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'ceco' => 'Dependencia',
            'solicitante' => 'Solicitante',
            'orden_interna_gasto' => 'Orden Interna Gasto',
            'orden_interna_activo' => 'Orden Interna Activo',
            'presupuesto_total' => 'Presupuesto Total',
            'presupuesto_seguridad' => 'Presupuesto Seguridad',
            'presupuesto_riesgo' => 'Presupuesto Riesgo',
            'presupuesto_heas' => 'Presupuesto Heas',
            'suma_total' => 'Suma Total',
            'suma_seguridad' => 'Suma Seguridad',
            'suma_riesgo' => 'Suma Riesgo',
            'suma_heas' => 'Suma Heas',
            'fecha_finalizacion' => 'Fecha Finalizacion',
            'created_on' => 'Fecha creacion',
            'modificado_por' => 'Modificado Por',
            'modified_in' => 'Modified In',
            'presupuesto_activo'=>'Presupuesto activo',
            'presupuesto_gasto'=>'Presupuesto gasto',
            'image2' => 'Cotizacion',
            'fecha_apertura'=>'Fecha Finalizacion'

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectoPedidoEspecials()
    {
        return $this->hasMany(ProyectoPedidoEspecial::className(), ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectoPedidos()
    {
        return $this->hasMany(ProyectoPedidos::className(), ['proyecto_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCecoo()
    {
        return $this->hasOne(CentroCosto::className(), ['codigo' => 'ceco']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProyectosPresupuestos()
    {
        return $this->hasMany(ProyectosPresupuesto::className(), ['fk_proyectos' => 'id']);
    }


     public function Provedores(){
        $query=Proveedor::find()->all();

        $list=ArrayHelper::map($query,'id','nombre');

        return $list;
    }

    public function ProyectoProvedor($id_proyecto){
        $query=ProyectoProvedor::find()->where('id_proyecto='.$id_proyecto)->all();

        $provedores=[];
        foreach ($query as $row) {
            $provedores[$row->id_provedor]=$row->provedor->nombre;
        }
        
        return $provedores;
    }


    public function Usuarios(){
        $query=Usuario::find()->all();
        $list=ArrayHelper::map($query,'usuario','usuario');

        return $list;
    }

    public function ProyectoUsuario($id_proyecto){
        $query=ProyectoUsuarios::find()->where('id_proyecto='.$id_proyecto)->all();

        $usuarios=[];

        foreach ($query as $row) {
            $usuarios[$row->usuario]=$row->usuario;
        }

        return $usuarios; 


    }

    public function NumSeguimientos($id){
        $query=ProyectoSeguimiento::find()->where('id_proyecto='.$id)->count();
        return $query;
    }

    public function PromedioSistema($id_proyecto,$id_sistema){
        /*$query=ProyectoSeguimiento::find()
        ->where('id_proyecto='.$id_proyecto.' AND id_sistema=7 AND id_tipo_reporte=6')
        ->orderby('fecha DESC')
        ->limit(1)
        ->one();

        if($query!=null){
            //echo "entra en if";
            return $query->avance;

        }else{*/
            //echo "entra en else";
            $porcentaje_total = (new \yii\db\Query())
            ->select('id,avance')
            ->from('proyecto_seguimiento')
            ->where('id_proyecto='.$id_proyecto.' AND (id_sistema='.$id_sistema.'  OR id_sistema=7) AND id_tipo_reporte=6')
            ->orderby('fecha DESC')
            ->limit(1)
            ->one();

            return $porcentaje_total['avance'];
        //}
    }


    public function Sistemas(){
        $query=SistemaProyectos::find()->all();

        $list=ArrayHelper::map($query,'id','nombre');

        return $list;
    }

    public function ProyectoSistemas($id_proyecto){
        $query=ProyectoSistema::find()->where('id_proyecto='.$id_proyecto)->all();

        $sistemas=[];
        foreach ($query as $row) {
            $sistemas[$row->id_sistema]=$row->sistema->nombre;
        }

        return $sistemas;
    }

    public static function Seguimiento($id){
        $detalle=ProyectoSeguimiento::find()->where('id_proyecto='.$id)->orderby('fecha DESC')->limit(1)->one();
        return $detalle;
    }

    public static function PromedioProyecto($id){
        $sistemas=ProyectoSistema::find()->where('id_proyecto='.$id)->all();
        $cont_sistema=0;
        $acumulador=0;
        $model=new Proyectos;
        foreach($sistemas as $st): 
            $promedio_sistema=$model->PromedioSistema($id,$st->id_sistema);
            $acumulador=$acumulador+$promedio_sistema;
            $cont_sistema++;
        endforeach;

        if($cont_sistema==0){
            $promedio_total="0";
        }else{
            //echo "acumulador=".$acumulador."- num=".$cont_sistema;
            $promedio_total=round(($acumulador/$cont_sistema),2, PHP_ROUND_HALF_DOWN);
            if($promedio_total>100){
                $promedio_total=100;
            }
        }

        return $promedio_total;
    }


    public function Get_fecha_finalizacion($id,$fecha){

        $model=LogFechaProyectos::find()->where('id_proyecto='.$id)->orderby('fecha Desc')->limit(1)->one();

        if($model!=null){
            return $model->fecha;
        }else{
            return $fecha;
        }
    }

}
