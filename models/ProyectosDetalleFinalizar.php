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
            ->select('fecha_sala_control,fecha_acta_entrega,fecha_entrega_factura,na_acta')
            ->from('proyectos_detalle_finalizar')
            ->where('id_proyecto='.$id_proyecto.' AND (id_sistema='.$id_sistema.')')
            ->one();
        $button="sala";
        if($query==null){
            $button=$button;
        }elseif($query['fecha_sala_control']!=null && $query['fecha_acta_entrega']==null || $query['fecha_acta_entrega']=='0000-00-00' && $query['na_acta']==false){
            $button='acta';
        }elseif($query['fecha_sala_control']!=null && $query['fecha_acta_entrega']!=null || $query['fecha_sala_control']=='0000-00-00' || $query['fecha_acta_entrega']=='0000-00-00'){
            $button='factura';
        }
        $array_finalizar=[];
        switch ($button) {
            case 'sala':
                $array_finalizar=[
                    'tipo_form'=>'sala',
                    'class'=>'btn-danger',
                    'icon'=>'<i class="fas fa-broadcast-tower"></i>',
                    'texto'=>'Ok Sala'
                ];
               

            break;

            case 'acta':

                $array_finalizar=[
                    'tipo_form'=>'acta',
                    'class'=>'btn-warning',
                    'icon'=>'<i class="fas fa-book"></i>',
                    'texto'=>'Ok Acta'
                ];
                
            break;

            case 'factura':
                $array_finalizar=[
                    'tipo_form'=>'factura',
                    'class'=>'btn-success',
                    'icon'=>'<i class="fas fa-clipboard-list"></i>',
                    'texto'=>'Factura'
                ];

            break;
            
            
        }
        return $array_finalizar;
    }

    public static function GetFinalizacion($id_proyecto,$id_sistema){
        $query = (new \yii\db\Query())
            ->select('fecha_sala_control,fecha_acta_entrega,fecha_entrega_factura,na_acta,na_sala,na_factura')
            ->from('proyectos_detalle_finalizar')
            ->where('id_proyecto='.$id_proyecto.' AND (id_sistema='.$id_sistema.')')
            ->one();

        return $query;
    }
}
