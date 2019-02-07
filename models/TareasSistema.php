<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tareas_sistema".
 *
 * @property integer $id
 * @property string $titulo
 * @property string $fecha
 * @property string $estado
 * @property string $descripcion
 */
class TareasSistema extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tareas_sistema';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fecha','estado','descripcion','titulo'], 'required'],
            [['fecha'], 'safe'],
            [['estado', 'descripcion'], 'string'],
            [['titulo'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'fecha' => 'Fecha',
            'estado' => 'Estado',
            'descripcion' => 'Descripcion',
        ];
    }
}
