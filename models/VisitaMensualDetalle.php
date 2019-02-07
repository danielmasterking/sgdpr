<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visita_mensual_detalle".
 *
 * @property integer $id
 * @property string $descripcion
 * @property integer $categoria_id
 * @property integer $novedad_id
 * @property string $plan_de_accion
 */
class VisitaMensualDetalle extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visita_mensual_detalle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descripcion', 'categoria_id', 'novedad_id','fecha','fecha_novedad','aplica_plan'], 'required'],
            [['categoria_id', 'novedad_id'], 'integer'],
            [['plan_de_accion'], 'string'],
            //[['descripcion'], 'string', 'max' => 100],
            [['file'],'file','extensions'=>'jpg, gif, png, pdf, jpeg, doc, docx, xls, xlsx, ppt, pptx', 'maxFiles' => 5],
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
            'categoria_id' => 'Categoria',
            'novedad_id' => 'Novedad ',
            'plan_de_accion' => 'Plan De Accion',
            'file' => 'Archivos',
            'fecha'=>'Fecha',
            'fecha_novedad'=>'Fecha Novedad'
        ];
    }

    public function getCategoria()
    {
        return $this->hasOne(CategoriaVisita::className(), ['id' => 'categoria_id']);
    }

    public function getNovedad()
    {
        return $this->hasOne(NovedadCategoriaVisita::className(), ['id' => 'novedad_id']);
    }
}
