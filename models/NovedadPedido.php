<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novedad_pedido".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $plan_de_accion
 * @property integer $visita_mensual_id
 * @property string $fecha
 * @property integer $fecha_novedad
 */
class NovedadPedido extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novedad_pedido';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion','visita_mensual_id', 'fecha', 'fecha_novedad','aplica_plan'], 'required'],
            [['descripcion', 'plan_de_accion'], 'string'],
            [['visita_mensual_id'], 'integer'],
            [['fecha'], 'safe'],
            [['file'],'file','extensions'=>'jpg, gif, png, pdf, jpeg, doc, docx, xls, xlsx, ppt, pptx', 'maxFiles' => 5]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'plan_de_accion' => 'Plan De Accion',
            'visita_mensual_id' => 'Visita Mensual ID',
            'fecha' => 'Fecha',
            'fecha_novedad' => 'Fecha Novedad',
            'file'=>'Archivos'
        ];
    }
}
