<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novedad_capacitacion".
 *
 * @property integer $id
 * @property string $descripcion
 * @property string $plan_de_accion
 * @property integer $visita_mensual_id
 * @property integer $tema_cap_id
 * @property string $fecha
 * @property integer $fecha_novedad
 */
class NovedadCapacitacion extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'novedad_capacitacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion','visita_mensual_id', 'tema_cap_id', 'fecha', 'fecha_novedad','aplica_plan'], 'required'],
            [['descripcion', 'plan_de_accion'], 'string'],
            [['visita_mensual_id', 'tema_cap_id'], 'integer'],
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
            'tema_cap_id' => 'Tema Capacitacion',
            'fecha' => 'Fecha',
            'fecha_novedad' => 'Fecha Novedad',
            'file'=>'Archivos'
        ];
    }

     public function getNovedad()
    {
        return $this->hasOne(Novedad::className(), ['id' => 'tema_cap_id']);
    }
}
