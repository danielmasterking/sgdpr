<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "proyectos_detalle_finalizar".
 *
 * @property integer $id
 * @property integer $id_proyecto
 * @property integer $id_sistema
 * @property string $fecha_sala_control
 * @property string $adjunto_sala_control
 * @property integer $na_sala
 * @property string $observacion_sala
 * @property string $fecha_acta_entrega
 * @property string $adjunto_acta_entrega
 * @property integer $na_acta
 * @property string $observacion_acta
 * @property string $recibe_factura
 * @property integer $na_factura
 * @property string $fecha_entrega_factura
 * @property string $observacion_factura
 */
class ProyectosDetalleFinalizar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'proyectos_detalle_finalizar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_proyecto', 'id_sistema'], 'required'],
            [['id_proyecto', 'id_sistema', 'na_sala', 'na_acta', 'na_factura'], 'integer'],
            [['fecha_sala_control', 'fecha_acta_entrega', 'fecha_entrega_factura'], 'safe'],
            [['observacion_sala', 'observacion_acta', 'observacion_factura'], 'string'],
            [['adjunto_sala_control', 'adjunto_acta_entrega'], 'string', 'max' => 200],
            [['recibe_factura'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_proyecto' => 'Id Proyecto',
            'id_sistema' => 'Id Sistema',
            'fecha_sala_control' => 'Fecha Sala Control',
            'adjunto_sala_control' => 'Adjunto Sala Control',
            'na_sala' => 'Na Sala',
            'observacion_sala' => 'Observacion Sala',
            'fecha_acta_entrega' => 'Fecha Acta Entrega',
            'adjunto_acta_entrega' => 'Adjunto Acta Entrega',
            'na_acta' => 'Na Acta',
            'observacion_acta' => 'Observacion Acta',
            'recibe_factura' => 'Recibe Factura',
            'na_factura' => 'Na Factura',
            'fecha_entrega_factura' => 'Fecha Entrega Factura',
            'observacion_factura' => 'Observacion Factura',
        ];
    }

    public static function ActivarEstado($id_proyecto,$id_sistema){
       $query = (new \yii\db\Query())
            ->select('tf.nombre boton,fp.id_tipo_finalizado')
            ->from(' finalizados_asignados_proyectos fp')
            ->innerjoin('tipos_finalizado_proyectos tf','tf.id=fp.id_tipo_finalizado')
            ->where('fp.id_proyecto='.$id_proyecto.' AND (fp.id_sistema='.$id_sistema.')')
            ->orderby('fp.orden ASC');
            //->all();
        

        $rowsCount= clone $query;
        $modelcount = $rowsCount->count();

        if($modelcount>0){

            $boton=[];
            $comand=$query->all();
            foreach ($comand as $key => $value) {
                $query2=(new \yii\db\Query())
                ->select('pf.*')
                ->from(' proyectos_detalle_finalizar pf')
                ->where('id_proyecto='.$id_proyecto.' AND (id_sistema='.$id_sistema.') AND (id_tipo_finalizado='.$value['id_tipo_finalizado'].')')
                ->one();

                if ($query2==null) {
                    $boton['boton']=$value['boton'];
                    $boton['tipo_finalizado']=$value['id_tipo_finalizado'];
                    $boton['estado']="A";
                    $boton['clase']="btn-primary";
                    $boton['icon']="fa-check";
                    break;
                }
            }
            

            if (count($boton)==0) {
                $boton['boton']="Finalizado";
                $boton['tipo_finalizado']='';
                $boton['estado']="F";
                $boton['clase']="btn-success";
                $boton['icon']="fa-clipboard-list";
                
            }
        }else{
            $boton['boton']="No asignado";
            $boton['tipo_finalizado']='';
            $boton['estado']="S";
            $boton['clase']="btn-info";
            $boton['icon']="fa-clipboard-list";
        }
        return $boton;
        
    }

    public static function GetFinalizacion($id_proyecto,$id_sistema){
        $query = (new \yii\db\Query())
            ->select('fecha_sala_control,fecha_acta_entrega,fecha_entrega_factura,na_acta,na_sala,na_factura,fecha_migo,na_migo')
            ->from('proyectos_detalle_finalizar')
            ->where('id_proyecto='.$id_proyecto.' AND (id_sistema='.$id_sistema.')')
            ->one();

        return $query;
    }
}
