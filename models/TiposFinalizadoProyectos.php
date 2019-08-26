<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tipos_finalizado_proyectos".
 *
 * @property integer $id
 * @property integer $nombre
 */
class TiposFinalizadoProyectos extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipos_finalizado_proyectos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['nombre'], 'string'],
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
        ];
    }
}
