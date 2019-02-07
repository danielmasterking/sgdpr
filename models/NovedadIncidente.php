<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "novedad_incidente".
 *
 * @property integer $id
 * @property string $usuario
 * @property string $cargo
 * @property integer $id_incidente
 * @property string $fecha
 * @property integer $tipo_novedad
 */
class NovedadIncidente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $image;

    public static function tableName()
    {
        return 'novedad_incidente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tipo_novedad','desc_novedad'], 'required'],
            [['id_incidente', 'tipo_novedad'], 'integer'],
            [['fecha'], 'safe'],
            [['usuario', 'cargo'], 'string', 'max' => 50],
            [['image'],'file','extensions'=>'jpg, gif, png, pdf, jpeg, pdf, docx, xlsx', 'maxFiles' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'cargo' => 'Cargo',
            'id_incidente' => 'Id Incidente',
            'fecha' => 'Fecha Evento',
            'tipo_novedad' => 'Tipo Novedad',
            'desc_novedad'=>'Descripcion novedad',
            'image'=>'Adjuntos'
        ];
    }


    public function getTipo_novedades()
    {
        return $this->hasOne(TipoNovedadIncidente::className(), [ 'id'=>'tipo_novedad' ]);
    }

}
